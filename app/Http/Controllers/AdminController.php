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
            
            $activeCodes = $activeQuery->orderBy('created_at', 'desc')->paginate(5, ['*'], 'active_page');
            
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
            
            // Preparar estadísticas para el admin
            $stats = [
                'total_usuarios' => User::count(),
                'usuarios_admin' => User::where('rol', 'admin')->count(),
                'usuarios_normales' => User::where('rol', 'user')->count(),
                'codigos_activos' => InvitationCode::where('used', false)->where('expires_at', '>', now())->count()
            ];
            
            // El panel de admin ya no maneja alertas de bienvenida
            // Las alertas ahora se manejan en el dashboard
            
            return view('admin.panel', compact('activeCodes', 'historyCodes', 'activeSearch', 'historySearch', 'stats'));
            
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
            
            // Buscar solo códigos activos (no usados y no expirados)
            $query = InvitationCode::where('used', false)
                ->where('expires_at', '>', now());
            
            // Aplicar filtro de búsqueda si existe
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
            
            // Primero agrupar las condiciones del historial (usado O expirado)
            $query = InvitationCode::where(function($q) {
                $q->where('used', true)
                  ->orWhere('expires_at', '<=', now());
            });
            
            // Luego aplicar el filtro de búsqueda si existe
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
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
                    
                    // Dar un mensaje claro según el tipo de error
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $errors[] = "{$email} - Ya existe un código de invitación para este correo";
                    } elseif (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                        $errors[] = "{$email} - Error en la base de datos";
                    } else {
                        $errors[] = "{$email} - No se pudo procesar la invitación";
                    }
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
        // Normalizar email (minúsculas y sin espacios)
        $email = strtolower(trim($email));
        
        // Verificar si está vacío
        if (empty($email)) {
            Log::warning("Email vacío");
            return ['success' => false, 'message' => "Correo vacío detectado en el archivo"];
        }
        
        // Validar errores comunes de escritura ANTES de la validación general
        $errorEscritura = $this->detectarErrorEscritura($email);
        if ($errorEscritura) {
            Log::warning("Email mal escrito: '{$email}' - {$errorEscritura}");
            return ['success' => false, 'message' => "{$email} - {$errorEscritura}"];
        }
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Email con formato inválido: '{$email}'");
            return ['success' => false, 'message' => "{$email} - El formato del correo electrónico no es válido"];
        }
        
        Log::info("Procesando invitación para: {$email}");
        
        // Verificar si el email ya está registrado como usuario
        // Usar comparación case-insensitive para mayor seguridad
        $userExists = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->exists();
        Log::info("Usuario existe para {$email}: " . ($userExists ? 'SÍ' : 'NO'));
        
        if ($userExists) {
            Log::warning("Intento de enviar invitación a email ya registrado: {$email}");
            return ['success' => false, 'message' => "{$email} - Este correo ya está registrado en la base de datos"];
        }

        // Verificar si ya existe un código ACTIVO (no usado y no expirado) para este email
        // Usar comparación case-insensitive
        $existingActiveCode = InvitationCode::whereRaw('LOWER(email) = ?', [strtolower($email)])
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
            
        if ($existingActiveCode) {
            Log::warning("Intento de enviar invitación a email con código activo: {$email} (Código: {$existingActiveCode->code})");
            return ['success' => false, 'message' => "{$email} - Ya tiene un código de invitación enviado (expira el " . $existingActiveCode->expires_at->format('d/m/Y H:i') . ")"];
        }
        
        // Si hay códigos usados o expirados, los ignoramos y permitimos crear uno nuevo
        Log::info("No hay código activo para {$email}, procediendo a crear uno nuevo");

        // Crear código de invitación
        $invitationCode = InvitationCode::createForEmail($email);
        Log::info("Código creado para {$email}: {$invitationCode->code}");
        
        // Enviar email
        try {
            Mail::to($email)->send(new InvitationMail($invitationCode));
            Log::info("Email enviado correctamente a {$email}");
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
     * Send bulk invitations from CSV or Excel file
     */
    public function sendBulkInvitations(Request $request)
    {
        try {
            // Validar archivo - aceptar CSV y Excel
            $validator = Validator::make($request->all(), [
                'excel_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240' // 10MB máximo
            ], [
                'excel_file.required' => 'Debe seleccionar un archivo.',
                'excel_file.file' => 'El archivo no es válido.',
                'excel_file.mimes' => 'El archivo debe ser formato .csv, .txt, .xlsx o .xls',
                'excel_file.max' => 'El archivo no debe exceder 10MB.'
            ]);

            if ($validator->fails()) {
                return $this->handleResponse($request, false, $validator->errors()->first(), 400);
            }

            $file = $request->file('excel_file');
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Leer archivo según su extensión
            $emails = [];
            
            try {
                if ($extension === 'csv' || $extension === 'txt') {
                    // Leer CSV usando funciones nativas de PHP
                    $emails = $this->readCsvFile($file);
                    Log::info("CSV leído: " . count($emails) . " emails encontrados");
                } elseif ($extension === 'xlsx' || $extension === 'xls') {
                    // Intentar leer Excel usando ZipArchive y SimpleXML
                    $emails = $this->readExcelFileSimple($file);
                    Log::info("Excel leído: " . count($emails) . " emails encontrados");
                } else {
                    return $this->handleResponse($request, false, 'Formato de archivo no soportado. Use CSV, TXT, XLSX o XLS.', 400);
                }
            } catch (\Exception $e) {
                Log::error('Error leyendo archivo: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return $this->handleResponse($request, false, $e->getMessage(), 400);
            }

            if (empty($emails)) {
                return $this->handleResponse($request, false, 'No se encontraron correos electrónicos válidos en el archivo.', 400);
            }

            // Limitar a 1000 correos
            if (count($emails) > 1000) {
                $emails = array_slice($emails, 0, 1000);
                Log::warning('Se procesaron solo los primeros 1000 correos del archivo.');
            }

            // Eliminar duplicados
            $emails = array_unique($emails);
            $totalEmails = count($emails);

            Log::info("Procesando {$totalEmails} correos desde archivo");

            // Procesar cada email
            $sentCount = 0;
            $errors = [];
            $successEmails = [];
            $skippedEmails = [];

            foreach ($emails as $email) {
                try {
                    // Normalizar email antes de procesar
                    $emailNormalized = strtolower(trim($email));
                    
                    Log::info("=== Procesando email: {$emailNormalized} (original: {$email}) ===");
                    
                    // Verificar que el email no esté vacío después de normalizar
                    if (empty($emailNormalized)) {
                        $errors[] = "Celda vacía o sin contenido válido detectada en el archivo";
                        $skippedEmails[] = $email;
                        Log::warning("✗ Email vacío o inválido: '{$email}'");
                        continue;
                    }
                    
                    $result = $this->processSingleInvitation($emailNormalized);
                    
                    Log::info("Resultado para {$emailNormalized}:", $result);
                    
                    if ($result['success']) {
                        $sentCount++;
                        $successEmails[] = $emailNormalized;
                        Log::info("✓ Invitación enviada correctamente a {$emailNormalized}");
                    } else {
                        // El mensaje ya incluye el email al inicio, no duplicar
                        $errors[] = $result['message'];
                        $skippedEmails[] = $emailNormalized;
                        Log::warning("✗ Error al procesar {$emailNormalized}: {$result['message']}");
                    }
                } catch (\Exception $e) {
                    Log::error("Excepción procesando invitación para {$email}: " . $e->getMessage());
                    Log::error("Stack trace: " . $e->getTraceAsString());
                    
                    // Determinar el tipo de error y dar un mensaje claro
                    $errorMessage = "{$email} - Error al procesar: ";
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $errorMessage .= "Ya existe un código de invitación para este correo";
                    } elseif (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                        $errorMessage .= "Error en la base de datos al guardar la invitación";
                    } elseif (strpos($e->getMessage(), 'Connection') !== false) {
                        $errorMessage .= "Error de conexión con el servidor de correo";
                    } else {
                        $errorMessage .= "No se pudo procesar la solicitud";
                    }
                    
                    $errors[] = $errorMessage;
                    $skippedEmails[] = $email;
                }
            }
            
            Log::info("=== RESUMEN DE PROCESAMIENTO ===");
            Log::info("Total procesados: {$totalEmails}");
            Log::info("Enviados exitosamente: {$sentCount}");
            Log::info("Omitidos: " . count($skippedEmails));
            Log::info("Errores encontrados: " . count($errors));

            // Preparar respuesta
            $message = "Procesamiento completado. Se enviaron {$sentCount} invitación(es) de {$totalEmails} correo(s) procesado(s).";
            if ($sentCount < $totalEmails) {
                $skippedCount = $totalEmails - $sentCount;
                $message .= " {$skippedCount} correo(s) no pudieron ser procesados.";
            }

            return $this->handleResponse($request, true, $message, 200, [
                'sent_count' => $sentCount,
                'total_count' => $totalEmails,
                'skipped_count' => count($skippedEmails),
                'success_emails' => $successEmails,
                'skipped_emails' => $skippedEmails,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Error en AdminController::sendBulkInvitations: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return $this->handleResponse($request, false, 'Error interno del servidor al procesar el archivo: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Leer archivo CSV usando funciones nativas de PHP
     */
    private function readCsvFile($file): array
    {
        $emails = [];
        $emailColumnIndex = null;
        $firstRow = true;
        
        // Abrir archivo
        $handle = fopen($file->getPathname(), 'r');
        
        if ($handle === false) {
            throw new \Exception('No se pudo abrir el archivo CSV.');
        }

        // Leer línea por línea
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($firstRow) {
                // Primera fila: buscar columna de email
                foreach ($row as $index => $header) {
                    $headerLower = strtolower(trim($header));
                    if (in_array($headerLower, ['email', 'correo', 'e-mail', 'correo electrónico', 'correoelectronico', 'mail'])) {
                        $emailColumnIndex = $index;
                        break;
                    }
                }
                
                if ($emailColumnIndex === null) {
                    // Si no hay encabezado, asumir que la primera columna es el email
                    $emailColumnIndex = 0;
                }
                
                $firstRow = false;
                continue;
            }

            // Leer email de la columna correspondiente
            if (isset($row[$emailColumnIndex])) {
                $email = trim($row[$emailColumnIndex]);
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = strtolower($email);
                }
            }
        }

        fclose($handle);
        return $emails;
    }

    /**
     * Leer archivo Excel usando ZipArchive y SimpleXML (nativos de PHP)
     */
    private function readExcelFileSimple($file): array
    {
        $emails = [];
        $extension = strtolower($file->getClientOriginalExtension());
        $filePath = $file->getPathname();
        
        if ($extension === 'xlsx') {
            // Archivo XLSX (Office 2007+) - es un archivo ZIP
            if (!class_exists('ZipArchive')) {
                throw new \Exception('La extensión ZipArchive no está disponible en su servidor. Por favor, exporte el archivo como CSV desde Excel.');
            }
            
            $zip = new \ZipArchive();
            if ($zip->open($filePath) !== true) {
                throw new \Exception('No se pudo abrir el archivo Excel. Verifique que el archivo no esté corrupto.');
            }
            
            try {
                // Leer sharedStrings.xml (contiene los valores de texto compartidos)
                $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
                $sharedStrings = [];
                
                if ($sharedStringsXml) {
                    // Eliminar declaración de namespace para evitar problemas
                    $sharedStringsXml = str_replace('xmlns=', 'ns=', $sharedStringsXml);
                    $xml = @simplexml_load_string($sharedStringsXml);
                    if ($xml) {
                        // Acceso directo sin namespace
                        foreach ($xml->si as $stringItem) {
                            $text = '';
                            if (isset($stringItem->t)) {
                                $text = (string)$stringItem->t;
                            } elseif (isset($stringItem->r)) {
                                // Rich text - concatenar todos los nodos t
                                foreach ($stringItem->r as $run) {
                                    if (isset($run->t)) {
                                        $text .= (string)$run->t;
                                    }
                                }
                            }
                            $sharedStrings[] = $text;
                        }
                    }
                }
                
                // Leer workbook.xml para encontrar la primera hoja
                $workbookXml = $zip->getFromName('xl/workbook.xml');
                $sheetRId = null;
                
                if ($workbookXml) {
                    $workbook = @simplexml_load_string($workbookXml);
                    if ($workbook && isset($workbook->sheets->sheet[0])) {
                        // Obtener el rId de la primera hoja
                        $sheetRId = (string)$workbook->sheets->sheet[0]['r:id'];
                    }
                }
                
                // Buscar relaciones para encontrar el nombre real del archivo de la hoja
                $sheetPath = 'xl/worksheets/sheet1.xml'; // Por defecto
                
                if ($sheetRId) {
                    $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
                    if ($relsXml) {
                        $rels = @simplexml_load_string($relsXml);
                        if ($rels) {
                            foreach ($rels->Relationship as $rel) {
                                if ((string)$rel['Id'] === $sheetRId) {
                                    $target = (string)$rel['Target'];
                                    if (strpos($target, 'worksheets/') === 0) {
                                        $sheetPath = $target;
                                    } elseif (strpos($target, '/worksheets/') !== false) {
                                        $sheetPath = 'xl/' . basename(dirname($target)) . '/' . basename($target);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
                
                // Leer la hoja de cálculo
                $sheetXml = $zip->getFromName($sheetPath);
                if (!$sheetXml) {
                    // Intentar con nombres alternativos comunes
                    $alternatives = ['xl/worksheets/sheet1.xml', 'xl/worksheets/sheet.xml', 'sheet1.xml'];
                    foreach ($alternatives as $alt) {
                        $sheetXml = $zip->getFromName($alt);
                        if ($sheetXml) break;
                    }
                }
                
                if (!$sheetXml) {
                    throw new \Exception('No se pudo encontrar la hoja de cálculo en el archivo Excel.');
                }
                
                if ($sheetXml) {
                    // Eliminar declaración de namespace para evitar problemas
                    $sheetXml = str_replace('xmlns=', 'ns=', $sheetXml);
                    $sheet = @simplexml_load_string($sheetXml);
                    if ($sheet) {
                        // Acceso directo sin namespace
                        $rows = [];
                        if (isset($sheet->sheetData->row)) {
                            $rows = $sheet->sheetData->row;
                        }
                        
                        $emailColumnIndex = null;
                        $firstRow = true;
                        
                        foreach ($rows as $row) {
                            $rowData = [];
                            
                            // Acceso directo a celdas sin namespace
                            $cells = [];
                            if (isset($row->c)) {
                                $cells = $row->c;
                            }
                            
                            foreach ($cells as $cell) {
                                $cellValue = '';
                                // Obtener atributos de la celda
                                $attributes = $cell->attributes();
                                $cellRef = isset($attributes['r']) ? (string)$attributes['r'] : '';
                                $cellType = isset($attributes['t']) ? (string)$attributes['t'] : '';
                                
                                // Determinar índice de columna (A=0, B=1, etc.)
                                $col = 0;
                                if (!empty($cellRef)) {
                                    preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $matches);
                                    if (isset($matches[1])) {
                                        $col = $this->columnToIndex($matches[1]);
                                    }
                                }
                                
                                // Si no se pudo determinar la columna, usar el orden de aparición
                                if ($col === 0 && empty($cellRef)) {
                                    $col = count($rowData);
                                }
                                
                                // Obtener valor de la celda
                                $cellValue = '';
                                $rawValue = null;
                                
                                // Leer el valor (puede estar en <v> o como inline string)
                                if (isset($cell->v)) {
                                    $rawValue = (string)$cell->v;
                                    
                                    if ($cellType === 's') {
                                        // Es un string compartido - buscar en sharedStrings
                                        $stringIndex = (int)$rawValue;
                                        if (isset($sharedStrings[$stringIndex]) && !empty($sharedStrings[$stringIndex])) {
                                            $cellValue = $sharedStrings[$stringIndex];
                                        }
                                    } else {
                                        // Valor directo (número, fecha, booleano)
                                        $cellValue = $rawValue;
                                    }
                                }
                                
                                // Si no hay valor o es un string compartido vacío, intentar inline string
                                if (empty($cellValue)) {
                                    // Buscar inline string (sin namespace)
                                    if (isset($cell->is->t)) {
                                        $cellValue = (string)$cell->is->t;
                                    }
                                }
                                
                                // Guardar el valor si no está vacío
                                if (!empty($cellValue)) {
                                    $rowData[$col] = trim($cellValue);
                                }
                            }
                            
                            if ($firstRow) {
                                // Primera fila: buscar columna de email
                                foreach ($rowData as $col => $header) {
                                    $headerLower = strtolower($header);
                                    if (in_array($headerLower, ['email', 'correo', 'e-mail', 'correo electrónico', 'correoelectronico', 'mail'])) {
                                        $emailColumnIndex = $col;
                                        break;
                                    }
                                }
                                
                                if ($emailColumnIndex === null) {
                                    // Si no hay encabezado, asumir primera columna
                                    $emailColumnIndex = 0;
                                }
                                
                                $firstRow = false;
                                continue;
                            }
                            
                            // Extraer email de la columna correspondiente
                            if (isset($rowData[$emailColumnIndex])) {
                                $email = $rowData[$emailColumnIndex];
                                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $emails[] = strtolower($email);
                                }
                            }
                        }
                    }
                }
                
                $zip->close();
                
            } catch (\Exception $e) {
                $zip->close();
                throw new \Exception('Error leyendo archivo Excel: ' . $e->getMessage() . '. Por favor, exporte el archivo como CSV desde Excel.');
            }
            
        } elseif ($extension === 'xls') {
            // Archivo XLS (formato binario antiguo) - más complejo, mejor sugerir CSV
            throw new \Exception('Los archivos .xls (Excel 97-2003) no son soportados directamente. Por favor, abra el archivo en Excel y guárdelo como .xlsx o .csv.');
        }
        
        return $emails;
    }
    
    /**
     * Convertir letra de columna a índice (A=0, B=1, Z=25, AA=26, etc.)
     */
    private function columnToIndex(string $column): int
    {
        $column = strtoupper($column);
        $index = 0;
        $length = strlen($column);
        
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($column[$i]) - ord('A') + 1);
        }
        
        return $index - 1;
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

    /**
     * Descargar plantilla de ejemplo para carga masiva
     */
    public function downloadTemplate()
    {
        // Crear contenido CSV con ejemplos
        $csvContent = [
            ['Email'], // Encabezado
            ['usuario1@ejemplo.com'],
            ['usuario2@ejemplo.com'],
            ['usuario3@ejemplo.com'],
            ['persona@dominio.com'],
            ['contacto@empresa.com']
        ];
        
        // Convertir a formato CSV
        $output = fopen('php://temp', 'r+');
        
        // Agregar BOM para UTF-8 (Excel lo reconoce mejor)
        fwrite($output, "\xEF\xBB\xBF");
        
        foreach ($csvContent as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csvData = stream_get_contents($output);
        fclose($output);
        
        // Descargar archivo
        $filename = 'plantilla-invitaciones-masivas.csv';
        
        return response($csvData, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'public');
    }

    /**
     * Detectar errores comunes de escritura en emails
     */
    private function detectarErrorEscritura(string $email): ?string
    {
        // Gmail mal escrito
        if (preg_match('/@gmmail\.|@gmai\.|@gmial\.|@gmail\.comm|@gmail\.c0m|@gmail\.con/i', $email)) {
            return "Dominio mal escrito - ¿Quisiste decir 'gmail.com'?";
        }
        
        // Yahoo mal escrito
        if (preg_match('/@yahhoo\.|@yahooo\.|@yahoo\.comm|@yahoo\.c0m|@yahoo\.con/i', $email)) {
            return "Dominio mal escrito - ¿Quisiste decir 'yahoo.com'?";
        }
        
        // Hotmail mal escrito
        if (preg_match('/@hotmial\.|@hotmaill\.|@hotmail\.comm|@hotmail\.c0m|@hotmail\.con/i', $email)) {
            return "Dominio mal escrito - ¿Quisiste decir 'hotmail.com'?";
        }
        
        // Outlook mal escrito
        if (preg_match('/@outlok\.|@outloo\.|@outlook\.comm|@outlook\.c0m|@outlook\.con/i', $email)) {
            return "Dominio mal escrito - ¿Quisiste decir 'outlook.com'?";
        }
        
        // Puntos extras en el dominio
        if (preg_match('/@.*\.com\.|@.*\.co\.|@.*\.net\./i', $email)) {
            return "Punto extra en el dominio";
        }
        
        // Comas en lugar de puntos
        if (preg_match('/@.*\.com,|@.*\.co,|@.*,com|@.*,co/i', $email)) {
            return "Coma en lugar de punto en el dominio";
        }
        
        // Espacios en el email
        if (strpos($email, ' ') !== false) {
            return "El correo contiene espacios no permitidos";
        }
        
        // Puntos dobles
        if (preg_match('/\.{2,}/', $email)) {
            return "El correo contiene puntos consecutivos";
        }
        
        // @ múltiples
        if (substr_count($email, '@') > 1) {
            return "El correo contiene más de un símbolo @";
        }
        
        // @ faltante
        if (substr_count($email, '@') === 0) {
            return "El correo no contiene el símbolo @";
        }
        
        // Sin dominio después del @
        if (preg_match('/@\s*$/', $email) || !preg_match('/@.+\..+/', $email)) {
            return "El correo no tiene un dominio válido después del @";
        }
        
        // Dominios comunes sin TLD
        if (preg_match('/@(gmail|yahoo|hotmail|outlook)$/i', $email)) {
            return "Falta la extensión del dominio (.com, .es, etc.)";
        }
        
        return null; // No hay errores detectados
    }
}
