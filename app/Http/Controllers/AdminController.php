<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use App\Models\InvitationCodeHistory;
use App\Models\User;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Usar middleware unificado
    }

    /**
     * Show the admin dashboard
     */
    public function index(Request $request)
    {
        try {
            // Log de depuración para admin
            \Log::info('=== PANEL ADMIN ACCEDIDO ===');
            \Log::info('Usuario: ' . auth()->user()->primer_nombre);
            \Log::info('Rol: ' . auth()->user()->rol);
            \Log::info('Admin panel visited: ' . (session()->has('admin_panel_visited') ? 'true' : 'false'));
            \Log::info('Welcome message en sesión: ' . (session()->has('welcome_message') ? 'true' : 'false'));
            \Log::info('Todas las variables de sesión: ' . json_encode(session()->all()));
            
            // Búsqueda para códigos activos
            $activeSearch = $request->get('active_search');
            $activeQuery = InvitationCode::where('used', false);
            
            if ($activeSearch) {
                $activeQuery->where(function($q) use ($activeSearch) {
                    $q->where('email', 'like', "%{$activeSearch}%")
                      ->orWhere('code', 'like', "%{$activeSearch}%");
                });
            }
            
            $activeCodes = $activeQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'active_page');
            
            // Búsqueda para historial de códigos eliminados
            $historySearch = $request->get('history_search');
            $historyQuery = InvitationCodeHistory::query();
            
            if ($historySearch) {
                $historyQuery->where(function($q) use ($historySearch) {
                    $q->where('email', 'like', "%{$historySearch}%")
                      ->orWhere('code', 'like', "%{$historySearch}%")
                      ->orWhere('deletion_reason', 'like', "%{$historySearch}%");
                });
            }
            
            $historyCodes = $historyQuery->orderBy('created_at', 'desc')->paginate(5, ['*'], 'history_page');
            
            // Verificar si hay un mensaje de bienvenida especial (registro)
            if (session()->has('welcome_message')) {
                $welcomeMessage = session('welcome_message');
                session()->put('admin_panel_visited', true); // Marcar como visitado
                
                \Log::info('Mostrando mensaje de bienvenida especial en admin (registro): ' . $welcomeMessage);
                return view('admin.panel', compact('activeCodes', 'historyCodes', 'activeSearch', 'historySearch'))->with('welcome_message', $welcomeMessage);
            }
            
            // Verificar si es la primera vez que el admin accede al panel en esta sesión
            if (!session()->has('admin_panel_visited')) {
                session()->put('admin_panel_visited', true);
                // Mensaje para administradores
                $welcomeMessage = '¡Bienvenido al panel de administración, ' . auth()->user()->primer_nombre . '!';
                \Log::info('Mostrando mensaje de bienvenida en admin (primera vez en sesión): ' . $welcomeMessage);
                
                // Enviar la vista con el mensaje de bienvenida
                return view('admin.panel', compact('activeCodes', 'historyCodes', 'activeSearch', 'historySearch'))->with('welcome_message', $welcomeMessage);
            }
            
            // Si ya visitó antes en esta sesión, no mostrar mensaje
            \Log::info('Panel admin ya visitado en esta sesión, no mostrando mensaje de bienvenida');
            return view('admin.panel', compact('activeCodes', 'historyCodes', 'activeSearch', 'historySearch'));
            
        } catch (\Exception $e) {
            Log::error('Error en AdminController::index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar el panel de administración.');
        }
    }

    /**
     * Search active codes via AJAX
     */
    public function searchActiveCodes(Request $request)
    {
        try {
            $search = $request->get('search');
            $query = InvitationCode::where('used', false);
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }
            
            $codes = $query->orderBy('created_at', 'desc')->get();
            
            $html = view('admin.partials.active-codes-table', compact('codes'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $codes->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en searchActiveCodes: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error en la búsqueda'], 500);
        }
    }

    /**
     * Search history codes via AJAX
     */
    public function searchHistoryCodes(Request $request)
    {
        try {
            $search = $request->get('search');
            $query = InvitationCodeHistory::query();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('deletion_reason', 'like', "%{$search}%");
                });
            }
            
            $codes = $query->orderBy('created_at', 'desc')->get();
            
            $html = view('admin.partials.history-codes-table', compact('codes'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $codes->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en searchHistoryCodes: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error en la búsqueda'], 500);
        }
    }

    /**
     * Send invitation codes to emails
     */
    public function sendInvitations(Request $request)
    {
        try {
            // Log de datos recibidos para debugging
            Log::info('=== INICIO sendInvitations ===');
            Log::info('Datos recibidos:', [
                'all_data' => $request->all(),
                'emails' => $request->input('emails'),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'is_ajax' => $request->ajax(),
                'expects_json' => $request->expectsJson()
            ]);

            // Filtrar emails vacíos antes de validar
            $emails = array_filter($request->input('emails', []), function($email) {
                return !empty(trim($email));
            });

            // Validar datos de entrada
            $validator = Validator::make([
                'emails' => array_values($emails) // Reindexar array
            ], [
                'emails' => 'required|array|max:5|min:1',
                'emails.*' => 'required|email|max:255'
            ], [
                'emails.required' => 'Debe proporcionar al menos un correo electrónico.',
                'emails.max' => 'No puede enviar más de 5 invitaciones a la vez.',
                'emails.min' => 'Debe proporcionar al menos un correo electrónico.',
                'emails.*.email' => 'Uno de los correos no tiene un formato válido.',
                'emails.*.max' => 'Uno de los correos es demasiado largo.'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                Log::error('Errores de validación en sendInvitations:', [
                    'errors' => $errors,
                    'failed_rules' => $validator->failed(),
                    'input_data' => $request->all()
                ]);
                return $this->handleResponse($request, false, implode(' ', $errors), 400);
            }

            // Normalizar emails ya filtrados
            $emails = collect($emails)
                ->map(fn($email) => strtolower(trim($email)))
                ->unique()
                ->values()
                ->toArray();

            $sentCount = 0;
            $errors = [];
            $successEmails = [];

            foreach ($emails as $email) {
                try {
                    $result = $this->processSingleInvitation($email);
                    
                    if ($result['success']) {
                        $sentCount++;
                        $successEmails[] = $email;
                    } else {
                        $errors[] = $result['message'];
                    }
                } catch (\Exception $e) {
                    Log::error("Error procesando invitación para {$email}: " . $e->getMessage());
                    $errors[] = "Error procesando invitación para {$email}.";
                }
            }

            // Preparar respuesta
            if ($sentCount > 0) {
                $message = "Se enviaron {$sentCount} invitación(es) correctamente.";
                if (!empty($errors)) {
                    $message .= " Algunos correos tuvieron problemas: " . implode(', ', array_slice($errors, 0, 3));
                }
                return $this->handleResponse($request, true, $message, 200, [
                    'sent_count' => $sentCount,
                    'success_emails' => $successEmails,
                    'errors' => $errors
                ]);
            } else {
                $errorMessage = 'No se pudo enviar ninguna invitación. ' . implode(', ', $errors);
                Log::error('No se pudo enviar ninguna invitación:', ['errors' => $errors]);
                return $this->handleResponse($request, false, $errorMessage, 400, ['errors' => $errors]);
            }

        } catch (\Exception $e) {
            Log::error('Error en AdminController::sendInvitations: ' . $e->getMessage());
            Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
            return $this->handleResponse($request, false, 'Error interno del servidor. Intente nuevamente.', 500);
        }
    }

    /**
     * Procesar una sola invitación
     */
    private function processSingleInvitation(string $email): array
    {
        // Verificar si el email ya está registrado como usuario
        if (User::where('email', $email)->exists()) {
            return ['success' => false, 'message' => "Este correo ya está registrado."];
        }

        // Verificar si ya existe un código activo para este email
        $existingActiveCode = InvitationCode::where('email', $email)->first();
        if ($existingActiveCode) {
            if ($existingActiveCode->used) {
                return ['success' => false, 'message' => "Este correo ya fue registrado."];
            } else {
                return ['success' => false, 'message' => "Ya existe una invitación para este correo."];
            }
        }

        // Crear código de invitación
        $invitationCode = InvitationCode::createForEmail($email);
        
        // Enviar email
        try {
            Mail::to($email)->send(new InvitationMail($invitationCode));
            return ['success' => true, 'message' => "Invitación enviada correctamente."];
        } catch (\Exception $e) {
            Log::error("Error enviando email a {$email}: " . $e->getMessage());
            // En lugar de eliminar el código, lo mantenemos pero informamos del error de email
            return [
                'success' => true, 
                'message' => "Invitación creada. Código: {$invitationCode->code}",
                'code' => $invitationCode->code,
                'expires_at' => $invitationCode->expires_at->format('d/m/Y H:i')
            ];
        }
    }

    /**
     * Manejar respuesta uniforme
     */
    private function handleResponse(Request $request, bool $success, string $message, int $statusCode = 200, array $data = []): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        // Mejorar mensajes de error para que sean más claros
        if (!$success) {
            if (strpos($message, 'Error del servidor') !== false) {
                $message = "Error interno del sistema. Por favor, intente nuevamente.";
            } elseif (strpos($message, 'HTTP Error: 400') !== false) {
                $message = "Error de validación. Verifique los datos ingresados.";
            } elseif (strpos($message, 'Error al enviar email') !== false) {
                $message = "Error al enviar el correo electrónico. El código de invitación se ha creado pero no se pudo enviar por email. Verifique la configuración de correo.";
            }
        }
        
        $response = array_merge(['success' => $success, 'message' => $message], $data);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($response, $statusCode);
        }
        
        $redirect = redirect()->route('admin.panel');
        return $success ? $redirect->with('success', $message) : $redirect->with('error', $message);
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
            
            Log::info("Código de invitación eliminado: {$invitationCode->email} por usuario: " . Auth::user()->email);
            
            return $this->handleResponse($request, true, 'Código de invitación eliminado correctamente y guardado en el historial.');
            
        } catch (\Exception $e) {
            Log::error('Error en AdminController::deleteInvitation: ' . $e->getMessage());
            return $this->handleResponse($request, false, 'Error al eliminar el código de invitación. Intente nuevamente.', 500);
        }
    }
}
