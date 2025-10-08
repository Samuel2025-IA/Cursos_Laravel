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
        @vite(['resources/css/app.css', 'resources/css/utilities/forms.css', 'resources/css/views/auth/register.css', 'resources/css/views/auth/login.css', 'resources/css/views/auth/verify-invitation.css', 'resources/css/views/auth/invitation-alert.css', 'resources/css/views/auth/forgot-password.css'])
        
        <!-- CSS de Layouts -->
        <link rel="stylesheet" href="{{ asset('css/views/layouts/layouts.css') }}">
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased @if(request()->routeIs('register')) register-page @endif" data-route="{{ request()->route()->getName() }}">
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-4 sm:pt-0 pb-4 sm:pb-0 bg-gray-100 @if(request()->routeIs('register')) register-container @endif">
            <div class="mb-6 pt-6">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-28 h-28 drop-shadow-lg" />
                    <span class="mt-3 text-xl font-semibold text-gray-800">Diócesis de Apartadó</span>
                    <span class="text-base text-gray-600">Plataforma de Cursos</span>
                </a>
            </div>

            <div class="w-full sm:max-w-lg mt-2 mb-2 px-6 py-6 bg-white shadow-lg overflow-hidden sm:rounded-lg @if(request()->routeIs('login')) login-container @endif @if(request()->routeIs('invitation.verify')) invitation-container @endif @if(request()->routeIs('password.request') || request()->routeIs('password.reset')) forgot-password-container @endif">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </div>

        <!-- Scripts del Layout de Invitados -->
        <script src="{{ asset('js/views/layouts/guest.js') }}"></script>


        <!-- Pasar datos de sesión a JavaScript -->
        @if(session('error'))
            <script>
                window.sessionError = '{{ session('error') }}';
            </script>
        @elseif(session('info'))
            <script>
                window.sessionInfo = '{{ session('info') }}';
            </script>
        @elseif(session('success'))
            <script>
                window.sessionSuccess = '{{ session('success') }}';
            </script>
        @elseif(session('status'))
            <script>
                window.sessionStatus = '{{ session('status') }}';
            </script>
        @endif
    </body>
</html>
                                                                    