@extends('layouts.dashboard')

@section('title', 'Diócesis de Apartadó')

{{-- 
    Comentario: Se movió la lógica de consultas a esta sección temporal
    TODO: Mover estas consultas a un controlador dedicado
--}}
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';

    $stats = $statsFromRoute ?? [
        'total_cursos' => \App\Models\Curso::count(),
        'cursos_activos' => \App\Models\Curso::where('activo', true)->count(),
        'cursos_finalizados' => \App\Models\Curso::whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<=', now())
            ->count(),
        'usuarios_registrados' => \App\Models\User::count(),
        'codigos_activos' => $isAdmin ? \App\Models\InvitationCode::where('used', false)->where('expires_at', '>', now())->count() : 0,
    ];

    $cursosRecientes = $recentCoursesFromRoute ?? \App\Models\Curso::latest()
        ->limit(5)
        ->get();
    
    // Para estudiantes, verificar qué cursos ya completaron
    $cursosCompletados = collect();
    if (!$isAdmin) {
        $cursosCompletados = \App\Models\CursoRespuesta::where('user_id', $currentUser->id)
            ->pluck('curso_id')
            ->toArray();
    }

    $dashboardNotifications = collect($dashboardNotifications ?? []);
    $notificationsCount = $notificationsCount ?? $dashboardNotifications->count();

    $codigosRecientes = collect();
    if ($isAdmin) {
        $codigosRecientes = \App\Models\InvitationCode::latest()->limit(5)->get();
    }
@endphp

@section('content')
<div class="space-y-0">
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
        {{-- Tarjeta: Cursos Activos --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-green-500 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Cursos Activos</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['cursos_activos'] }}</h3>
                    <div class="flex items-center mt-2 text-sm text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Disponibles</span>
                    </div>
                </div>
                <div class="flex-shrink-0 bg-green-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Cursos Finalizados --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-red-500 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Cursos Finalizados</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['cursos_finalizados'] ?? 0 }}</h3>
                    <div class="flex items-center mt-2 text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium">Actualizado {{ now()->format('d/m') }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0 bg-red-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13a4 4 0 014-4h10a4 4 0 014 4v3a4 4 0 01-4 4H7a4 4 0 01-4-4v-3zM7 9V7a5 5 0 0110 0v2" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Usuarios Registrados (solo para admin) --}}
        @if($isAdmin)
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-blue-500 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Usuarios Registrados</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['usuarios_registrados'] }}</h3>
                    <div class="flex items-center mt-2 text-sm text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-medium">En el sistema</span>
                    </div>
                </div>
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
        </div>
        @endif

        @if($isAdmin)
        {{-- Tarjeta: Códigos Activos (Solo Admin) --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border-l-4 border-orange-500 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Códigos Activos</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['codigos_activos'] }}</h3>
                    <div class="flex items-center mt-2 text-sm text-orange-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Pendientes</span>
                    </div>
                </div>
                <div class="flex-shrink-0 bg-orange-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Contenido principal -->
    <div class="grid grid-cols-1 @if($isAdmin) lg:grid-cols-3 @else lg:grid-cols-1 @endif gap-4 md:gap-4 mt-2">
        <!-- Cursos Recientes -->
        <div class="@if($isAdmin) lg:col-span-2 @endif w-full">
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
                        <div class="@if($isAdmin) space-y-4 @else grid grid-cols-1 md:grid-cols-2 gap-4 @endif">
                            @foreach($cursosRecientes as $curso)
                                @php
                                    $cursoActivo = !$curso->esta_finalizado && $curso->activo;
                                @endphp
                                <div @class([
                                    'flex flex-col items-start p-4 sm:p-5 rounded-xl transition-all duration-200 gap-3',
                                    'hover:shadow-lg',
                                    'border-2',
                                    $cursoActivo ? 'border-green-300 bg-green-50 hover:border-green-500 hover:bg-green-100' : 'border-gray-200 bg-white hover:border-[#2f9f37] hover:bg-gray-50',
                                    $isAdmin ? 'sm:flex-row sm:items-center justify-between sm:gap-0' : null,
                                ])>
                                    <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0 w-full sm:w-auto">
                                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#2f9f37] to-[#2f9f37]/80 rounded-xl flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate text-sm sm:text-base">{{ $curso->nombre ?? 'Curso #' . $curso->id }}</h4>
                                            <p class="text-xs sm:text-sm text-gray-600 truncate">
                                                @if($curso->descripcion)
                                                    {{ Str::limit($curso->descripcion, 50) }}
                                                @elseif($curso->instructor)
                                                    Instructor: {{ $curso->instructor }}
                                                @else
                                                    Curso creado el {{ $curso->created_at->format('d/m/Y') }}
                                                @endif
                                            </p>
                                            @if($curso->esta_finalizado && $curso->fecha_fin)
                                                <p class="text-xs text-red-600 font-medium mt-1">Finalizado el {{ $curso->fecha_fin->format('d/m/Y') }}</p>
                                            @elseif($curso->fecha_fin)
                                                <p class="text-xs text-green-600 font-medium mt-1">Finaliza el {{ $curso->fecha_fin->format('d/m/Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex @if($isAdmin) items-center space-x-2 w-full sm:w-auto justify-end sm:justify-start @else flex-col space-y-3 w-full @endif">
                                        {{-- Estado del curso --}}
                                        @if($curso->esta_finalizado)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Finalizado
                                            </span>
                                        @elseif($curso->activo)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Inactivo
                                            </span>
                                        @endif
                                        {{-- Botón Ver/Iniciar Curso --}}
                                        @if($isAdmin)
                                            <a href="{{ route('cursos.show', $curso) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 bg-[#2f9f37] hover:bg-[#2f9f37]/90 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Ver</span>
                                            </a>
                                        @else
                                            @php
                                                $cursoCompletado = in_array($curso->id, $cursosCompletados);
                                            @endphp
                                            @if($curso->esta_finalizado)
                                                <button disabled
                                                        class="w-full flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-400 text-white text-xs sm:text-sm font-semibold rounded-lg cursor-not-allowed opacity-60">
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Curso Expirado</span>
                                                </button>
                                            @elseif($cursoCompletado)
                                                <button disabled
                                                        class="w-full flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 bg-blue-500 text-white text-xs sm:text-sm font-semibold rounded-lg cursor-not-allowed opacity-75">
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Completado</span>
                                                </button>
                                            @else
                                                <a href="{{ route('cursos.iniciar', $curso) }}" 
                                                   class="w-full flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 bg-[#2f9f37] hover:bg-[#2f9f37]/90 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Iniciar</span>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                            <a href="{{ route('cursos.index') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-[#2f9f37] hover:bg-[#2f9f37]/90 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>Ver Todos los Cursos</span>
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

        <!-- Panel lateral (solo para admin) -->
        @if($isAdmin)
        <div class="space-y-4">
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
                        {{-- Botón Ver Cursos --}}
                        <a href="{{ route('cursos.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-[#2f9f37] hover:bg-[#2f9f37]/90 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Ver Cursos</span>
                        </a>
                        
                        @if($isAdmin)
                            {{-- Botón Panel Admin --}}
                            <a href="{{ route('admin.panel') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Panel Admin</span>
                            </a>
                        @endif
                        
                        {{-- Botón Mi Perfil --}}
                        <a href="{{ route('profile.edit') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Mi Perfil</span>
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
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!('pushState' in window.history)) {
            return;
        }

        const enforceDashboardState = function () {
            window.history.pushState(null, document.title, window.location.href);
        };

        const handlePopState = function () {
            enforceDashboardState();
        };

        enforceDashboardState();
        window.addEventListener('popstate', handlePopState);

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                enforceDashboardState();
            }
        });

        window.addEventListener('beforeunload', function () {
            window.removeEventListener('popstate', handlePopState);
        });
    });
</script>
@endpush
