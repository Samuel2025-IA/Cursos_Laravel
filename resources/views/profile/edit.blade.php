@extends('layouts.dashboard')

@section('title', 'Mi Perfil')

@section('content')
<div class="space-y-6 -mt-16">
    <!-- Encabezado principal, consistente con el dashboard -->
    <div class="bg-gradient-to-r from-[#2f9f37] to-[#27842f] text-white p-4 sm:p-6 rounded-xl shadow-lg">
        <div class="flex items-start gap-3 sm:gap-4">
            <div class="flex-shrink-0 bg-white/20 rounded-full p-2 sm:p-3">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex-1">
                <h1 class="text-lg sm:text-xl font-bold">Mi Perfil</h1>
                <p class="text-white/90 text-sm sm:text-base">Gestiona tu información y configuración de seguridad</p>
            </div>
        </div>
    </div>

    <!-- Contenido: estructura plana y elegante sin contenedores anidados -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información Personal -->
        <section class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <header class="px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-600/10 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Información Personal</h2>
            </header>
            <div class="p-4 sm:p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <!-- Seguridad -->
        <section class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <header class="px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-slate-700/10 text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </span>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Configuración de Seguridad</h2>
            </header>
            <div class="p-4 sm:p-6">
                @include('profile.partials.update-password-form')
            </div>
        </section>
    </div>
</div>

@push('styles')
<style>
    /* Estilos minimalistas para inputs */
    .profile-input {
        border: 1px solid #d1d5db !important; /* borde gris suave por defecto */
        border-radius: 8px !important;
        padding: 12px 16px !important;
        font-size: 14px !important;
        transition: all 0.2s ease !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
    }
    
    .profile-input:focus {
        border-color: #2f9f37 !important; /* verde dashboard */
        box-shadow: 0 0 0 3px rgba(47, 159, 55, 0.15) !important; /* halo verde suave */
        outline: none !important;
        border-radius: 8px !important; /* asegurar bordes redondeados */
    }
    
    .profile-input:hover {
        border-color: #cbd5e1 !important; /* leve realce al pasar el mouse */
    }
    
    /* Botones de toggle más elegantes */
    .password-toggle-btn {
        background: transparent !important;
        border: none !important;
        color: #9ca3af !important;
        transition: color 0.2s ease !important;
    }
    
    .password-toggle-btn:hover {
        color: #2f9f37 !important;
        background: transparent !important;
    }
    
    /* Estilo de contenedores del admin */
    .admin-style-container {
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e5e7eb !important;
    }
    
    /* Headers de sección */
    .section-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
        border: none !important;
    }
    
    /* Contenido de sección */
    .section-content {
        background-color: #f9fafb !important;
        border: 1px solid #d1d5db !important;
        border-top: none !important;
        border-radius: 0 0 0.5rem 0.5rem !important;
    }
    
    /* Animaciones suaves */
    .smooth-transition {
        transition: all 0.3s ease !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Scripts específicos del perfil (minimalistas, sin animaciones invasivas)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ Perfil cargado correctamente');        
        // Aplicar estilos minimalistas a los inputs
        applyMinimalistStyles();
        // Config extras
        // Funcionalidades básicas del perfil
        setupProfileFeatures();
    });
    
    function applyMinimalistStyles() {
        // Aplicar estilos a todos los inputs
        const inputs = document.querySelectorAll('input[type="password"], input[type="text"], input[type="email"]');
        inputs.forEach(input => {
            input.classList.add('profile-input');
        });
    }
    
    function setupProfileFeatures() {
        // Configurar botones de toggle de contraseña
        const toggleButtons = document.querySelectorAll('button[data-field]');
        toggleButtons.forEach(button => {
            const fieldId = button.getAttribute('data-field');
            button.addEventListener('click', (e) => {
                e.preventDefault();
                togglePassword(fieldId);
            });
        });
        
        // Sin efectos visuales adicionales en focus
    }
    
    // Función togglePassword (simplificada)
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(`eye-${fieldId}`);
        
        if (field && eyeIcon) {
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                field.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    }
</script>
@endpush
@endsection