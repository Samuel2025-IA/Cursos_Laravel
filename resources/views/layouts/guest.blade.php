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
        @vite(['resources/css/app.css', 'resources/css/views/auth/register.css', 'resources/css/views/auth/login.css', 'resources/css/views/auth/verify-invitation.css', 'resources/css/views/auth/invitation-alert.css'])
        
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

            <div class="w-full sm:max-w-lg mt-2 mb-2 px-6 py-6 bg-white shadow-lg overflow-hidden sm:rounded-lg @if(request()->routeIs('login')) login-container @endif @if(request()->routeIs('invitation.verify')) invitation-container @endif">
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

            window.showError = function(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#dc2626',
                    timer: 5000,
                    timerProgressBar: true
                });
            };

            window.showSuccess = function(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: message,
                    confirmButtonColor: '#059669',
                    confirmButtonText: '¡Perfecto!',
                    background: '#ffffff',
                    iconColor: '#059669',
                    timer: 4000,
                    timerProgressBar: true
                });
            };

            // Función que faltaba - showErrorAlert
            window.showErrorAlert = function(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: message,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Aceptar',
                        background: '#ffffff',
                        iconColor: '#dc2626',
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    });
                } else {
                    alert('ERROR: ' + message);
                }
            };
        </script>

        <!-- Scripts de alertas -->
        @vite('resources/js/views/layouts/guest-alerts.js')
        
        <!-- Script para aplicar estilos de registro -->
        <script>
            // Aplicar clases CSS para el formulario de registro
            document.addEventListener('DOMContentLoaded', function() {
                if (window.location.pathname === '/register') {
                    document.body.classList.add('register-page');
                    const container = document.querySelector('.min-h-screen');
                    if (container) {
                        container.classList.add('register-container');
                    }
                }
            });
        </script>

        <!-- Mostrar alertas desde sesiones de Laravel - UNA SOLA A LA VEZ -->
        @if(session('error'))
            <script data-session-alert="error">
                // Marcar que hay alerta de sesión
                document.body.setAttribute('data-has-session-message', 'true');
                
                setTimeout(function() {
                    console.log('🔴 Mostrando alerta de ERROR:', '{{ session('error') }}');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ session('error') }}',
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'Aceptar',
                            background: '#ffffff',
                            iconColor: '#dc2626',
                            timer: 5000,
                            timerProgressBar: true
                        });
                    }
                }, 800);
            </script>
        @elseif(session('info'))
            <script data-session-alert="info">
                // Marcar que hay alerta de sesión
                document.body.setAttribute('data-has-session-message', 'true');
                
                setTimeout(function() {
                    console.log('🔵 Mostrando alerta de INFO:', '{{ session('info') }}');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Información Importante',
                            text: '{{ session('info') }}',
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'Aceptar',
                            background: '#ffffff',
                            iconColor: '#2563eb',
                            showCloseButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                }, 800);
            </script>
        @elseif(session('success'))
            <script data-session-alert="success">
                // Marcar que hay alerta de sesión
                document.body.setAttribute('data-has-session-message', 'true');
                
                setTimeout(function() {
                    console.log('🟢 Mostrando alerta de SUCCESS:', '{{ session('success') }}');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: '{{ session('success') }}',
                            confirmButtonColor: '#059669',
                            confirmButtonText: '¡Perfecto!',
                            background: '#ffffff',
                            iconColor: '#059669',
                            timer: 4000,
                            timerProgressBar: true
                        });
                    }
                }, 800);
            </script>
        @elseif(session('status'))
            <script data-session-alert="status">
                // Marcar que hay alerta de sesión
                document.body.setAttribute('data-has-session-message', 'true');
                
                setTimeout(function() {
                    console.log('📧 Mostrando alerta de STATUS (Password Reset):', '{{ session('status') }}');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Enlace Enviado!',
                            text: '{{ session('status') }}',
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: '¡Perfecto!',
                            background: '#ffffff',
                            iconColor: '#2563eb',
                            timer: 5000,
                            timerProgressBar: true,
                            allowOutsideClick: true,
                            allowEscapeKey: true
                        });
                    }
                }, 800);
            </script>
        @endif
    </body>
</html>
                                                                    