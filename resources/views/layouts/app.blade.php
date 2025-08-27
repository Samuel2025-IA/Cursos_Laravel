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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
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

        <!-- SweetAlert2 Helper Functions -->
        <script>
            // Configuración global de SweetAlert2 con colores de la Diócesis
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Helper functions
            window.showSuccess = function(message) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Exito!',
                    text: message,
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#059669',
                    timer: 4000,
                    timerProgressBar: true
                });
            };

            window.showError = function(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Entendido',
                    background: '#ffffff',
                    iconColor: '#dc2626'
                });
            };

            window.showWarning = function(message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: message,
                    confirmButtonColor: '#d97706',
                    confirmButtonText: 'Entendido',
                    background: '#ffffff',
                    iconColor: '#d97706'
                });
            };

            window.showInfo = function(message) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: message,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#2563eb'
                });
            };

            window.showConfirm = function(title, text, confirmCallback) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar',
                    background: '#ffffff',
                    color: '#374151'
                }).then((result) => {
                    if (result.isConfirmed && confirmCallback) {
                        confirmCallback();
                    }
                });
            };

            // Función para confirmar logout con Miky
            window.confirmLogout = function() {
                Swal.fire({
                    title: '¿Ya te vas tan rápido? ',
                    text: '¡Miky se pondrá triste si te vas!',
                    imageUrl: '{{ asset("img/miky.jpg") }}',
                    imageWidth: 200,
                    imageHeight: 200,
                    imageAlt: 'Miky triste',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#059669',
                    confirmButtonText: 'Sí, cerrar sesión',
                    cancelButtonText: 'No, me quedo',
                    background: '#ffffff',
                    customClass: {
                        image: 'rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buscar y enviar el formulario de logout activo
                        let logoutForm = document.getElementById('logout-form');
                        if (!logoutForm) {
                            logoutForm = document.getElementById('logout-form-mobile');
                        }
                        
                        if (logoutForm) {
                            logoutForm.submit();
                        } else {
                            // Fallback: crear un formulario temporal
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("logout") }}';
                            
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            
                            form.appendChild(csrfToken);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                });
            };
        </script>

        <!-- Mostrar alertas desde sesiones de Laravel -->
        @if(session('error'))
            <script>
                showError('{{ session('error') }}');
            </script>
        @endif

        @if(session('warning'))
            <script>
                showWarning('{{ session('warning') }}');
            </script>
        @endif

        @if(session('info'))
            <script>
                showInfo('{{ session('info') }}');
            </script>
        @endif
    </body>
</html>
