<x-app-layout class="with-sidebar">
    @section('title', 'Dashboard - Diócesis de Apartadó')
    <!-- Meta tags para el sistema de alertas flash -->
    @if(session('flash_message') && session('flash_token'))
        <meta name="flash-message" content="{{ session('flash_message') }}">
        <meta name="flash-token" content="{{ session('flash_token') }}">
    @endif

    <!-- Meta tag para mensaje de bienvenida -->
    @if(session('welcome_message'))
        <meta name="welcome-message" content="{{ session('welcome_message') }}">
        @php
            session()->forget(['welcome_message']);
        @endphp
    @endif


    @if(Auth::user()->rol === 'admin')
    <!-- CSS unificado -->
    <link rel="stylesheet" href="{{ asset('css/all-styles.css') }}">
    
    <!-- CSS de respaldo principal -->
    <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
    
    <!-- Estilos inline como respaldo final -->
    <style>
        .app-sidebar {
            position: fixed;
            inset: 64px auto 0 0;
            width: 260px;
            background-color: #2A3658;
            border-right: 1px solid rgba(255,255,255,0.08);
            color: #e5e7eb;
            z-index: 40;
            transition: width .25s ease, transform .25s ease;
            display: flex;
            flex-direction: column;
        }
        .app-sidebar .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: .02em;
            padding: 16px 16px;
            transition: opacity 0.25s ease;
        }
        .app-sidebar .nav-list {
            list-style: none;
            margin: 0;
            padding: 8px;
        }
        .app-sidebar .nav-item {
            margin: 2px 0;
        }
        .app-sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #e5e7eb;
            text-decoration: none;
            transition: background .2s ease, color .2s ease;
        }
        .app-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .app-sidebar .nav-link .ico {
            width: 18px;
            height: 18px;
            color: currentColor;
            flex-shrink: 0;
        }
        .with-sidebar .dashboard-content {
            margin-left: 280px;
            transition: margin-left .25s ease;
        }
        .sidebar-toggle-btn {
            position: fixed;
            top: 84px;
            left: 280px;
            z-index: 50;
            width: 40px;
            height: 40px;
            background: #000;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .sidebar-toggle-btn:hover {
            background: #1a1a1a;
            border-color: rgba(255,255,255,0.3);
        }
        @media (max-width: 768px) {
            .app-sidebar {
                transform: translateX(-100%);
                width: 260px;
            }
            .app-sidebar.open {
                transform: translateX(0);
            }
            .with-sidebar .dashboard-content {
                margin-left: 0;
            }
            .sidebar-toggle-btn {
                left: 20px;
                top: 84px;
            }
        }
    </style>
    
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

    <div class="dashboard-content py-12" style="margin-top: 80px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Sección de Cursos Disponibles -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#2f9f37] to-[#2f9f37]/90 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">{{ __('Cursos Disponibles') }}</h2>
                    <p class="text-sm text-white/80 mt-1">
                        {{ __('Explora los cursos disponibles en el sistema') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Bienvenido al Sistema de Cursos</h3>
                        <p class="mt-1 text-sm text-gray-500">Aquí podrás ver y gestionar los cursos disponibles.</p>
                        <div class="mt-6">
                            <a href="{{ route('cursos.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#2f9f37] hover:bg-[#2f9f37]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f9f37]">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Script simple para manejar alertas flash -->
    @vite(['resources/js/simple-flash.js'])
    <!-- SweetAlert2 (para mostrar bienvenida si está disponible) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script para lógica del dashboard (sidebar + bienvenida) -->
    @vite(['resources/js/dashboard.js'])
</x-app-layout>
