<x-guest-layout>
    @section('title', 'Iniciar Sesión - Diócesis de Apartadó')

    <!-- Loading Overlay -->
    <x-loading-overlay id="login-loading" text="Iniciando sesión..." />

    <!-- Session Status -->
    <!-- <x-auth-session-status class="mb-4" :status="session('status')" /> -->

    <!-- Validation Errors -->
    <!-- <x-auth-session-status class="mb-4" :status="session('status')" /> -->

    <form method="POST" action="{{ route('login') }}" id="login-form" class="login-form">
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
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#2f9f37] shadow-sm focus:ring-[#2f9f37]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-[#2f9f37] hover:text-[#2f9f37]/80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f9f37]" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3" type="submit">
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>

    @vite(['resources/js/global/toggle-password-unified.js', 'resources/js/auth/login.js'])

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-700">
            ¿No tienes una cuenta? 
            <a href="{{ route('invitation.verify') }}" class="text-[#2f9f37] hover:text-[#2f9f37]/80 underline font-medium">
                Regístrate aquí
            </a>
        </p>
    </div>


    <!-- Meta tags para mensajes flash -->
    @if(session('flash_message') && session('flash_token'))
        <meta name="flash-message" content="{{ session('flash_message') }}">
        <meta name="flash-token" content="{{ session('flash_token') }}">
        @php
            session()->forget(['flash_message', 'flash_token']);
        @endphp
    @endif

    <!-- Meta tag para acceso no autorizado -->
    @if(session('unauthorized_error'))
        <meta name="unauthorized-error" content="{{ session('unauthorized_error') }}">
        @php
            session()->forget(['unauthorized_error']);
        @endphp
    @endif

    <!-- Meta tags para errores de login -->
    @if(session('error_type') && session('error_message'))
        <meta name="error-type" content="{{ session('error_type') }}">
        <meta name="error-message" content="{{ session('error_message') }}">
        @php
            session()->forget(['error_type', 'error_message']);
        @endphp
    @endif



    <!-- Script para manejar alertas flash -->
    @vite(['resources/js/simple-flash.js'])
    
    <!-- Script para manejar acceso no autorizado -->
    @vite('resources/js/auth/unauthorized-access.js')

    <!-- Scripts para alertas SweetAlert2 -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#2f9f37'
                });
            });
        </script>
    @endif
</x-guest-layout>
