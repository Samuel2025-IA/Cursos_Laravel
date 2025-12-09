<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') @else Diócesis de Apartadó @endif</title>

    <!-- Favicon personalizado -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Arsenal:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts - CSS unificado -->
    <link rel="stylesheet" href="{{ asset('css/all-styles.css') }}">
    
    <!-- CSS de respaldo directo -->
    <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
    
    <!-- CSS específico del Dashboard -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS CDN como respaldo adicional -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js x-cloak CSS -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Header/Navbar - Nivel más alto -->
    @include('layouts.partials.header')

    <div class="flex min-h-screen" style="margin-top: 0;">
        <!-- Sidebar Fijo -->
        @include('layouts.partials.sidebar')

        <!-- Overlay para móvil -->
        <div id="sidebarOverlay" class="sidebar-overlay" style="display: none;"></div>

        <!-- Contenido Principal -->
        <div class="flex-1 flex flex-col main-content-with-sidebar" style="margin-left: 16rem; margin-top: 4rem; transition: margin-left 0.2s ease-in-out;">
            <!-- Contenido de la página -->
            <main class="flex-1 {{ request()->routeIs('profile.edit') ? 'px-3 sm:px-4 md:px-6 pt-0 pb-6' : 'px-3 sm:px-4 md:px-6 -mt-10 pb-6' }}">
                <!-- Mensajes Flash (se muestran con SweetAlert2 en lugar de HTML) -->

                <!-- Contenido específico de la vista -->
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        © {{ date('Y') }} Diócesis de Apartadó - Sistema de Cursos
                    </div>
                    <div class="text-sm text-gray-500">
                        Versión 1.0.0
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts del Layout Dashboard -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/dashboard-hamburger-menu.js') }}"></script>
    
    
    <!-- Scripts de Transiciones de Navegación -->
    <script src="{{ asset('build/assets/navigation-transitions-B76zogpA.js') }}"></script>

    <!-- Script para manejar alertas flash con SweetAlert2 -->
    @if(session('success'))
        <script>
            window.sessionSuccess = {!! json_encode(session('success')) !!};
        </script>
    @endif

    @if(session('error'))
        <script>
            window.sessionError = {!! json_encode(session('error')) !!};
        </script>
    @endif

    @if(session('warning'))
        <script>
            window.sessionWarning = {!! json_encode(session('warning')) !!};
        </script>
    @endif

    @if(session('info'))
        <script>
            window.sessionInfo = {!! json_encode(session('info')) !!};
        </script>
    @endif

    <!-- Script para mostrar alertas flash con SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar alerta de éxito
            if (window.sessionSuccess) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: window.sessionSuccess,
                    confirmButtonColor: '#2f9f37',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#2f9f37',
                    timer: 4000,
                    timerProgressBar: true
                });
                window.sessionSuccess = null;
            }

            // Mostrar alerta de error
            if (window.sessionError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: window.sessionError,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Entiendo',
                    background: '#ffffff',
                    iconColor: '#dc2626'
                });
                window.sessionError = null;
            }

            // Mostrar alerta de advertencia
            if (window.sessionWarning) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: window.sessionWarning,
                    confirmButtonColor: '#d97706',
                    confirmButtonText: 'Entiendo',
                    background: '#ffffff',
                    iconColor: '#d97706'
                });
                window.sessionWarning = null;
            }

            // Mostrar alerta de información
            if (window.sessionInfo) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: window.sessionInfo,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#2563eb'
                });
                window.sessionInfo = null;
            }
        });
    </script>

    <!-- Script para alertas de bienvenida y despedida -->
    @vite('resources/js/views/global/welcome-goodbye-alerts.js')
    
    <!-- Meta tags para alertas de bienvenida/despedida (moved to end for better timing) -->
    @if(session('welcome_message'))
        <meta name="welcome-message" content="{{ session('welcome_message') }}" id="welcome-message-meta">
        <script>console.log('🔍 Meta tag welcome-message creado:', '{{ session('welcome_message') }}'});</script>
    @endif

    @if(session('admin_welcome_message') || isset($admin_welcome_message))
        <meta name="admin-welcome-message" content="{{ session('admin_welcome_message') ?? $admin_welcome_message }}" id="admin-welcome-message-meta">
        <script>console.log('🔍 Meta tag admin-welcome-message creado:', '{{ session('admin_welcome_message') ?? $admin_welcome_message }}');</script>
    @endif

    @if(session('goodbye'))
        <meta name="goodbye-message" content="{{ session('goodbye') }}" id="goodbye-message-meta">
    @endif

    @stack('scripts')
</body>
</html>
