<x-guest-layout>
    @section('title', 'Verificar Código de Invitación - Diócesis de Apartadó')
    
    <!-- Logo y título -->
    <svg class="mx-auto h-12 w-12 text-[#2f9f37] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h2 class="text-xl font-bold text-gray-900 text-center mb-2">Código de Invitación</h2>
    

    <!-- Formulario -->
    <form id="verify-form" method="POST" action="{{ route('invitation.verify') }}" class="space-y-4 verify-invitation-form">
        @csrf
        
        <input 
            id="code" 
            name="code" 
            type="text" 
            maxlength="8"
            class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] text-center text-lg font-mono tracking-widest uppercase" 
            placeholder="ABC12345"
            value="{{ old('code') }}"
            required 
            autofocus
            oninput="this.value = this.value.toUpperCase()"
        >

        <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-[#2f9f37] hover:bg-[#2f9f37]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f9f37]">
            Verificar Código
        </button>
    </form>

    <!-- Información adicional eliminada - se maneja por SweetAlert2 en guest layout -->

    <!-- Enlaces -->
    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-[#2f9f37] hover:text-[#2f9f37]/80">
            ¿Ya tienes cuenta? Inicia sesión
        </a>
    </div>
    
    
    <!-- Loading Overlay Component -->
    <x-loading-overlay id="verify-loading" text="Verificando código..." />
    
    <!-- Script para mostrar información de invitación -->
    @vite('resources/js/auth/verify-invitation.js')
</x-guest-layout>
