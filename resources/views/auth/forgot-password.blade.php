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
        @vite(['resources/css/app.css'])
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Meta tags para el sistema de alertas flash -->
        @if(session('status'))
            <meta name="flash-message" content="{{ session('status') }}">
            <meta name="flash-token" content="{{ Str::random(32) }}">
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <x-guest-layout>
            @section('title', 'Recuperar Contraseña - Diócesis de Apartadó')

            <!-- Loading Overlay -->
            <x-loading-overlay id="forgot-password-loading" text="Enviando enlace de recuperación..." />

            <div class="mb-4 text-sm text-gray-600">
                {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu dirección de correo electrónico y te enviaremos un enlace de restablecimiento de contraseña que te permitirá elegir una nueva.') }}
            </div>

            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <!-- Ocultamos el error estándar de Laravel -->
                    <div class="hidden">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button type="submit">
                        {{ __('Enviar Enlace de Restablecimiento') }}
                    </x-primary-button>
                </div>
            </form>

            @vite('resources/js/auth/forgot-password.js')
        </x-guest-layout>
    </body>
</html>
