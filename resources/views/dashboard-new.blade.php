@extends('layouts.dashboard')

@section('title', 'Dashboard - Diócesis de Apartadó')

@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
    
    // Preparar datos para las estadísticas
    $stats = [
        'total_cursos' => \App\Models\Curso::count(),
        'cursos_activos' => \App\Models\Curso::where('activo', true)->count(),
        'usuarios_registrados' => \App\Models\User::count(),
        'codigos_activos' => $isAdmin ? \App\Models\InvitationCode::where('used', false)->where('expires_at', '>', now())->count() : 0
    ];
    
    // Obtener cursos recientes
    $cursosRecientes = \App\Models\Curso::latest()->limit(5)->get();
    
    // Para admin: obtener códigos de invitación recientes
    $codigosRecientes = collect();
    if ($isAdmin) {
        $codigosRecientes = \App\Models\InvitationCode::latest()->limit(5)->get();
    }
@endphp

@section('content')
<div class="space-y-6">
    <!-- Mensaje de bienvenida -->
    @if(session('welcome_message'))
        <div class="bg-gradient-to-r from-[#2f9f37] to-[#2f9f37]/90 text-white p-4 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-semibold">¡Bienvenido!</h3>
                    <p class="text-sm opacity-90">{{ session('welcome_message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['total_cursos'] }}</h3>
            <p class="stat-label">Total de Cursos</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>+{{ $stats['cursos_activos'] }} activos</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['cursos_activos'] }}</h3>
            <p class="stat-label">Cursos Activos</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>Disponibles</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['usuarios_registrados'] }}</h3>
            <p class="stat-label">Usuarios Registrados</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>En el sistema</span>
            </div>
        </div>

        @if($isAdmin)
        <div class="stat-card">
            <div class="stat-icon error">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['codigos_activos'] }}</h3>
            <p class="stat-label">Códigos Activos</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>Pendientes</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cursos Recientes -->
        <div class="lg:col-span-2">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Cursos Disponibles
                    </h3>
                </div>
                <div class="dashboard-card-content">
                    @if($cursosRecientes->count() > 0)
                        <div class="space-y-4">
                            @foreach($cursosRecientes as $curso)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-[#2f9f37] rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $curso->nombre ?? 'Curso #' . $curso->id }}</h4>
                                            <p class="text-sm text-gray-500">{{ $curso->descripcion ? Str::limit($curso->descripcion, 60) : 'Curso creado el ' . $curso->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($curso->activo)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactivo
                                            </span>
                                        @endif
                                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-sm btn-primary">
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('cursos.index') }}" class="btn btn-primary">
                                Ver Todos los Cursos
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No hay cursos disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">Los cursos aparecerán aquí cuando estén disponibles.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="space-y-6">
            <!-- Acciones rápidas -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="dashboard-card-content">
                    <div class="space-y-3">
                        <a href="{{ route('cursos.index') }}" class="w-full btn btn-primary flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Ver Cursos
                        </a>
                        
                        @if($isAdmin)
                            <a href="{{ route('admin.panel') }}" class="w-full btn btn-secondary flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Panel Admin
                            </a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" class="w-full btn btn-secondary flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Mi Perfil
                        </a>
                    </div>
                </div>
            </div>

            @if($isAdmin && $codigosRecientes->count() > 0)
            <!-- Códigos de invitación recientes -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h3 class="dashboard-card-title flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Códigos Recientes
                    </h3>
                </div>
                <div class="dashboard-card-content">
                    <div class="space-y-3">
                        @foreach($codigosRecientes as $codigo)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $codigo->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $codigo->code }}</p>
                                </div>
                                <div class="text-right">
                                    @if($codigo->used)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Usado
                                        </span>
                                    @elseif($codigo->expires_at->isPast())
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Expirado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Activo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scripts específicos del dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-ocultar mensaje de bienvenida después de 5 segundos
        const welcomeMessage = document.querySelector('.bg-gradient-to-r.from-\\[\\#2f9f37\\]');
        if (welcomeMessage) {
            setTimeout(() => {
                welcomeMessage.style.opacity = '0';
                setTimeout(() => {
                    welcomeMessage.remove();
                }, 300);
            }, 5000);
        }
    });
</script>
@endpush
