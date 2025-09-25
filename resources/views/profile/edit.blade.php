<x-app-layout class="with-sidebar">
    @section('title', 'Mi Perfil - Diócesis de Apartadó')
    <x-slot name="header">
        <div class="header-content" style="margin-left: 280px; transition: margin-left 0.25s ease;">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Gestiona la información de tu cuenta y configuración de seguridad') }}
        </p>
        </div>
    </x-slot>

    @if(Auth::user()->rol === 'admin')
    @vite(['resources/css/sidebar.css'])
    <div class="app-sidebar">
        <div class="section-title">Panel General</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5l9-7 9 7V20a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-4H10v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.5z"/></svg>
                    <span>Hogar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 9h16"/></svg>
                    <span>Bandeja de entrada</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Calendario</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>Buscar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V22a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H2a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H8a1.65 1.65 0 0 0 1-1.51V2a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V8c0 .66.26 1.3.73 1.77.47.47 1.11.73 1.77.73H22a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Ajustes</span>
                </a>
            </li>
        </ul>
    </div>
    @vite(['resources/js/sidebar.js'])
    @endif

    <!-- Botón de colapso flotante -->
    @if(Auth::user()->rol === 'admin')
    <button class="sidebar-toggle-btn" type="button" title="Colapsar sidebar" data-toggle-sidebar style="background: transparent; border: none; box-shadow: none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <line x1="9" y1="3" x2="9" y2="21"/>
        </svg>
    </button>
    @endif

    <div class="py-12 dashboard-content" style="margin-top: 80px; margin-left: 280px; transition: margin-left 0.25s ease;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Información del Perfil -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#2f9f37] to-[#2f9f37]/90 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Información del Perfil
                    </h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#2D3A73] to-[#1e2a5a] px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <script>
        // Ajustar contenido cuando el sidebar se colapsa/expande
        function adjustContentForSidebar(collapsed = null) {
            const sidebar = document.querySelector('.app-sidebar');
            const content = document.querySelector('.dashboard-content');
            const headerContent = document.querySelector('.header-content');
            
            if (sidebar && content) {
                const isCollapsed = collapsed !== null ? collapsed : sidebar.classList.contains('collapsed');
                
                if (isCollapsed) {
                    content.style.marginLeft = '84px';
                    if (headerContent) {
                        headerContent.style.marginLeft = '84px';
                    }
                } else {
                    content.style.marginLeft = '280px';
                    if (headerContent) {
                        headerContent.style.marginLeft = '280px';
                    }
                }
            }
        }

        // Escuchar el evento personalizado del sidebar
        document.addEventListener('sidebarToggle', function(event) {
            adjustContentForSidebar(event.detail.collapsed);
        });

        // Ajustar inicialmente cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            adjustContentForSidebar();
        });
    </script>
</x-app-layout>
