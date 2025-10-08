<x-app-layout>
    @section('title', 'Dashboard - Diócesis de Apartadó')
    <!-- Meta tags para el sistema de alertas flash -->
    @if(session('flash_message') && session('flash_token'))
        <meta name="flash-message" content="{{ session('flash_message') }}">
        <meta name="flash-token" content="{{ session('flash_token') }}">
    @endif

    <!-- Meta tag para mensaje de bienvenida -->
    @if(session('welcome_message'))
        <meta name="welcome-message" content="{{ session('welcome_message') }}">
    @endif

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-2">
            
            <!-- Sección de Cursos Disponibles -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#2f9f37] to-[#2f9f37]/90 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">{{ __('Cursos Disponibles') }}</h2>
                    <p class="text-sm text-white/80 mt-1">
                        {{ __('Explora los cursos disponibles en el sistema') }}
                    </p>
                </div>
                <div class="p-4">
                    <div class="text-center py-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-xl font-bold text-gray-900">Bienvenido al Sistema de Cursos</h3>
                        <p class="mt-2 text-base text-gray-600">Aquí podrás ver y gestionar los cursos disponibles.</p>
                        <div class="mt-4">
                            <a href="{{ route('cursos.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-lg text-base font-semibold rounded-lg text-white bg-[#2f9f37] hover:bg-[#2f9f37]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f9f37] transition-all duration-200 hover:scale-105">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Ver Cursos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejar alertas flash y mensaje de bienvenida -->
    @vite(['resources/js/dashboard.js'])
</x-app-layout>
