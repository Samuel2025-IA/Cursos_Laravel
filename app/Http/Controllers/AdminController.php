<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use App\Models\InvitationCodeHistory;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->rol !== 'admin') {
                abort(403, 'Acceso denegado. Solo los administradores pueden acceder a esta sección.');
            }
            return $next($request);
        });
    }

    /**
     * Show the admin dashboard
     */
    public function index()
    {
        // Obtener códigos activos
        $activeCodes = InvitationCode::orderBy('created_at', 'desc')->get();
        
        // Obtener historial de códigos eliminados
        $historyCodes = InvitationCodeHistory::orderBy('created_at', 'desc')->get();
        
        // Combinar ambos en una sola colección para mostrar en el historial
        $allCodes = collect();
        
        // Agregar códigos activos
        foreach ($activeCodes as $code) {
            $allCodes->push((object) [
                'id' => $code->id,
                'email' => $code->email,
                'code' => $code->code,
                'used' => $code->used,
                'expires_at' => $code->expires_at,
                'created_at' => $code->created_at,
                'updated_at' => $code->updated_at,
                'is_active' => true,
                'status' => $code->used ? 'used' : ($code->expires_at->isPast() ? 'expired' : 'active')
            ]);
        }
        
        // Agregar códigos del historial
        foreach ($historyCodes as $code) {
            $allCodes->push((object) [
                'id' => 'hist_' . $code->id,
                'email' => $code->email,
                'code' => $code->code,
                'used' => $code->used,
                'expires_at' => $code->expires_at,
                'created_at' => $code->created_at,
                'updated_at' => $code->updated_at,
                'is_active' => false,
                'status' => $code->status
            ]);
        }
        
        // Ordenar por fecha de creación descendente
        $allCodes = $allCodes->sortByDesc('created_at');
        
        // Paginar manualmente - 5 elementos por página
        $perPage = 5;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $allCodes->slice($offset, $perPage)->values();
        
        // Crear paginador personalizado
        $invitationCodes = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $allCodes->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
        
        return view('admin.panel', compact('invitationCodes'));
    }

    /**
     * Send invitation codes to emails
     */
    public function sendInvitations(Request $request)
    {
        // 🔍 DEBUG: Ver qué datos están llegando
        \Log::info('📥 DEBUG AdminController::sendInvitations - Request data:', [
            'all_request' => $request->all(),
            'emails_raw' => $request->emails,
            'request_method' => $request->method(),
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson()
        ]);
        
        // Filtrar emails vacíos y convertir a minúsculas
        $emails = array_filter($request->emails ?? [], function($email) {
            return !empty(trim($email));
        });

        // Convertir todos los emails a minúsculas
        $emails = array_map('strtolower', $emails);

        if (empty($emails)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar al menos un correo electrónico válido.'
                ], 400);
            }
            return redirect()->route('admin.panel')->with('error', 'Debe proporcionar al menos un correo electrónico válido.');
        }

        // Validar emails
        $request->validate([
            'emails' => 'array|max:5',
        ], [
            'emails.max' => 'No puede enviar más de 5 invitaciones a la vez.',
        ]);

        // Verificar correos duplicados en el mismo envío
        $duplicateEmails = array_diff_assoc($emails, array_unique($emails));
        if (!empty($duplicateEmails)) {
            $duplicateCount = count($duplicateEmails);
            $errorMessage = "No puedes enviar el mismo correo {$duplicateCount} vez(es) en el mismo envío. Por favor, elimina los correos duplicados.";
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }
            return redirect()->route('admin.panel')->with('error', $errorMessage);
        }

        $sentCount = 0;
        $errors = [];
        $createdCodes = [];
        $processedEmails = []; // Para evitar procesar el mismo email múltiples veces

        foreach ($emails as $email) {
            try {
                // Evitar procesar el mismo email múltiples veces
                if (in_array($email, $processedEmails)) {
                    continue;
                }
                $processedEmails[] = $email;

                // Validar email individual
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Uno de los correos no es válido.";
                    continue;
                }

                // Verificar si el email ya está registrado como usuario
                $existingUser = \App\Models\User::where('email', $email)->first();
                if ($existingUser) {
                    $errors[] = "Uno de los correos ya está registrado como usuario.";
                    continue;
                }

                // Verificar si ya existe un código ACTIVO para este email
                $existingActiveCode = InvitationCode::where('email', $email)->first();

                if ($existingActiveCode) {
                    if ($existingActiveCode->used) {
                        $errors[] = "Uno de los correos ya tiene un código usado.";
                    } else {
                        $errors[] = "Uno de los correos ya tiene un código de invitación activo.";
                    }
                    continue;
                }

                // Verificar si existe en el historial (códigos eliminados)
                $existingHistoryCode = InvitationCodeHistory::where('email', $email)->first();
                if ($existingHistoryCode) {
                    // Si existe en el historial, permitir reenvío sin error
                    // Solo mostrar un mensaje informativo
                }

                // Create invitation code
                $invitationCode = InvitationCode::createForEmail($email);
                
                // Send email
                Mail::to($email)->send(new InvitationMail($invitationCode));
                
                $createdCodes[] = [
                    'email' => $email,
                    'code' => $invitationCode->code
                ];

                $sentCount++;
            } catch (\Illuminate\Database\QueryException $e) {
                // Manejar error de duplicado de email
                if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $errors[] = "Uno de los correos ya existe en la base de datos.";
                } else {
                    $errors[] = "Error de base de datos.";
                }
            } catch (\Exception $e) {
                $errors[] = "Error enviando invitación.";
            }
        }

        if ($sentCount > 0) {
            $message = "Se enviaron {$sentCount} invitación(es) por email correctamente.";
            
            if (!empty($errors)) {
                $message .= " Errores: " . implode(', ', $errors);
            }
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'sent_count' => $sentCount,
                    'errors' => $errors
                ]);
            }
            return redirect()->route('admin.panel')->with('success', $message);
        } else {
            $errorMessage = 'No se pudo enviar ninguna invitación. ' . implode(', ', $errors);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => $errors
                ], 400);
            }
            return redirect()->route('admin.panel')->with('error', $errorMessage);
        }
    }

    /**
     * Delete an invitation code
     */
    public function deleteInvitation(InvitationCode $invitationCode, Request $request)
    {
        try {
            // Guardar en el historial antes de eliminar
            $status = $invitationCode->used ? 'used' : ($invitationCode->expires_at->isPast() ? 'expired' : 'deleted');
            InvitationCodeHistory::createFromInvitationCode($invitationCode, $status);
            
            // Eliminar de la tabla principal
            $invitationCode->delete();
            
            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Código de invitación eliminado correctamente y guardado en el historial.'
                ]);
            }
            
            // Si no es AJAX, devolver redirect tradicional
            return redirect()->route('admin.panel')->with('success', 'Código de invitación eliminado correctamente y guardado en el historial.');
            
        } catch (\Exception $e) {
            // Manejar errores
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el código de invitación: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.panel')->with('error', 'Error al eliminar el código de invitación.');
        }
    }
}
