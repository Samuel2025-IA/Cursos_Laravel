@extends('layouts.dashboard')

@section('title', 'Cursos - Diócesis de Apartadó')

@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
    
    // Para estudiantes, verificar qué cursos ya completaron
    $cursosCompletados = collect();
    if (!$isAdmin) {
        $cursosCompletados = \App\Models\CursoRespuesta::where('user_id', $currentUser->id)
            ->pluck('curso_id')
            ->toArray();
    }
@endphp

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Cursos Disponibles</h1>
        <p class="text-gray-600">Explora los cursos disponibles en el sistema</p>
    </div>

    <!-- Lista de Cursos -->
    @if($cursos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cursos as $curso)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden hover:-translate-y-1">
                    <!-- Header del card -->
                    <div class="bg-gradient-to-r from-[#2f9f37] to-[#27842f] px-6 py-4">
                        <h3 class="text-lg font-bold text-white">{{ $curso->nombre }}</h3>
                    </div>
                    
                    <!-- Contenido del card -->
                    <div class="p-6">
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $curso->descripcion ?? 'Sin descripción disponible' }}
                        </p>
                        
                        <!-- Información del curso -->
                        <div class="space-y-2 mb-4">
                            @if($curso->fecha_inicio)
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Inicia: {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                            
                            @if($curso->fecha_fin)
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Termina: {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</span>
                                </div>
                            @endif

                            @if($curso->instructor)
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Instructor: {{ $curso->instructor }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Footer del card -->
                        <div class="mt-4 pt-4 border-t border-gray-200 space-y-3">
                            <!-- Estado del curso -->
                            <div class="flex justify-center">
                                @if($curso->esta_finalizado)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Expirado
                                    </span>
                                @elseif($curso->activo)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Inactivo
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="flex flex-col sm:flex-row gap-2">
                                @if($isAdmin)
                                    <a href="{{ route('cursos.show', $curso) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-[#2f9f37] hover:bg-[#27842f] text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </a>
                                @else
                                    @php
                                        $cursoCompletado = in_array($curso->id, $cursosCompletados);
                                    @endphp
                                    @if($curso->esta_finalizado)
                                        <button disabled
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-400 text-white text-sm font-semibold rounded-lg cursor-not-allowed opacity-60">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Curso Expirado
                                        </button>
                                    @elseif($cursoCompletado)
                                        <button disabled
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg cursor-not-allowed opacity-75">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Completado
                                        </button>
                                    @else
                                        <a href="{{ route('cursos.iniciar', $curso) }}" 
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-[#2f9f37] hover:bg-[#27842f] text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Iniciar
                                        </a>
                                    @endif
                                @endif
                                
                                @if($isAdmin)
                                    <a href="{{ route('cursos.edit', $curso) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </a>
                                    
                                    <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="delete-course-form flex-1" data-course-name="{{ $curso->nombre }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación de cursos -->
        <div class="mt-8">
            <x-history-pagination :paginator="$cursos" />
        </div>
    @else
        <!-- Estado vacío -->
        <div class="bg-white rounded-xl shadow-md p-12">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay cursos disponibles</h3>
                <p class="text-sm text-gray-600 mb-6">Pronto se publicarán nuevos cursos.</p>
                
                @if($isAdmin)
                    <a href="{{ route('admin.panel') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Crear Cursos
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Volver al Dashboard
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- JavaScript para eliminación de cursos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar formularios de eliminación
    const deleteForms = document.querySelectorAll('.delete-course-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const courseName = this.dataset.courseName || 'este curso';
            const form = this;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Estás seguro de que deseas eliminar el curso "${courseName}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#ffffff',
                iconColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Enviar formulario
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection