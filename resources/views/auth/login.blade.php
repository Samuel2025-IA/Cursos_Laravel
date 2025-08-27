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
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-12"
                             type="password"
                             name="password"
                             required autocomplete="current-password" />
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
