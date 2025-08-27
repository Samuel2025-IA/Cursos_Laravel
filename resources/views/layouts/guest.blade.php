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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-4 pt-6">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-24 h-24 drop-shadow-lg" />
                    <span class="mt-2 text-lg font-semibold text-gray-800">Diócesis de Apartadó</span>
                    <span class="text-sm text-gray-600">Plataforma de Cursos</span>
                </a>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </div>

        <!-- SweetAlert2 Helper Functions -->
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            window.showInfo = function(message) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: message,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#2563eb',
                    timer: 6000,
                    timerProgressBar: true
                });
            };
        </script>

        <!-- Mostrar alertas desde sesiones de Laravel -->
        @if(session('info'))
            <script>
                showInfo('{{ session('info') }}');
            </script>
        @endif
    </body>
</html>
