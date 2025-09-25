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

        <!-- Scripts - CSS unificado -->
        <link rel="stylesheet" href="{{ asset('css/all-styles.css') }}">
        
        <!-- CSS de respaldo directo -->
        <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
        
        <!-- Tailwind CSS CDN como respaldo adicional -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary': '#2f9f37',
                        }
                    }
                }
            }
        </script>
        
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
        
        <!-- SweetAlert2 Helper Functions - DEFINIDAS DIRECTAMENTE -->
        <script>
            // Función global para alertas de error
            window.showErrorAlert = function(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: message,
                        confirmButtonColor: '#2f9f37', // Verde
                        confirmButtonText: '¡Perfecto!',
                        background: '#ffffff',
                        iconColor: '#dc2626'
                    });
                } else {
                    alert('ERROR: ' + message);
                }
            };

            // Función global para alertas de advertencia
            window.showWarningAlert = function(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Atención!',
                        text: message,
                        confirmButtonColor: '#2f9f37', // Verde
                        confirmButtonText: '¡Perfecto!',
                        background: '#ffffff',
                        iconColor: '#d97706'
                    });
                } else {
                    alert('ADVERTENCIA: ' + message);
                }
            };

            // Función global para alertas de información
            window.showInfoAlert = function(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        text: message,
                        confirmButtonColor: '#2563eb', // Azul
                        confirmButtonText: 'Aceptar',
                        background: '#ffffff',
                        iconColor: '#2563eb'
                    });
                } else {
                    alert('INFO: ' + message);
                }
            };

            // Función global para alertas de éxito
            window.showSuccessAlert = function(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: message,
                        confirmButtonColor: '#2f9f37', // Verde
                        confirmButtonText: '¡Perfecto!',
                        background: '#ffffff',
                        iconColor: '#059669',
                        timer: 4000,
                        timerProgressBar: true
                    });
                } else {
                    alert('ÉXITO: ' + message);
                }
            };

            console.log('✅ Funciones de alerta cargadas directamente en app.blade.php');
        </script>

        <!-- Mostrar alertas desde sesiones de Laravel -->
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('🔴 Mostrando alerta de error:', '{{ session('error') }}');
                    showErrorAlert('{{ session('error') }}');
                });
            </script>
        @endif

        @if(session('warning'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('🟡 Mostrando alerta de advertencia:', '{{ session('warning') }}');
                    showWarningAlert('{{ session('warning') }}');
                });
            </script>
        @endif

        @if(session('info'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('🔵 Mostrando alerta de información:', '{{ session('info') }}');
                    showInfoAlert('{{ session('info') }}');
                });
            </script>
        @endif

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('🟢 Mostrando alerta de éxito:', '{{ session('success') }}');
                    showSuccessAlert('{{ session('success') }}');
                });
            </script>
        @endif

        <!-- Manejo de errores de validación específicos -->
        @if($errors->updatePassword->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        const errors = [
                            @foreach($errors->updatePassword->all() as $error)
                                '{{ $error }}',
                            @endforeach
                        ];
                        
                        if (errors.length > 0 && typeof Swal !== 'undefined') {
                            const errorMessage = errors.length === 1 ? errors[0] : errors.join('\n• ');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error en la Contraseña',
                                text: errorMessage,
                                confirmButtonColor: '#dc2626',
                                confirmButtonText: 'Aceptar',
                                background: '#ffffff',
                                iconColor: '#dc2626',
                                allowOutsideClick: true,
                                allowEscapeKey: true
                            });
                        }
                    }, 800);
                });
            </script>
        @endif
    </body>
</html>
