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
        <link href="https://fonts.googleapis.com/css2?family=Arsenal:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts - CSS unificado -->
        <link rel="stylesheet" href="{{ asset('css/all-styles.css') }}">
        
        <!-- CSS de respaldo directo -->
        <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
        
        <!-- CSS de Layouts -->
        <link rel="stylesheet" href="{{ asset('css/views/layouts/layouts.css') }}">
        
        <!-- Tailwind CSS CDN como respaldo adicional -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Alpine.js x-cloak CSS -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased" style="margin: 0; padding: 0;">
        <div class="min-h-screen" style="margin: 0; padding: 0;">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="shadow">
                    {{ $header }}
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Scripts del Layout Principal -->
        <script src="{{ asset('js/views/layouts/app.js') }}"></script>
        
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

        <!-- Pasar errores de validación a JavaScript -->
        @if($errors->updatePassword->any())
            <script>
                window.passwordErrors = [
                    @foreach($errors->updatePassword->all() as $error)
                        '{{ $error }}',
                    @endforeach
                ];
            </script>
        @endif
    </body>
</html>
