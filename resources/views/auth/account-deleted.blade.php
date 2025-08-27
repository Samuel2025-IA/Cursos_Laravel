<x-guest-layout>
    @section('title', 'Cuenta Eliminada - Diócesis de Apartadó')

    <!-- Loading Overlay -->
    <x-loading-overlay id="account-deleted-loading" text="Verificando cuenta..." />

    <!-- Mensaje de cuenta eliminada -->
    <div class="mb-6 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Cuenta No Encontrada
        </h2>
        
        <p class="text-gray-600 mb-4">
            {{ $message }}
        </p>
        
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-700">
                <strong>Email:</strong> {{ $email }}
            </p>
        </div>
        
        <p class="text-sm text-gray-500 mb-6">
            Si crees que esto es un error, contacta al administrador del sistema.
        </p>
    </div>

    <!-- Botones de acción -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('login') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Ir al Login
        </a>
        
        <a href="{{ route('password.request') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Solicitar Otro Enlace
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Vista de cuenta eliminada cargada');
            console.log('📧 Email:', '{{ $email }}');
            console.log('💬 Mensaje:', '{{ $message }}');
        });
    </script>
</x-guest-layout>

