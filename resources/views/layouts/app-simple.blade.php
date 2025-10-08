<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title') @yield('title') @else Diócesis de Apartadó - Sistema de Cursos @endif</title>

        <!-- Favicon personalizado -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts - Usando asset() directamente -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-CAjc0yiz.css') }}">
        
        <!-- CSS de Layouts -->
        <link rel="stylesheet" href="{{ asset('css/views/layouts/layouts.css') }}">
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Alpine.js x-cloak CSS -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Scripts del Layout Simple -->
        <script src="{{ asset('js/views/layouts/app-simple.js') }}"></script>

        <!-- Pasar datos de sesión a JavaScript -->
        @if(session('error'))
            <script>
                window.sessionError = '{{ session('error') }}';
            </script>
        @endif

        @if(session('success'))
            <script>
                window.sessionSuccess = '{{ session('success') }}';
            </script>
        @endif
    </body>
</html>






