@extends('layouts.dashboard')

@section('title', 'Recursos - Diócesis de Apartadó')

@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Recursos de Lectura</h1>
            <p class="text-gray-600">Explora y descarga los recursos disponibles</p>
        </div>
        
        @if($isAdmin)
            <a href="{{ route('recursos.create') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Subir Recurso
            </a>
        @endif
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Lista de Recursos -->
    @if($recursos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recursos as $recurso)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden hover:-translate-y-1 flex flex-col h-full">
                    <!-- Header del card -->
                    <div class="bg-gradient-to-r from-[#2f9f37] to-[#27842f] px-6 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-bold text-white flex-1 break-words leading-tight">{{ $recurso->nombre }}</h3>
                            <div class="flex-shrink-0 mt-0.5">
                                @if($recurso->tipo_archivo === 'pdf')
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido del card -->
                    <div class="p-6 flex-1 flex flex-col">
                        @if($recurso->descripcion)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 break-words">
                                {{ $recurso->descripcion }}
                            </p>
                        @endif
                        
                        <!-- Información del recurso -->
                        <div class="space-y-2.5 mb-4 flex-1">
                            <div class="flex items-start text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="capitalize break-words">{{ $recurso->tipo_archivo === 'pdf' ? 'PDF' : 'Word' }}</span>
                            </div>
                            
                            <div class="flex items-start text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                </svg>
                                <span class="break-words">{{ $recurso->tamaño_formateado }}</span>
                            </div>
                            
                            <div class="flex items-start text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="break-words">Subido por: {{ $recurso->user->primer_nombre }} {{ $recurso->user->primer_apellido }}</span>
                            </div>
                            
                            <div class="flex items-start text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-[#2f9f37] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="break-words">{{ $recurso->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Footer del card -->
                        <div class="mt-auto pt-4 border-t border-gray-200">
                            <!-- Botones de acción -->
                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('recursos.download', $recurso) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-[#2f9f37] hover:bg-[#27842f] text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar
                                </a>
                                
                                @if($isAdmin)
                                    <form action="{{ route('recursos.destroy', $recurso) }}" method="POST" class="delete-recurso-form flex-1" data-recurso-name="{{ $recurso->nombre }}">
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

        <!-- Paginación -->
        <div class="mt-8">
            <x-history-pagination :paginator="$recursos" />
        </div>
    @else
        <!-- Estado vacío -->
        <div class="bg-white rounded-xl shadow-md p-12">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay recursos disponibles</h3>
                <p class="text-sm text-gray-600 mb-6">Aún no se han subido recursos de lectura.</p>
                
                @if($isAdmin)
                    <a href="{{ route('recursos.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Subir Primer Recurso
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- JavaScript para eliminación de recursos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar formularios de eliminación
    const deleteForms = document.querySelectorAll('.delete-recurso-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const recursoName = this.dataset.recursoName || 'este recurso';
            const form = this;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Estás seguro de que deseas eliminar el recurso "${recursoName}"? Esta acción no se puede deshacer.`,
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

