@extends('layouts.dashboard')

@section('title', 'Panel de Administración')

{{-- 
    Comentario: Panel de administración con diseño responsivo
    TODO: Considerar mover la lógica a un controlador dedicado
--}}
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
    
    // Preparar datos para las estadísticas del admin
    $stats = [
        'total_usuarios' => \App\Models\User::count(),
        'usuarios_admin' => \App\Models\User::where('rol', 'admin')->count(),
        'usuarios_normales' => \App\Models\User::where('rol', 'user')->count(),
        'codigos_activos' => \App\Models\InvitationCode::where('used', false)->where('expires_at', '>', now())->count()
    ];
    
    // Obtener códigos de invitación activos (mostrar todos)
    $activeCodes = \App\Models\InvitationCode::where('used', false)
        ->where('expires_at', '>', now())
        ->latest()
        ->get();
    
    // Obtener historial de códigos eliminados (usados o expirados)
    $historyCodes = \App\Models\InvitationCode::where(function($q) {
            $q->where('used', true)
              ->orWhere('expires_at', '<=', now());
        })
        ->latest()
        ->paginate(5);
@endphp

@section('content')
<div class="space-y-6">
    <!-- Mensaje de bienvenida para admin -->
    @if(session('admin_welcome_message'))
        <div class="bg-gradient-to-r from-[#2f9f37] to-[#27842f] text-white p-4 sm:p-6 rounded-xl shadow-xl border-l-4 border-white/30 animate-fade-in">
            <div class="flex items-start space-x-3 sm:space-x-4">
                <div class="flex-shrink-0 bg-white/20 rounded-full p-2 sm:p-3">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg sm:text-xl font-bold mb-1">¡Panel de Administración!</h3>
                    <p class="text-white/90 text-sm sm:text-base">{{ session('admin_welcome_message') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" 
                        class="flex-shrink-0 text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Panel de Administración</h1>
        <p class="text-gray-600">Gestiona usuarios y códigos de invitación del sistema</p>
    </div>

    <!-- Loading Overlay Estándar -->
    <x-loading-overlay id="invitation-loading" text="Enviando invitaciones..." />
    
    <!-- Gestión de Usuarios -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-5 md:p-6">
            <div class="mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    Gestión de Usuarios
                </h2>
            </div>
            <!-- Tabs de navegación -->
            <div class="border-b border-gray-200 mb-4 sm:mb-6 overflow-x-auto">
                <nav class="flex space-x-4 sm:space-x-6 md:space-x-8" aria-label="Tabs">
                    <button type="button" id="tab-individual" class="tab-button active border-b-2 border-[#2f9f37] py-2.5 sm:py-3 md:py-4 px-2 text-sm font-medium text-[#2f9f37] whitespace-nowrap">
                        Inserción Individual
                    </button>
                    <button type="button" id="tab-masiva" class="tab-button border-b-2 border-transparent py-2.5 sm:py-3 md:py-4 px-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                        Inserción Masiva
                    </button>
                </nav>
            </div>

            <!-- Contenido de Inserción Individual -->
            <div id="content-individual" class="tab-content">
                <form id="invitationForm" action="{{ route('admin.send-invitations') }}" method="POST" class="space-y-4 sm:space-y-5 md:space-y-6" onsubmit="return validarFormulario()">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-4">
                        <div class="space-y-1.5">
                            <label for="email1" class="block text-sm font-medium text-gray-700 mb-1.5">Correo 1</label>
                            <input type="email" id="email1" name="emails[]" class="email-input w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-colors" placeholder="usuario1@ejemplo.com">
                        </div>
                        <div class="space-y-1.5">
                            <label for="email2" class="block text-sm font-medium text-gray-700 mb-1.5">Correo 2</label>
                            <input type="email" id="email2" name="emails[]" class="email-input w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-colors" placeholder="usuario2@ejemplo.com">
                        </div>
                        <div class="space-y-1.5">
                            <label for="email3" class="block text-sm font-medium text-gray-700 mb-1.5">Correo 3</label>
                            <input type="email" id="email3" name="emails[]" class="email-input w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-colors" placeholder="usuario3@ejemplo.com">
                        </div>
                        <div class="space-y-1.5">
                            <label for="email4" class="block text-sm font-medium text-gray-700 mb-1.5">Correo 4</label>
                            <input type="email" id="email4" name="emails[]" class="email-input w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-colors" placeholder="usuario4@ejemplo.com">
                        </div>
                        <div class="space-y-1.5 sm:col-span-2 lg:col-span-1">
                            <label for="email5" class="block text-sm font-medium text-gray-700 mb-1.5">Correo 5</label>
                            <input type="email" id="email5" name="emails[]" class="email-input w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-colors" placeholder="usuario5@ejemplo.com">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 gap-3 sm:gap-4">
                        <button type="submit" id="submitBtn" class="w-full sm:w-auto sm:max-w-xs lg:max-w-sm inline-flex items-center justify-center px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-[#2f9f37] to-[#27842f] border border-transparent rounded-md font-medium text-sm text-white uppercase tracking-wide hover:from-[#27842f] hover:to-[#2f9f37] focus:from-[#27842f] focus:to-[#2f9f37] active:from-[#1e6b24] active:to-[#27842f] focus:outline-none focus:ring-2 focus:ring-[#2f9f37]/30 focus:ring-offset-2 transition-all ease-in-out duration-200 shadow-md hover:shadow-lg transform hover:scale-102 active:scale-98 sticky-button">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold text-sm">Enviar Invitaciones</span>
                        </button>
                        <p class="text-xs sm:text-sm text-gray-500 text-center sm:text-right">
                            Los códigos de invitación expiran en 7 días
                        </p>
                    </div>
                </form>
            </div>

            <!-- Contenido de Inserción Masiva -->
            <div id="content-masiva" class="tab-content hidden">
                <form id="bulkInvitationForm" action="{{ route('admin.send-bulk-invitations') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2 sm:mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs sm:text-sm font-medium text-blue-900 mb-2">Formato del archivo</h4>
                                <ul class="text-xs sm:text-sm text-blue-800 space-y-1 list-disc list-inside">
                                    <li>El archivo puede ser formato <strong>.csv</strong> (recomendado), .txt, .xlsx o .xls</li>
                                    <li>La primera fila debe contener el encabezado: <strong>"Email"</strong> o <strong>"Correo"</strong></li>
                                    <li>Las siguientes filas deben contener los correos electrónicos (uno por fila)</li>
                                    <li>Máximo 1000 correos por archivo</li>
                                    <li><strong>Nota:</strong> Si tiene Excel, exporte como CSV (Archivo > Guardar como > CSV)</li>
                                </ul>
                                <div class="mt-4">
                                    <a href="{{ route('admin.download-template') }}" id="download-template" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Descargar plantilla de ejemplo (CSV)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccionar archivo (CSV, TXT, Excel)
                        </label>
                        <div id="file-upload-area" class="mt-1 flex justify-center px-3 sm:px-4 md:px-6 pt-4 sm:pt-5 pb-4 sm:pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#2f9f37] transition-colors">
                            <div class="space-y-1 text-center w-full min-w-0">
                                <div id="file-upload-icon" class="flex justify-center">
                                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div id="file-upload-text" class="flex flex-wrap text-xs sm:text-sm text-gray-600 justify-center gap-1">
                                    <label for="excel_file" class="relative cursor-pointer bg-white rounded-md font-medium text-[#2f9f37] hover:text-[#27842f] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#2f9f37]">
                                        <span>Seleccionar archivo</span>
                                        <input id="excel_file" name="excel_file" type="file" accept=".csv,.txt,.xlsx,.xls" class="sr-only" required>
                                    </label>
                                    <p class="whitespace-nowrap">o arrastra y suelta</p>
                                </div>
                                <p id="file-upload-hint" class="text-xs text-gray-500">CSV, TXT, XLSX, XLS hasta 10MB</p>
                                
                                <!-- Información del archivo seleccionado -->
                                <div id="file-selected-info" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-green-900" id="file-name">Archivo seleccionado</p>
                                                <p class="text-xs text-green-700" id="file-size"></p>
                                            </div>
                                        </div>
                                        <button type="button" id="remove-file-btn" class="text-red-600 hover:text-red-800 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('excel_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-4 gap-4">
                        <button type="submit" id="submitBulkBtn" class="w-full md:w-auto md:max-w-xs inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-[#2f9f37] to-[#27842f] border border-transparent rounded-md font-medium text-sm text-white uppercase tracking-wide hover:from-[#27842f] hover:to-[#2f9f37] focus:from-[#27842f] focus:to-[#2f9f37] active:from-[#1e6b24] active:to-[#27842f] focus:outline-none focus:ring-2 focus:ring-[#2f9f37]/30 focus:ring-offset-2 transition-all ease-in-out duration-200 shadow-md hover:shadow-lg transform hover:scale-102 active:scale-98">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="font-semibold">Procesar Archivo</span>
                        </button>
                        <p class="text-xs md:text-sm text-gray-500 text-center md:text-right">
                            Se procesarán los correos y se enviarán las invitaciones
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Códigos Activos -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-5 md:p-6">
            <div class="mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-[#2D3A73]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Códigos de Invitación Activos
                </h2>
            </div>
            <div>
                    <!-- Buscador de códigos activos -->
                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex-1 relative min-w-0">
                                <input type="text" 
                                       id="activeSearchInput"
                                       placeholder="Buscar por email o código..." 
                                       class="w-full px-3 py-2 pr-10 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                                <button type="button" 
                                        id="activeSearchButton"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-[#2f9f37] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                            <button type="button" 
                                    id="clearActiveSearch"
                                    class="w-full sm:w-auto px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition ease-in-out duration-150"
                                    style="display: none;">
                                Limpiar
                            </button>
                        </div>
                        <div id="activeSearchResults" class="mt-2 text-sm text-gray-600" style="display: none;">
                            <!-- Los resultados aparecerán aquí -->
                        </div>
                    </div>
                    
                    <!-- Contenedor dinámico para códigos activos -->
                    <div id="activeCodesContainer">
                        @include('admin.partials.active-codes-table', ['codes' => $activeCodes])
                    </div>

                    <!-- Paginación para códigos activos -->
                    <div id="activeCodesPagination" class="mt-6">
                        <x-history-pagination :paginator="$activeCodes" />
                    </div>
            </div>
        </div>
    </div>

    <!-- Historial de Códigos Eliminados -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-5 md:p-6">
            <div class="mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Historial de Códigos Eliminados
                </h2>
            </div>
            <div>
                    <!-- Buscador de historial -->
                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex-1 relative min-w-0">
                                <input type="text" 
                                       id="historySearchInput"
                                       placeholder="Buscar en historial..." 
                                       class="w-full px-3 py-2 pr-10 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" 
                                        id="historySearchButton"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                            <button type="button" 
                                    id="clearHistorySearch"
                                    class="w-full sm:w-auto px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition ease-in-out duration-150"
                                    style="display: none;">
                                Limpiar
                            </button>
                        </div>
                        <div id="historySearchResults" class="mt-2 text-sm text-gray-600" style="display: none;">
                            <!-- Los resultados aparecerán aquí -->
                        </div>
                    </div>
                    
                    <!-- Contenedor dinámico para historial -->
                    <div id="historyCodesContainer">
                        @include('admin.partials.history-codes-table', ['codes' => $historyCodes])
                    </div>
                    
            <!-- Paginación del historial (solo visible cuando no hay búsqueda activa) -->
            <div id="historyCodesPagination" class="mt-6">
                <x-history-pagination :paginator="$historyCodes" />
            </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS del Panel de Administración -->
@vite('resources/css/admin/panel.css')

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script del Panel de Administración -->
<script>
    // Pasar rutas al JavaScript
    window.adminRoutes = {
        searchActiveCodes: '{{ route("admin.search-active-codes") }}',
        searchHistoryCodes: '{{ route("admin.search-history-codes") }}'
    };
    
    // Inicializar pestañas directamente aquí para asegurar que funcione
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Inicializando pestañas desde panel.blade.php');
        
        const tabIndividual = document.getElementById('tab-individual');
        const tabMasiva = document.getElementById('tab-masiva');
        const contentIndividual = document.getElementById('content-individual');
        const contentMasiva = document.getElementById('content-masiva');

        console.log('Elementos encontrados:', {
            tabIndividual: !!tabIndividual,
            tabMasiva: !!tabMasiva,
            contentIndividual: !!contentIndividual,
            contentMasiva: !!contentMasiva
        });

        if (tabIndividual && tabMasiva && contentIndividual && contentMasiva) {
            // Cambiar a pestaña individual
            tabIndividual.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Click en pestaña individual');
                
                tabIndividual.classList.add('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
                tabIndividual.classList.remove('border-transparent', 'text-gray-500');
                tabMasiva.classList.remove('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
                tabMasiva.classList.add('border-transparent', 'text-gray-500');
                
                contentIndividual.classList.remove('hidden');
                contentMasiva.classList.add('hidden');
            });

            // Función para mostrar el archivo seleccionado
            function setupFileUpload() {
                const excelFileInput = document.getElementById('excel_file');
                const fileName = document.getElementById('file-name');
                const fileSize = document.getElementById('file-size');
                const fileUploadArea = document.getElementById('file-upload-area');
                const fileUploadIcon = document.getElementById('file-upload-icon');
                const fileUploadText = document.getElementById('file-upload-text');
                const fileUploadHint = document.getElementById('file-upload-hint');
                const fileSelectedInfo = document.getElementById('file-selected-info');
                const removeFileBtn = document.getElementById('remove-file-btn');

                if (!excelFileInput) {
                    console.log('Input de archivo no encontrado aún');
                    return;
                }

                console.log('Configurando input de archivo...');

                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                }

                function showFileInfo(file) {
                    if (!file) return;
                    
                    console.log('Archivo seleccionado:', file.name);
                    
                    // Actualizar texto del nombre y tamaño
                    if (fileName) fileName.textContent = file.name;
                    if (fileSize) fileSize.textContent = formatFileSize(file.size);
                    
                    // Ocultar elementos iniciales
                    if (fileUploadIcon) fileUploadIcon.style.display = 'none';
                    if (fileUploadText) fileUploadText.style.display = 'none';
                    if (fileUploadHint) fileUploadHint.style.display = 'none';
                    
                    // Mostrar información del archivo
                    if (fileSelectedInfo) fileSelectedInfo.classList.remove('hidden');
                    
                    // Cambiar estilo del área
                    if (fileUploadArea) {
                        fileUploadArea.classList.remove('border-gray-300');
                        fileUploadArea.classList.add('border-green-500', 'bg-green-50');
                    }
                }

                // Event listener para cuando se selecciona un archivo
                // Verificar si ya tiene un listener para evitar duplicados
                if (!excelFileInput.hasAttribute('data-listener-attached')) {
                    excelFileInput.setAttribute('data-listener-attached', 'true');
                    excelFileInput.addEventListener('change', function(e) {
                        console.log('Cambio detectado en input de archivo');
                        if (e.target.files && e.target.files.length > 0) {
                            showFileInfo(e.target.files[0]);
                        }
                    });
                }

                // Botón para eliminar archivo
                if (removeFileBtn && !removeFileBtn.hasAttribute('data-listener-attached')) {
                    removeFileBtn.setAttribute('data-listener-attached', 'true');
                    removeFileBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        excelFileInput.value = '';
                        if (fileSelectedInfo) fileSelectedInfo.classList.add('hidden');
                        if (fileUploadIcon) fileUploadIcon.style.display = 'flex';
                        if (fileUploadText) fileUploadText.style.display = 'flex';
                        if (fileUploadHint) fileUploadHint.style.display = 'block';
                        if (fileUploadArea) {
                            fileUploadArea.classList.remove('border-green-500', 'bg-green-50');
                            fileUploadArea.classList.add('border-gray-300');
                        }
                    });
                }
            }

            // Cambiar a pestaña masiva
            tabMasiva.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Click en pestaña masiva');
                
                tabMasiva.classList.add('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
                tabMasiva.classList.remove('border-transparent', 'text-gray-500');
                tabIndividual.classList.remove('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
                tabIndividual.classList.add('border-transparent', 'text-gray-500');
                
                contentMasiva.classList.remove('hidden');
                contentIndividual.classList.add('hidden');
                
                // Configurar la carga de archivos cuando se muestra la pestaña
                setTimeout(setupFileUpload, 200);
            });
            
            console.log('Pestañas configuradas correctamente');
            
            // Ejecutar setupFileUpload inicialmente después de un delay
            setTimeout(setupFileUpload, 500);
        } else {
            console.error('No se encontraron todos los elementos de pestañas');
        }
    });
</script>
@vite('resources/js/views/admin/panel.js')

<!-- (Eliminado) Script específico de bienvenida admin para evitar duplicados con welcome-goodbye-alerts.js -->

<!-- Alertas de sesión deshabilitadas - Las alertas se manejan desde JavaScript/AJAX -->

@endsection

@push('scripts')
<script>
    // Scripts específicos del panel de admin
    document.addEventListener('DOMContentLoaded', function() {
        // El botón sticky permanece siempre visible
        // No hay auto-ocultamiento del mensaje de bienvenida
    });
</script>
@endpush
