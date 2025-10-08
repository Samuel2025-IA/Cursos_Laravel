<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') @else Dashboard - Diócesis de Apartadó @endif</title>

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
            <main class="flex-1 p-6">
                <!-- Mensajes Flash -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('warning') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @endif

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

    <!-- Pasar datos de sesión a JavaScript -->
    @if(session('error'))
        <script>
            window.sessionError = '{{ session('error') }}';
        </script>
    @endif

    @if(session('warning'))
        <script>
            window.sessionWarning = '{{ session('warning') }}';
        </script>
    @endif

    @if(session('info'))
        <script>
            window.sessionInfo = '{{ session('info') }}';
        </script>
    @endif

    @if(session('success'))
        <script>
            window.sessionSuccess = '{{ session('success') }}';
        </script>
    @endif

    @stack('scripts')
</body>
</html>
