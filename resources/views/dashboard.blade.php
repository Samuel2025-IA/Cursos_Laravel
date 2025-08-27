<x-app-layout>
    @section('title', 'Dashboard - Diócesis de Apartadó')
    <!-- Meta tags para el sistema de alertas flash -->
    @if(session('flash_message') && session('flash_token'))
        <meta name="flash-message" content="{{ session('flash_message') }}">
        <meta name="flash-token" content="{{ session('flash_token') }}">
    @endif

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Sistema de Cursos
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Panel de Control
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Saludo personalizado -->
            <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-emerald-800">
                            ¡Hola, {{ Auth::user()->primer_nombre }}!
                        </h3>
                        <p class="text-emerald-700">
                            Bienvenido al sistema de cursos de la Diócesis de Apartadó
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contenido del dashboard -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700">
                        Panel de Control
                    </h3>
                    <p class="text-gray-600">
                        Aquí podrás gestionar tus cursos y actividades.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script simple para manejar alertas flash -->
    <script src="{{ asset('js/simple-flash.js') }}"></script>
</x-app-layout>
