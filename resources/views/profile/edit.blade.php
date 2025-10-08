<x-app-layout>
    @section('title', 'Mi Perfil - Diócesis de Apartadó')
    <x-slot name="header">
    </x-slot>

    <div class="py-6 profile-container">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Contenedor Principal del Perfil - Ancho Completo -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden profile-card fade-in">
                <div class="profile-header px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-white">{{ __('Mi Perfil') }}</h1>
                            <p class="text-white/90 mt-1">{{ __('Gestiona la información de tu cuenta y configuración de seguridad') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Sección de Cambiar Contraseña - Debajo del perfil -->
            <div class="mt-6">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden profile-card fade-in">
                <div class="password-header px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Cambiar Contraseña
                    </h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Estilos del Perfil -->
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    
    <!-- Scripts del Perfil -->
    <script src="{{ asset('js/views/profile/profile.js') }}"></script>
</x-app-layout>
