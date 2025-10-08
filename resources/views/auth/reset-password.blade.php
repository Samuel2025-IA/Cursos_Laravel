<x-guest-layout>
    @section('title', 'Restablecer Contraseña - Diócesis de Apartadó')

    <!-- Loading Overlay -->
    <x-loading-overlay id="reset-password-loading" text="Restableciendo contraseña..." />

    <form method="POST" action="{{ route('password.store') }}" id="reset-password-form">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
        <!-- Email Address (oculto para Laravel) -->
        <input type="hidden" name="email" value="{{ $request->email }}">

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Nueva Contraseña')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-12" type="password" name="password" required autofocus autocomplete="new-password" />
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password')"
                        title="Mostrar/Ocultar contraseña">
                    <!-- Icono de ojo abierto (mostrar) -->
                    <svg id="eye-icon-password" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <!-- Icono de ojo tachado (ocultar) -->
                    <svg id="eye-slash-icon-password" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </button>
            </div>
            <!-- Errores ocultos - se manejan con SweetAlert2 -->
            <div class="hidden">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-12" type="password" name="password_confirmation" required autocomplete="new-password" />
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password_confirmation')"
                        title="Mostrar/Ocultar contraseña">
                    <!-- Icono de ojo abierto (mostrar) -->
                    <svg id="eye-icon-password_confirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <!-- Icono de ojo tachado (ocultar) -->
                    <svg id="eye-slash-icon-password_confirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </button>
            </div>
            <!-- Errores ocultos - se manejan con SweetAlert2 -->
            <div class="hidden">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button type="submit">
                {{ __('Restablecer Contraseña') }}
            </x-primary-button>
        </div>
    </form>

    @vite(['resources/js/global/toggle-password-unified.js', 'resources/js/auth/reset-password.js'])

    <!-- Script para validación en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');
            const submitBtn = document.querySelector('button[type="submit"]');

            // Función para validar contraseñas
            function validarContraseñas() {
                const password = passwordField.value;
                const confirmPassword = confirmPasswordField.value;

                // Solo validar si ambos campos tienen contenido
                if (password && confirmPassword) {
                    if (password !== confirmPassword) {
                        // Mostrar alerta de error
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Las contraseñas no coinciden!',
                                text: 'Por favor, asegúrate de que ambas contraseñas sean exactamente iguales.',
                                confirmButtonColor: '#dc2626',
                                confirmButtonText: 'Aceptar',
                                background: '#ffffff',
                                iconColor: '#dc2626',
                                allowOutsideClick: true,
                                allowEscapeKey: true
                            });
                        }
                        return false;
                    }
                }

                // Validar longitud mínima
                if (password && password.length < 8) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Contraseña muy corta!',
                            text: 'La contraseña debe tener al menos 8 caracteres.',
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'Aceptar',
                            background: '#ffffff',
                            iconColor: '#dc2626',
                            allowOutsideClick: true,
                            allowEscapeKey: true
                        });
                    }
                    return false;
                }

                return true;
            }

            // Validar cuando se escriba en el campo de confirmación
            confirmPasswordField.addEventListener('blur', function() {
                validarContraseñas();
            });

            // Validar cuando se escriba en el campo de contraseña
            passwordField.addEventListener('blur', function() {
                if (confirmPasswordField.value) {
                    validarContraseñas();
                }
            });

            // Validar antes de enviar el formulario
            document.getElementById('reset-password-form').addEventListener('submit', function(e) {
                if (!validarContraseñas()) {
                    e.preventDefault();
                    return false;
                }
                
                // Si todo está bien, mostrar loading
                if (typeof showLoading === 'function') {
                    showLoading('reset-password-loading', 'Restableciendo contraseña...');
                }
            });
        });
    </script>

</x-guest-layout>
