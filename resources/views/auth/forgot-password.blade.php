<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Recuperar Contraseña - Diócesis de Apartadó</title>

        <!-- Favicon personalizado -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <x-guest-layout>
            @section('title', 'Recuperar Contraseña - Diócesis de Apartadó')

            <!-- Loading Overlay -->
            <x-loading-overlay id="forgot-password-loading" text="Enviando enlace de recuperación..." />

            <!-- Debug de errores -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <h4 class="text-sm font-medium text-red-800 mb-2">Errores encontrados:</h4>
                    <ul class="text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Debug de sesión -->
            @if(session()->has('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            <div class="mb-4 text-sm text-gray-600">
                {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu dirección de correo electrónico y te enviaremos un enlace de restablecimiento de contraseña que te permitirá elegir una nueva.') }}
            </div>

            <!-- Session Status -->
            <!-- <x-auth-session-status class="mb-4" :status="session('status')" /> -->

            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button type="submit">
                        {{ __('Enviar Enlace de Restablecimiento') }}
                    </x-primary-button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('forgot-password-form');
                    
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            console.log('🎯 Formulario de recuperación enviado!');
                            
                            // Mostrar Loading Overlay
                            showLoading('forgot-password-loading', 'Enviando enlace de recuperación...');
                            
                            // Verificar errores de validación
                            const errors = document.querySelectorAll('.text-red-600');
                            if (errors.length > 0) {
                                console.log('❌ Errores de validación encontrados:', errors.length);
                                hideLoading('forgot-password-loading');
                            } else {
                                console.log('✅ No hay errores de validación visibles');
                            }
                        });
                    }
                });
            </script>
        </x-guest-layout>
    </body>
</html>
