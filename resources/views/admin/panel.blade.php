<x-app-layout>
    @section('title', 'Panel de Administración - Diócesis de Apartadó')
    <x-slot name="header">
    </x-slot>

    <!-- Meta tags para el sistema de alertas flash -->
    @if(session('flash_message') && session('flash_token'))
        <meta name="flash-message" content="{{ session('flash_message') }}">
        <meta name="flash-token" content="{{ session('flash_token') }}">
    @endif

    <!-- Meta tag para mensaje de bienvenida -->
    @if(session('welcome_message'))
        <meta name="welcome-message" content="{{ session('welcome_message') }}">
    @endif

    <div class="py-6 admin-container">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Contenedor Principal del Panel - Ancho Completo -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden admin-card fade-in">
                <div class="admin-header px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-white">{{ __('Panel de Administración') }}</h1>
                            <p class="text-white/90 mt-1">{{ __('Gestiona usuarios y códigos de invitación del sistema') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
    <!-- Loading Overlay Estándar -->
    <x-loading-overlay id="invitation-loading" text="Enviando invitaciones..." />
            
                    <!-- Gestión de Usuarios -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden admin-card fade-in">
                        <div class="user-management-header px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                Gestión de Usuarios
                            </h3>
                        </div>
                <div class="p-6">
                    <form id="invitationForm" action="{{ route('admin.send-invitations') }}" method="POST" class="space-y-4" onsubmit="return validarFormulario()">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="email1" class="block text-sm font-medium text-gray-700 mb-1">Correo 1</label>
                                <input type="email" id="email1" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario1@ejemplo.com">
                            </div>
                            <div>
                                <label for="email2" class="block text-sm font-medium text-gray-700 mb-1">Correo 2</label>
                                <input type="email" id="email2" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario2@ejemplo.com">
                            </div>
                            <div>
                                <label for="email3" class="block text-sm font-medium text-gray-700 mb-1">Correo 3</label>
                                <input type="email" id="email3" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario3@ejemplo.com">
                            </div>
                            <div>
                                <label for="email4" class="block text-sm font-medium text-gray-700 mb-1">Correo 4</label>
                                <input type="email" id="email4" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario4@ejemplo.com">
                            </div>
                            <div>
                                <label for="email5" class="block text-sm font-medium text-gray-700 mb-1">Correo 5</label>
                                <input type="email" id="email5" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario5@ejemplo.com">
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 gap-4">
                            <p class="text-sm text-gray-500">
                                Los códigos de invitación expiran en 7 días
                            </p>
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-[#2f9f37] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2f9f37]/90 focus:bg-[#2f9f37]/90 active:bg-[#2f9f37]/80 focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Enviar Invitaciones
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Códigos Activos -->
            <div class="mt-6">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden admin-card fade-in">
                    <div class="active-codes-header px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Códigos de Invitación Activos
                        </h3>
                    </div>
                <div class="p-6">
                    <!-- Buscador de códigos activos -->
                    <div class="mb-4">
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="activeSearchInput"
                                   placeholder="Buscar por email o código..." 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                            <button type="button" 
                                    id="clearActiveSearch"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition ease-in-out duration-150"
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
                    
                    <!-- Paginación (solo visible cuando no hay búsqueda activa) -->
                    <div id="activeCodesPagination" class="mt-6">
                        {{ $activeCodes->links() }}
                    </div>
                </div>
            </div>

            <!-- Historial de Códigos Eliminados -->
            <div class="mt-6">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden admin-card fade-in">
                    <div class="history-header px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Historial de Códigos Eliminados
                        </h3>
                    </div>
                <div class="p-6">
                    <!-- Buscador de historial -->
                    <div class="mb-4">
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="historySearchInput"
                                   placeholder="Buscar en historial..." 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <button type="button" 
                                    id="clearHistorySearch"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition ease-in-out duration-150"
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
</script>
@vite('resources/js/views/admin/panel.js')

    <!-- Script para manejar alertas flash y mensaje de bienvenida en admin -->
    @vite(['resources/js/views/admin/admin-welcome.js'])
    
    <!-- Alertas de sesión -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarAlerta('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarAlerta('{{ session('error') }}', 'error');
            });
        </script>
    @endif
</x-app-layout>
