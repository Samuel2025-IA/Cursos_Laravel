<x-guest-layout>
    @section('title', 'Iniciar Sesión - Diócesis de Apartadó')

    <!-- Loading Overlay -->
    <x-loading-overlay id="login-loading" text="Iniciando sesión..." />

    <!-- Session Status -->
    <!-- <x-auth-session-status class="mb-4" :status="session('status')" /> -->

    <!-- Validation Errors -->

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-emerald-600 hover:text-emerald-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3" type="submit">
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('🎯 Formulario de login enviado!');
                    
                    // Mostrar Loading Overlay
                    showLoading('login-loading', 'Iniciando sesión...');
                    
                    // Verificar errores de validación
                    const errors = document.querySelectorAll('.text-red-600');
                    if (errors.length > 0) {
                        console.log('❌ Errores de validación encontrados:', errors.length);
                        hideLoading('login-loading');
                    } else {
                        console.log('✅ No hay errores de validación visibles');
                    }
                });
            }
        });
    </script>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-700">
            ¿No tienes una cuenta? 
            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 underline font-medium">
                Regístrate aquí
            </a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
            const eyeSlashIcon = document.getElementById(`eye-slash-icon-${fieldId}`);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

    </script>

    <!-- Meta tags para mensajes flash -->
    @if(session('flash_message') && session('flash_token'))
        <meta name="flash-message" content="{{ session('flash_message') }}">
        <meta name="flash-token" content="{{ session('flash_token') }}">
        @php
            session()->forget(['flash_message', 'flash_token']);
        @endphp
    @endif



    <!-- Script simple para manejar alertas flash (temporal) -->
    <script src="{{ asset('js/simple-flash.js') }}"></script>
</x-guest-layout>
