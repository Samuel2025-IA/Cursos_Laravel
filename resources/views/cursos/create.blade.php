@extends('layouts.dashboard')

@section('title', 'Crear Curso - Diócesis de Apartadó')

<style>
/* Estilos para campos de fecha estilo Google Forms */
input[type="date"] {
    background-color: white;
    color: #374151;
    font-size: 14px;
    line-height: 1.5;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}

input[type="date"]:focus {
    outline: none;
    border-color: #6b7280;
    box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.1);
}

/* Mejorar la apariencia del placeholder */
input[type="date"]:invalid {
    color: #9ca3af;
}

input[type="date"]:valid {
    color: #374151;
}

/* Estilos para opciones correctas */
.correct-answer-radio:checked + span,
.correct-answer-checkbox:checked + span {
    color: #2f9f37;
    font-weight: 600;
}

.correct-answer-radio:checked,
.correct-answer-checkbox:checked {
    accent-color: #2f9f37;
}

.flex.items-center.gap-3.has-correct-answer {
    background-color: rgba(47, 159, 55, 0.05);
    border-radius: 0.375rem;
    padding: 0.25rem;
}
</style>

@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Crear Nuevo Curso</h1>
        <p class="text-gray-600">Crea un formulario dinámico para tu curso con diferentes tipos de preguntas</p>
    </div>

    <!-- Mensajes de error generales -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Por favor, corrige los siguientes errores:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($errors->has('general'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{{ $errors->first('general') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form id="course-form" method="POST" action="{{ route('cursos.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Información Básica del Curso -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mr-0 lg:mr-24">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Información Básica del Curso
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Curso *</label>
                    <input type="text" id="nombre" name="nombre" required
                           value="{{ old('nombre') }}"
                           class="w-full px-4 py-3 border-2 @error('nombre') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-200"
                           placeholder="Ej: Introducción a la Teología">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio</label>
                    <div class="relative">
                        <input type="date" id="fecha_inicio" name="fecha_inicio"
                               value="{{ old('fecha_inicio') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border-2 @error('fecha_inicio') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-200 appearance-none">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    @error('fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Finalización</label>
                    <div class="relative">
                        <input type="date" id="fecha_fin" name="fecha_fin"
                               value="{{ old('fecha_fin') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border-2 @error('fecha_fin') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-200 appearance-none">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    @error('fecha_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción del Curso</label>
                <textarea id="descripcion" name="descripcion" rows="4"
                          class="w-full px-4 py-3 border-2 @error('descripcion') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-200"
                          placeholder="Describe el contenido y objetivos del curso...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Formulario estilo Google Forms -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mr-0 lg:mr-24">
            <!-- Encabezado del Formulario -->
            <div class="bg-[#2563EB] h-16"></div>
            <div class="px-6 py-8 border-b border-gray-200">
                <input type="text" 
                       id="form-title" 
                       placeholder="Formulario sin título" 
                       class="text-2xl font-normal text-gray-900 bg-transparent border-none outline-none w-full mb-2 placeholder-gray-400 focus:placeholder-gray-300"
                       value="{{ old('form_title') }}">
                <input type="text" 
                       id="form-description" 
                       placeholder="Descripción del formulario" 
                       class="text-sm text-gray-600 bg-transparent border-none outline-none w-full placeholder-gray-400 focus:placeholder-gray-300"
                       value="{{ old('form_description') }}">
                <input type="hidden" name="form_title" id="form-title-hidden">
                <input type="hidden" name="form_description" id="form-description-hidden">
            </div>

            <!-- Contenedor de Preguntas -->
            <div id="form-fields-container" class="space-y-0">
                <!-- Los campos se agregarán aquí dinámicamente -->
            </div>
        </div>

        <!-- Barra lateral flotante estilo Google Forms -->
        <div id="gf-toolbar" class="fixed right-4 lg:right-6 top-1/2 transform -translate-y-1/2 z-30 bg-white border border-gray-200 rounded-lg shadow-lg p-2 space-y-2 hidden lg:block">
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="text-short" title="Agregar pregunta">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="multiple-choice" title="Opción múltiple">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="checkbox" title="Casillas">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </button>
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="dropdown" title="Lista desplegable">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="image" title="Imagen">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="video" title="Video">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </button>
            <button type="button" class="gf-add-btn w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-colors" data-type="date" title="Fecha">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-4 mr-0 lg:mr-24">
            <a href="{{ route('cursos.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                Cancelar
            </a>
            <button type="submit" id="submit-btn"
                    class="px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                Crear Curso
            </button>
        </div>
    </form>
</div>

<!-- JavaScript para el constructor de formularios -->
<script>
// CDN para SortableJS
var s = document.createElement('script');
s.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';
document.head.appendChild(s);
// Estilos para elementos redimensionables (estilo simple tipo Google Forms)
const styleTag = document.createElement('style');
styleTag.innerHTML = `
.gf-resizable{ resize: both; overflow: auto; display: inline-block; max-width:100%; border: 1px dashed #9ca3af; border-radius: .5rem; padding: .25rem; background:white }
.gf-resizable:hover{ border-color:#6b7280 }
`;
document.head.appendChild(styleTag);
// Funciones globales para preview de imágenes y videos (deben estar fuera de DOMContentLoaded)
window.previewImage = function(input, previewId) {
    console.log('previewImage llamado', previewId, input);
    
    if (!input.files || !input.files[0]) {
        console.log('No hay archivo seleccionado');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        console.log('Archivo cargado, buscando preview:', previewId);
        const preview = document.getElementById(previewId);
        
        if (!preview) {
            console.error('No se encontró el elemento preview:', previewId);
            return;
        }
        
        const img = document.getElementById('image_preview_img_' + previewId.replace('image_preview_', ''));
        if (!img) {
            console.error('No se encontró la imagen en el preview');
            return;
        }
        
        img.src = e.target.result;
        
        // Aplicar dimensiones predeterminadas
        const counter = previewId.replace('image_preview_', '');
        setTimeout(function() {
            applyImageDimensions(parseInt(counter));
            initResizableImage(parseInt(counter));
        }, 100);
        
        preview.classList.remove('hidden');
        console.log('Preview mostrado exitosamente');
    };
    
    reader.onerror = function(error) {
        console.error('Error al leer el archivo:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la imagen. Por favor, intenta con otra imagen.',
                confirmButtonColor: '#dc2626'
            });
        }
    };
    
    reader.readAsDataURL(input.files[0]);
}

window.applyImageDimensions = function(counter) {
    const widthInput = document.getElementById('image_width_' + counter);
    const heightInput = document.getElementById('image_height_' + counter);
    const img = document.getElementById('image_preview_img_' + counter);
    
    if (!widthInput || !heightInput || !img) {
        console.log('Elementos no encontrados para aplicar dimensiones:', counter);
        return;
    }
    
    const width = widthInput.value || '100%';
    const height = heightInput.value || 'auto';
    
    img.style.width = width;
    img.style.height = height;
    img.style.maxWidth = '100%';
    img.style.objectFit = 'contain';
}

// Sincronizar cambios de tamaño del contenedor con los inputs (imagen)
function initResizableImage(counter){
    const box = document.getElementById('image_resizable_' + counter);
    const widthInput = document.getElementById('image_width_' + counter);
    const heightInput = document.getElementById('image_height_' + counter);
    if (!box || !widthInput || !heightInput) return;
    try {
        const ro = new ResizeObserver(entries => {
            for (const entry of entries){
                const w = Math.round(entry.contentRect.width);
                const h = Math.round(entry.contentRect.height);
                if (w > 0) widthInput.value = w + 'px';
                if (h > 0) heightInput.value = h + 'px';
            }
        });
        ro.observe(box);
        const initW = widthInput.value || '100%';
        const initH = heightInput.value || 'auto';
        box.style.width = initW;
        box.style.height = (initH !== 'auto') ? initH : '';
    } catch(err){ console.warn('ResizeObserver no disponible', err); }
}

window.removeImagePreview = function(inputId, previewId) {
    document.getElementById(inputId).value = '';
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.classList.add('hidden');
    }
    // Resetear dimensiones
    const counter = previewId.replace('image_preview_', '');
    const widthInput = document.getElementById('image_width_' + counter);
    const heightInput = document.getElementById('image_height_' + counter);
    if (widthInput) widthInput.value = '100%';
    if (heightInput) heightInput.value = 'auto';
}

window.previewVideo = function(input, previewId, urlInputId) {
    console.log('previewVideo llamado', previewId, input);
    
    if (!input.files || !input.files[0]) {
        console.log('No hay archivo de video seleccionado');
        return;
    }
    
    // Si se sube un archivo, limpiar la URL
    if (urlInputId) {
        const urlInput = document.getElementById(urlInputId);
        if (urlInput) urlInput.value = '';
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        console.log('Video cargado, buscando preview:', previewId);
        const preview = document.getElementById(previewId);
        
        if (!preview) {
            console.error('No se encontró el elemento preview del video:', previewId);
            return;
        }
        
        const video = document.getElementById('video_preview_element_' + previewId.replace('video_preview_', ''));
        if (!video) {
            console.error('No se encontró el video en el preview');
            return;
        }
        
        video.src = e.target.result;
        
        // Aplicar dimensiones predeterminadas
        const counter = previewId.replace('video_preview_', '');
        setTimeout(function() {
            applyVideoDimensions(parseInt(counter));
            initResizableVideo(parseInt(counter));
        }, 100);
        
        preview.classList.remove('hidden');
        console.log('Preview de video mostrado exitosamente');
    };
    
    reader.onerror = function(error) {
        console.error('Error al leer el archivo de video:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar el video. Por favor, intenta con otro video.',
                confirmButtonColor: '#dc2626'
            });
        }
    };
    
    reader.readAsDataURL(input.files[0]);
}

window.applyVideoDimensions = function(counter) {
    const widthInput = document.getElementById('video_width_' + counter);
    const heightInput = document.getElementById('video_height_' + counter);
    const video = document.getElementById('video_preview_element_' + counter);
    
    if (!widthInput || !heightInput || !video) {
        console.log('Elementos no encontrados para aplicar dimensiones del video:', counter);
        return;
    }
    
    const width = widthInput.value || '100%';
    const height = heightInput.value || 'auto';
    
    video.style.width = width;
    video.style.height = height;
    video.style.maxWidth = '100%';
}

// Sincronizar cambios de tamaño del contenedor con los inputs (video)
function initResizableVideo(counter){
    const box = document.getElementById('video_resizable_' + counter);
    const widthInput = document.getElementById('video_width_' + counter);
    const heightInput = document.getElementById('video_height_' + counter);
    if (!box || !widthInput || !heightInput) return;
    try {
        const ro = new ResizeObserver(entries => {
            for (const entry of entries){
                const w = Math.round(entry.contentRect.width);
                const h = Math.round(entry.contentRect.height);
                if (w > 0) widthInput.value = w + 'px';
                if (h > 0) heightInput.value = h + 'px';
            }
        });
        ro.observe(box);
        const initW = widthInput.value || '100%';
        const initH = heightInput.value || 'auto';
        box.style.width = initW;
        box.style.height = (initH !== 'auto') ? initH : '';
    } catch(err){ console.warn('ResizeObserver no disponible', err); }
}
window.removeVideoPreview = function(inputId, previewId, urlInputId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';
    
    if (urlInputId) {
        const urlInput = document.getElementById(urlInputId);
        if (urlInput) urlInput.value = '';
    }
    
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.classList.add('hidden');
    }
    
    // Resetear dimensiones
    const counter = previewId.replace('video_preview_', '');
    const widthInput = document.getElementById('video_width_' + counter);
    const heightInput = document.getElementById('video_height_' + counter);
    if (widthInput) widthInput.value = '100%';
    if (heightInput) heightInput.value = 'auto';
}

document.addEventListener('DOMContentLoaded', function() {
    let fieldCounter = 0;
    
    // Validación de fechas del lado del cliente
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    
    // Actualizar fecha mínima de fecha_fin cuando cambie fecha_inicio
    if (fechaInicio && fechaFin) {
        fechaInicio.addEventListener('change', function() {
            const fechaInicioValue = this.value;
            if (fechaInicioValue) {
                fechaFin.min = fechaInicioValue;
                // Si fecha_fin es anterior a fecha_inicio, actualizarla
                if (fechaFin.value && fechaFin.value < fechaInicioValue) {
                    fechaFin.value = fechaInicioValue;
                }
            } else {
                fechaFin.min = new Date().toISOString().split('T')[0];
            }
        });
        
        // Validar al cambiar fecha_fin
        fechaFin.addEventListener('change', function() {
            const fechaFinValue = this.value;
            const fechaInicioValue = fechaInicio.value;
            
            if (fechaFinValue && fechaInicioValue && fechaFinValue < fechaInicioValue) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha inválida',
                    text: 'La fecha de finalización debe ser posterior o igual a la fecha de inicio.',
                    confirmButtonColor: '#d97706',
                    confirmButtonText: 'Entiendo'
                });
                this.value = fechaInicioValue;
            }
        });
    }
    
    // Sincronizar título y descripción del formulario
    const formTitle = document.getElementById('form-title');
    const formDescription = document.getElementById('form-description');
    const formTitleHidden = document.getElementById('form-title-hidden');
    const formDescriptionHidden = document.getElementById('form-description-hidden');
    
    if (formTitle && formTitleHidden) {
        formTitle.addEventListener('input', function() {
            formTitleHidden.value = this.value;
        });
        formTitleHidden.value = formTitle.value || '';
    }
    
    if (formDescription && formDescriptionHidden) {
        formDescription.addEventListener('input', function() {
            formDescriptionHidden.value = this.value;
        });
        formDescriptionHidden.value = formDescription.value || '';
    }
    
    // Validación antes de enviar el formulario
    const courseForm = document.getElementById('course-form');
    const submitBtn = document.getElementById('submit-btn');
    
    if (courseForm && submitBtn) {
        courseForm.addEventListener('submit', function(e) {
            // Sincronizar título y descripción antes de enviar
            if (formTitle && formTitleHidden) {
                formTitleHidden.value = formTitle.value || '';
            }
            if (formDescription && formDescriptionHidden) {
                formDescriptionHidden.value = formDescription.value || '';
            }
            
            const formFieldsContainer = document.getElementById('form-fields-container');
            const formFields = formFieldsContainer.querySelectorAll('.form-field');
            
            if (formFields.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Campo requerido',
                    text: 'Debes agregar al menos un campo de pregunta para crear el curso.',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Entiendo'
                });
                return false;
            }
        });
    }

    // Toolbar flotante
    document.querySelectorAll('.gf-add-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            addFormField(this.dataset.type);
        });
    });
    
    // Manejar cambio de tipo de pregunta desde el select
    document.addEventListener('change', function(e) {
        if (e.target.matches('select[name*="[type]"]')) {
            const fieldContainer = e.target.closest('.form-field');
            const currentType = fieldContainer.dataset.fieldType;
            const newType = e.target.value;
            
            if (currentType !== newType) {
                const counter = fieldContainer.querySelector('input[name*="[type]"]').name.match(/\[(\d+)\]/)[1];
                const questionValue = fieldContainer.querySelector('input[name*="[question]"]')?.value || '';
                const requiredValue = fieldContainer.querySelector('input[name*="[required]"]')?.checked || false;
                
                // Guardar opciones si existen
                let options = [];
                if (currentType === 'multiple-choice' || currentType === 'checkbox' || currentType === 'dropdown') {
                    const optionInputs = fieldContainer.querySelectorAll('input[name*="[options][]"]');
                    options = Array.from(optionInputs).map(input => input.value).filter(v => v);
                }
                
                // Reemplazar el campo
                fieldCounter = parseInt(counter);
                const newFieldHTML = getFieldHTMLByType(newType, fieldCounter, questionValue, requiredValue, options);
                fieldContainer.outerHTML = newFieldHTML;
                saveDraftDebounced();
            }
        }
    });
    
    function getFieldHTMLByType(type, counter, question, required, options) {
        switch(type) {
            case 'text-short': return createShortTextField(counter).replace('placeholder="Pregunta sin título"', `placeholder="Pregunta sin título" value="${question}"`);
            case 'text-long': return createLongTextField(counter).replace('placeholder="Pregunta sin título"', `placeholder="Pregunta sin título" value="${question}"`);
            case 'multiple-choice': return createMultipleChoiceField(counter);
            case 'checkbox': return createCheckboxField(counter);
            case 'dropdown': return createDropdownField(counter);
            case 'date': return createDateField(counter);
            case 'rating': return createRatingField(counter);
            case 'image': return createImageField(counter);
            case 'video': return createVideoField(counter);
            default: return createShortTextField(counter);
        }
    }
    
    function addFormField(type) {
        fieldCounter++;
        const container = document.getElementById('form-fields-container');
        
        let fieldHTML = '';
        
        switch(type) {
            case 'text-short':
                fieldHTML = createShortTextField(fieldCounter);
                break;
            case 'text-long':
                fieldHTML = createLongTextField(fieldCounter);
                break;
            case 'multiple-choice':
                fieldHTML = createMultipleChoiceField(fieldCounter);
                break;
            case 'checkbox':
                fieldHTML = createCheckboxField(fieldCounter);
                break;
            case 'dropdown':
                fieldHTML = createDropdownField(fieldCounter);
                break;
            case 'grid':
                fieldHTML = createGridField(fieldCounter);
                break;
            case 'date':
                fieldHTML = createDateField(fieldCounter);
                break;
            case 'rating':
                fieldHTML = createRatingField(fieldCounter);
                break;
            case 'image':
                fieldHTML = createImageField(fieldCounter);
                break;
            case 'video':
                fieldHTML = createVideoField(fieldCounter);
                break;
        }
        
        container.insertAdjacentHTML('beforeend', fieldHTML);
        saveDraftDebounced();
    }
    
    
    
    function createShortTextField(counter) {
        return `
            <div class="form-field border-b border-gray-200 px-6 py-4 hover:bg-gray-50 transition-colors group" data-field-type="text-short">
                <div class="flex items-start gap-4">
                    <!-- Ícono de arrastrar -->
                    <div class="drag-handle cursor-move mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    
                    <!-- Contenido de la pregunta -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <input type="text" name="fields[${counter}][question]" 
                                   class="flex-1 text-sm font-normal text-gray-900 bg-transparent border-none outline-none placeholder-gray-400 focus:placeholder-gray-300"
                                   placeholder="Pregunta sin título">
                            <select name="fields[${counter}][type]" class="text-sm text-gray-600 border border-transparent hover:border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-gray-400 bg-transparent">
                                <option value="text-short" selected>Texto corto</option>
                                <option value="text-long">Párrafo</option>
                                <option value="multiple-choice">Opción múltiple</option>
                                <option value="checkbox">Casillas</option>
                                <option value="dropdown">Lista desplegable</option>
                                <option value="date">Fecha</option>
                                <option value="rating">Calificación</option>
                            </select>
                        </div>
                        <input type="text" 
                               class="w-full text-sm text-gray-500 border-b border-transparent hover:border-gray-300 focus:border-gray-400 focus:outline-none py-1"
                               placeholder="Respuesta corta de texto" 
                               disabled>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="duplicate-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Duplicar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button type="button" class="remove-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Opciones adicionales -->
                <div class="flex items-center gap-4 mt-3 ml-9">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="fields[${counter}][required]" class="rounded">
                        <span>Obligatoria</span>
                    </label>
                </div>
                <input type="hidden" name="fields[${counter}][type]" value="text-short">
            </div>
        `;
    }
    
    function createLongTextField(counter) {
        return `
            <div class="form-field border-b border-gray-200 px-6 py-4 hover:bg-gray-50 transition-colors group" data-field-type="text-long">
                <div class="flex items-start gap-4">
                    <div class="drag-handle cursor-move mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <input type="text" name="fields[${counter}][question]" 
                                   class="flex-1 text-sm font-normal text-gray-900 bg-transparent border-none outline-none placeholder-gray-400 focus:placeholder-gray-300"
                                   placeholder="Pregunta sin título">
                            <select name="fields[${counter}][type]" class="text-sm text-gray-600 border border-transparent hover:border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-gray-400 bg-transparent">
                                <option value="text-short">Texto corto</option>
                                <option value="text-long" selected>Párrafo</option>
                                <option value="multiple-choice">Opción múltiple</option>
                                <option value="checkbox">Casillas</option>
                                <option value="dropdown">Lista desplegable</option>
                                <option value="date">Fecha</option>
                                <option value="rating">Calificación</option>
                            </select>
                        </div>
                        <textarea class="w-full text-sm text-gray-500 border-b border-transparent hover:border-gray-300 focus:border-gray-400 focus:outline-none py-1 resize-none"
                                  placeholder="Respuesta larga de texto" 
                                  disabled rows="3"></textarea>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="duplicate-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Duplicar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button type="button" class="remove-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 ml-9">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="fields[${counter}][required]" class="rounded">
                        <span>Obligatoria</span>
                    </label>
                </div>
                <input type="hidden" name="fields[${counter}][type]" value="text-long">
            </div>
        `;
    }
    
    function createMultipleChoiceField(counter) {
        return `
            <div class="form-field border-b border-gray-200 px-6 py-4 hover:bg-gray-50 transition-colors group" data-field-type="multiple-choice">
                <div class="flex items-start gap-4">
                    <div class="drag-handle cursor-move mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <input type="text" name="fields[${counter}][question]" 
                                   class="flex-1 text-sm font-normal text-gray-900 bg-transparent border-none outline-none placeholder-gray-400 focus:placeholder-gray-300"
                                   placeholder="Pregunta sin título">
                            <select name="fields[${counter}][type]" class="text-sm text-gray-600 border border-transparent hover:border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-gray-400 bg-transparent">
                                <option value="text-short">Texto corto</option>
                                <option value="text-long">Párrafo</option>
                                <option value="multiple-choice" selected>Opción múltiple</option>
                                <option value="checkbox">Casillas</option>
                                <option value="dropdown">Lista desplegable</option>
                                <option value="date">Fecha</option>
                                <option value="rating">Calificación</option>
                            </select>
                        </div>
                        <div class="options-container space-y-2 ml-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <input type="text" name="fields[${counter}][options][]" 
                                       class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none border-b border-transparent hover:border-gray-300 focus:border-gray-400 py-1"
                                       placeholder="Opción 1">
                                <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer hover:text-[#2f9f37]">
                                    <input type="radio" name="fields[${counter}][correct_answer]" value="0" class="correct-answer-radio">
                                    <span>Correcta</span>
                                </label>
                                <button type="button" class="remove-option-btn text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <input type="text" name="fields[${counter}][options][]" 
                                       class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none border-b border-transparent hover:border-gray-300 focus:border-gray-400 py-1"
                                       placeholder="Opción 2">
                                <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer hover:text-[#2f9f37]">
                                    <input type="radio" name="fields[${counter}][correct_answer]" value="1" class="correct-answer-radio">
                                    <span>Correcta</span>
                                </label>
                                <button type="button" class="remove-option-btn text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="add-option-btn mt-2 ml-4 text-sm text-gray-600 hover:text-[#2f9f37]">
                            Agregar opción o agregar "Otros"
                        </button>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="duplicate-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Duplicar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button type="button" class="remove-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 ml-9">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="fields[${counter}][required]" class="rounded">
                        <span>Obligatoria</span>
                    </label>
                </div>
                <input type="hidden" name="fields[${counter}][type]" value="multiple-choice">
            </div>
        `;
    }
    
    function createCheckboxField(counter) {
        return `
            <div class="form-field border-b border-gray-200 px-6 py-4 hover:bg-gray-50 transition-colors group" data-field-type="checkbox">
                <div class="flex items-start gap-4">
                    <div class="drag-handle cursor-move mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <input type="text" name="fields[${counter}][question]" 
                                   class="flex-1 text-sm font-normal text-gray-900 bg-transparent border-none outline-none placeholder-gray-400 focus:placeholder-gray-300"
                                   placeholder="Pregunta sin título">
                            <select name="fields[${counter}][type]" class="text-sm text-gray-600 border border-transparent hover:border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-gray-400 bg-transparent">
                                <option value="text-short">Texto corto</option>
                                <option value="text-long">Párrafo</option>
                                <option value="multiple-choice">Opción múltiple</option>
                                <option value="checkbox" selected>Casillas</option>
                                <option value="dropdown">Lista desplegable</option>
                                <option value="date">Fecha</option>
                                <option value="rating">Calificación</option>
                            </select>
                        </div>
                        <div class="options-container space-y-2 ml-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <input type="text" name="fields[${counter}][options][]" 
                                       class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none border-b border-transparent hover:border-gray-300 focus:border-gray-400 py-1"
                                       placeholder="Opción 1">
                                <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer hover:text-[#2f9f37]">
                                    <input type="checkbox" name="fields[${counter}][correct_answers][]" value="0" class="correct-answer-checkbox">
                                    <span>Correcta</span>
                                </label>
                                <button type="button" class="remove-option-btn text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <input type="text" name="fields[${counter}][options][]" 
                                       class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none border-b border-transparent hover:border-gray-300 focus:border-gray-400 py-1"
                                       placeholder="Opción 2">
                                <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer hover:text-[#2f9f37]">
                                    <input type="checkbox" name="fields[${counter}][correct_answers][]" value="1" class="correct-answer-checkbox">
                                    <span>Correcta</span>
                                </label>
                                <button type="button" class="remove-option-btn text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="add-option-btn mt-2 ml-4 text-sm text-gray-600 hover:text-[#2f9f37]">
                            Agregar opción o agregar "Otros"
                        </button>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="duplicate-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Duplicar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button type="button" class="remove-field-btn p-1 rounded hover:bg-gray-200 text-gray-600" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 ml-9">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="fields[${counter}][required]" class="rounded">
                        <span>Obligatoria</span>
                    </label>
                </div>
                <input type="hidden" name="fields[${counter}][type]" value="checkbox">
            </div>
        `;
    }
    
    function createDropdownField(counter) {
        return `
            <div class="form-field bg-gray-50 p-4 rounded-lg border border-gray-200" data-field-type="dropdown">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Lista Desplegable</h4>
                    <button type="button" class="remove-field-btn text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                        <input type="text" name="fields[${counter}][question]" 
                               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                               placeholder="¿Cuál es tu pregunta?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opciones</label>
                        <div class="options-container space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="fields[${counter}][options][]" 
                                       class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                       placeholder="Opción 1">
                                <button type="button" class="remove-option-btn text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="text" name="fields[${counter}][options][]" 
                                       class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                       placeholder="Opción 2">
                                <button type="button" class="remove-option-btn text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="add-option-btn mt-2 text-sm text-[#2f9f37] hover:text-[#27842f]">
                            + Agregar opción
                        </button>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="fields[${counter}][required]" id="required_${counter}" class="mr-2">
                        <label for="required_${counter}" class="text-sm text-gray-700">Campo obligatorio</label>
                    </div>
                    <input type="hidden" name="fields[${counter}][type]" value="dropdown">
                </div>
            </div>
        `;
    }
    
    function createGridField(counter) {
        return `
            <div class="form-field bg-gray-50 p-4 rounded-lg border border-gray-200" data-field-type="grid">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Cuadrícula de Opciones</h4>
                    <button type="button" class="remove-field-btn text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                        <input type="text" name="fields[${counter}][question]" 
                               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                               placeholder="¿Cuál es tu pregunta?">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Filas</label>
                            <div class="rows-container space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="fields[${counter}][rows][]" 
                                           class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                           placeholder="Fila 1">
                                    <button type="button" class="remove-row-btn text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="fields[${counter}][rows][]" 
                                           class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                           placeholder="Fila 2">
                                    <button type="button" class="remove-row-btn text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="add-row-btn mt-2 text-sm text-[#2f9f37] hover:text-[#27842f]">
                                + Agregar fila
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Columnas</label>
                            <div class="columns-container space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="fields[${counter}][columns][]" 
                                           class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                           placeholder="Columna 1">
                                    <button type="button" class="remove-column-btn text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="fields[${counter}][columns][]" 
                                           class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                           placeholder="Columna 2">
                                    <button type="button" class="remove-column-btn text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="add-column-btn mt-2 text-sm text-[#2f9f37] hover:text-[#27842f]">
                                + Agregar columna
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="fields[${counter}][required]" id="required_${counter}" class="mr-2">
                        <label for="required_${counter}" class="text-sm text-gray-700">Campo obligatorio</label>
                    </div>
                    <input type="hidden" name="fields[${counter}][type]" value="grid">
                </div>
            </div>
        `;
    }
    
     function createDateField(counter) {
         return `
             <div class="form-field bg-gray-50 p-4 rounded-lg border border-gray-200" data-field-type="date">
                 <div class="flex items-center justify-between mb-3">
                     <h4 class="font-medium text-gray-900">Fecha</h4>
                     <button type="button" class="remove-field-btn text-red-600 hover:text-red-800">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                         </svg>
                     </button>
                 </div>
                 <div class="space-y-3">
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                         <input type="text" name="fields[${counter}][question]" 
                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                placeholder="¿Cuál es tu pregunta?">
                     </div>
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Campo de fecha</label>
                         <div class="relative">
                             <input type="date" name="fields[${counter}][date_value]" 
                                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500 appearance-none">
                             <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                 <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                 </svg>
                             </div>
                         </div>
                     </div>
                     <div class="flex items-center">
                         <input type="checkbox" name="fields[${counter}][required]" id="required_${counter}" class="mr-2">
                         <label for="required_${counter}" class="text-sm text-gray-700">Campo obligatorio</label>
                     </div>
                     <input type="hidden" name="fields[${counter}][type]" value="date">
                 </div>
             </div>
         `;
     }
    
    function createRatingField(counter) {
        return `
            <div class="form-field bg-gray-50 p-4 rounded-lg border border-gray-200" data-field-type="rating">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Calificación por Estrellas</h4>
                    <button type="button" class="remove-field-btn text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                        <input type="text" name="fields[${counter}][question]" 
                               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                               placeholder="¿Cuál es tu pregunta?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Escala</label>
                        <select name="fields[${counter}][scale]" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500">
                            <option value="5">1 a 5 estrellas</option>
                            <option value="10">1 a 10 estrellas</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="fields[${counter}][required]" id="required_${counter}" class="mr-2">
                        <label for="required_${counter}" class="text-sm text-gray-700">Campo obligatorio</label>
                    </div>
                    <input type="hidden" name="fields[${counter}][type]" value="rating">
                </div>
            </div>
        `;
    }
    
    function createImageField(counter) {
        return `
            <div class="form-field bg-gray-50 p-4 rounded-lg border border-gray-200" data-field-type="image">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Imagen</h4>
                    <button type="button" class="remove-field-btn text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título de la imagen (opcional)</label>
                        <input type="text" name="fields[${counter}][question]" 
                               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                               placeholder="Ej: Imagen del curso">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir imagen</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#2f9f37] transition-colors">
                            <div class="space-y-4 text-center w-full">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="image_${counter}" class="relative cursor-pointer bg-white rounded-md font-medium text-[#2f9f37] hover:text-[#27842f] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#2f9f37]">
                                        <span>Sube un archivo</span>
                                        <input id="image_${counter}" name="fields[${counter}][image]" type="file" accept="image/*" class="sr-only" onchange="previewImage(this, 'image_preview_${counter}')">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>

                                <!-- Preview dentro del recuadro -->
                                <div id="image_preview_${counter}" class="hidden space-y-4">
                                    <div class="flex justify-center">
                                        <div id="image_resizable_${counter}" class="gf-resizable w-full">
                                            <img id="image_preview_img_${counter}" src="" alt="Preview" 
                                                 class="rounded-lg shadow-md"
                                                 style="display:block; width:100%; height:auto;">
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                                        <h4 class="text-sm font-medium text-gray-700">Ajustar dimensiones</h4>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Ancho (px o %)</label>
                                                <input type="text" id="image_width_${counter}" 
                                                       name="fields[${counter}][image_width]"
                                                       value="100%"
                                                       placeholder="Ej: 800px o 100%"
                                                       class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Alto (px o auto)</label>
                                                <input type="text" id="image_height_${counter}" 
                                                       name="fields[${counter}][image_height]"
                                                       value="auto"
                                                       placeholder="Ej: 600px o auto"
                                                       class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                                            </div>
                                        </div>
                                        <button type="button" onclick="applyImageDimensions(${counter})" 
                                                class="w-full px-3 py-1.5 bg-[#2f9f37] hover:bg-[#27842f] text-white text-xs font-semibold rounded transition-colors">
                                            Aplicar dimensiones
                                        </button>
                                    </div>
                                    <button type="button" onclick="removeImagePreview('image_${counter}', 'image_preview_${counter}')" 
                                            class="w-full text-sm text-red-600 hover:text-red-800">
                                        Eliminar imagen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="fields[${counter}][required]" id="required_${counter}" class="mr-2">
                        <label for="required_${counter}" class="text-sm text-gray-700">Campo obligatorio</label>
                    </div>
                    <input type="hidden" name="fields[${counter}][type]" value="image">
                    <div class="pt-3">
                        <button type="button" class="add-question-below-btn text-sm text-[#2f9f37] hover:text-[#27842f] font-medium" data-target-counter="${counter}">
                            + Añadir pregunta debajo
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    function createVideoField(counter) {
        return `
            <div class="form-field bg-gray-50 p-4 rounded-lg border border-gray-200" data-field-type="video">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Video</h4>
                    <button type="button" class="remove-field-btn text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título del video (opcional)</label>
                        <input type="text" name="fields[${counter}][question]" 
                               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                               placeholder="Ej: Video introductorio">
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL de YouTube o pegar enlace</label>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                <input type="url" name="fields[${counter}][video_url]" 
                                       id="video_url_${counter}"
                                       class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                                       placeholder="Pega la URL de YouTube aquí">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">O sube un archivo de video</p>
                        </div>
                        <div>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#2f9f37] transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="video_${counter}" class="relative cursor-pointer bg-white rounded-md font-medium text-[#2f9f37] hover:text-[#27842f] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#2f9f37]">
                                            <span>Sube un archivo</span>
                                            <input id="video_${counter}" name="fields[${counter}][video]" type="file" accept="video/*" class="sr-only" onchange="previewVideo(this, 'video_preview_${counter}', 'video_url_${counter}')">
                                        </label>
                                        <p class="pl-1">o arrastra y suelta</p>
                                    </div>
                                    <p class="text-xs text-gray-500">MP4, MOV, AVI hasta 100MB</p>
                                    <div id="video_preview_${counter}" class="mt-4 hidden space-y-4">
                            <div class="flex justify-center">
                                <div id="video_resizable_${counter}" class="gf-resizable">
                                    <video id="video_preview_element_${counter}" src="" controls 
                                           class="rounded-lg shadow-md"
                                           style="display:block; width:100%; height:auto;"></video>
                                </div>
                            </div>
                                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                                            <h4 class="text-sm font-medium text-gray-700">Ajustar dimensiones</h4>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs text-gray-600 mb-1">Ancho (px o %)</label>
                                                    <input type="text" id="video_width_${counter}" 
                                                           name="fields[${counter}][video_width]"
                                                           value="100%"
                                                           placeholder="Ej: 800px o 100%"
                                                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-600 mb-1">Alto (px o auto)</label>
                                                    <input type="text" id="video_height_${counter}" 
                                                           name="fields[${counter}][video_height]"
                                                           value="auto"
                                                           placeholder="Ej: 450px o auto"
                                                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                                                </div>
                                            </div>
                                            <button type="button" onclick="applyVideoDimensions(${counter})" 
                                                    class="w-full px-3 py-1.5 bg-[#2f9f37] hover:bg-[#27842f] text-white text-xs font-semibold rounded transition-colors">
                                                Aplicar dimensiones
                                            </button>
                                        </div>
                                        <button type="button" onclick="removeVideoPreview('video_${counter}', 'video_preview_${counter}', 'video_url_${counter}')" 
                                                class="w-full text-sm text-red-600 hover:text-red-800">
                                            Eliminar video
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="fields[${counter}][required]" id="required_${counter}" class="mr-2">
                        <label for="required_${counter}" class="text-sm text-gray-700">Campo obligatorio</label>
                    </div>
                    <input type="hidden" name="fields[${counter}][type]" value="video">
                    <div class="pt-3">
                        <button type="button" class="add-question-below-btn text-sm text-[#2f9f37] hover:text-[#27842f] font-medium" data-target-counter="${counter}">
                            + Añadir pregunta debajo
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Validar URL de YouTube cuando se ingrese
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name*="[video_url]"]')) {
            const url = e.target.value.trim();
            if (url && !url.includes('youtube.com') && !url.includes('youtu.be')) {
                // Si tiene valor pero no es YouTube, solo mostrar advertencia
                // Permitimos otras URLs también
            }
        }
    });
    
    // Manejar cambios en opciones correctas para resaltar visualmente
    document.addEventListener('change', function(e) {
        if (e.target.matches('.correct-answer-radio, .correct-answer-checkbox')) {
            const optionRow = e.target.closest('.flex.items-center.gap-3');
            if (optionRow) {
                if (e.target.checked) {
                    optionRow.classList.add('has-correct-answer');
                } else {
                    optionRow.classList.remove('has-correct-answer');
                }
            }
            
            // Para radio buttons, desmarcar otros en el mismo grupo
            if (e.target.matches('.correct-answer-radio')) {
                const fieldRoot = e.target.closest('.form-field');
                const allRadios = fieldRoot.querySelectorAll('.correct-answer-radio');
                allRadios.forEach(radio => {
                    if (radio !== e.target) {
                        radio.checked = false;
                        const otherRow = radio.closest('.flex.items-center.gap-3');
                        if (otherRow) {
                            otherRow.classList.remove('has-correct-answer');
                        }
                    }
                });
            }
        }
    });
    
    // Aplicar estilos a opciones ya marcadas como correctas al cargar
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.correct-answer-radio:checked, .correct-answer-checkbox:checked').forEach(function(input) {
                const optionRow = input.closest('.flex.items-center.gap-3');
                if (optionRow) {
                    optionRow.classList.add('has-correct-answer');
                }
            });
        }, 100);
    });
    
    // Event listeners para botones dinámicos
    document.addEventListener('click', function(e) {
        // Eliminar campo
        if (e.target.closest('.remove-field-btn')) {
            e.target.closest('.form-field').remove();
            saveDraftDebounced();
        }
        
        // Agregar opción
        if (e.target.closest('.add-option-btn')) {
            const fieldRoot = e.target.closest('.form-field');
            const container = fieldRoot.querySelector('.options-container');
            const fieldCounter = fieldRoot.querySelector('input[name*="[type]"]').name.match(/\[(\d+)\]/)[1];
            const fieldType = fieldRoot.dataset.fieldType;
            
            // Contar opciones existentes para el índice
            const existingOptions = container.querySelectorAll('.flex.items-center.gap-3');
            const optionIndex = existingOptions.length;
            
            // Determinar el ícono según el tipo de campo
            let iconSVG = '';
            let correctAnswerInput = '';
            if (fieldType === 'multiple-choice') {
                iconSVG = '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                correctAnswerInput = `<label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer hover:text-[#2f9f37]">
                    <input type="radio" name="fields[${fieldCounter}][correct_answer]" value="${optionIndex}" class="correct-answer-radio">
                    <span>Correcta</span>
                </label>`;
            } else if (fieldType === 'checkbox') {
                iconSVG = '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>';
                correctAnswerInput = `<label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer hover:text-[#2f9f37]">
                    <input type="checkbox" name="fields[${fieldCounter}][correct_answers][]" value="${optionIndex}" class="correct-answer-checkbox">
                    <span>Correcta</span>
                </label>`;
            } else {
                iconSVG = '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
            }
            
            const optionHTML = `
                <div class="flex items-center gap-3">
                    ${iconSVG}
                    <input type="text" name="fields[${fieldCounter}][options][]" 
                           class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none border-b border-transparent hover:border-gray-300 focus:border-gray-400 py-1"
                           placeholder="Nueva opción">
                    ${correctAnswerInput}
                    <button type="button" class="remove-option-btn text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', optionHTML);
            saveDraftDebounced();
        }
        
        // Eliminar opción
        if (e.target.closest('.remove-option-btn')) {
            const optionRow = e.target.closest('.flex.items-center');
            if (optionRow) {
                const fieldRoot = optionRow.closest('.form-field');
                const container = fieldRoot.querySelector('.options-container');
                const fieldType = fieldRoot.dataset.fieldType;
                
                optionRow.remove();
                
                // Actualizar índices de las opciones correctas restantes
                if (fieldType === 'multiple-choice' || fieldType === 'checkbox') {
                    const remainingOptions = container.querySelectorAll('.flex.items-center.gap-3');
                    remainingOptions.forEach((option, index) => {
                        const correctInput = option.querySelector('.correct-answer-radio, .correct-answer-checkbox');
                        if (correctInput) {
                            correctInput.value = index;
                        }
                    });
                }
                
                saveDraftDebounced();
            }
        }
        
        // Agregar fila (para cuadrícula)
        if (e.target.closest('.add-row-btn')) {
            const fieldRoot = e.target.closest('.form-field');
            const container = fieldRoot.querySelector('.rows-container');
            const fieldCounter = fieldRoot.querySelector('input[name*="[type]"]').name.match(/\[(\d+)\]/)[1];
            const rowHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" name="fields[${fieldCounter}][rows][]" 
                           class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                           placeholder="Nueva fila">
                    <button type="button" class="remove-row-btn text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', rowHTML);
            saveDraftDebounced();
        }
        
        // Eliminar fila
        if (e.target.closest('.remove-row-btn')) {
            e.target.closest('.flex').remove();
            saveDraftDebounced();
        }
        
        // Agregar columna (para cuadrícula)
        if (e.target.closest('.add-column-btn')) {
            const fieldRoot = e.target.closest('.form-field');
            const container = fieldRoot.querySelector('.columns-container');
            const fieldCounter = fieldRoot.querySelector('input[name*="[type]"]').name.match(/\[(\d+)\]/)[1];
            const columnHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" name="fields[${fieldCounter}][columns][]" 
                           class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-gray-500"
                           placeholder="Nueva columna">
                    <button type="button" class="remove-column-btn text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', columnHTML);
            saveDraftDebounced();
        }
        
        // Eliminar columna
        if (e.target.closest('.remove-column-btn')) {
            e.target.closest('.flex').remove();
            saveDraftDebounced();
        }

        // Añadir pregunta debajo de media (imagen/video)
        if (e.target.closest('.add-question-below-btn')) {
            const mediaField = e.target.closest('.form-field');
            if (!mediaField) return;
            fieldCounter++;
            const questionHTML = createShortTextField(fieldCounter);
            mediaField.insertAdjacentHTML('afterend', questionHTML);
            // Desplazar a la nueva pregunta para editarla
            const newField = mediaField.nextElementSibling;
            if (newField) {
                const input = newField.querySelector('input[type="text"]');
                if (input) input.focus();
            }
            saveDraftDebounced();
        }
    });

    // Botones de duplicar campo
    document.addEventListener('click', function(e){
        if (e.target.closest('.duplicate-field-btn')){
            const f = e.target.closest('.form-field');
            if (!f) return;
            fieldCounter++;
            const clone = f.cloneNode(true);
            // actualizar índices simples en name="fields[xx]"
            clone.innerHTML = clone.innerHTML.replace(/fields\[(\d+)\]/g, `fields[${fieldCounter}]`);
            f.insertAdjacentElement('afterend', clone);
            
            // Actualizar índices de opciones correctas en el campo duplicado
            const fieldType = clone.dataset.fieldType;
            if (fieldType === 'multiple-choice' || fieldType === 'checkbox') {
                const container = clone.querySelector('.options-container');
                if (container) {
                    const options = container.querySelectorAll('.flex.items-center.gap-3');
                    options.forEach((option, index) => {
                        const correctInput = option.querySelector('.correct-answer-radio, .correct-answer-checkbox');
                        if (correctInput) {
                            correctInput.value = index;
                        }
                    });
                }
            }
            
            saveDraftDebounced();
        }
    });

    // Habilitar reordenamiento cuando SortableJS cargue
    s.onload = function(){
        new Sortable(document.getElementById('form-fields-container'), {
            animation: 150,
            handle: '.drag-handle',
            onEnd(){ saveDraftDebounced(); }
        });
    };

    // Autosave en localStorage
    const DRAFT_KEY = 'course_builder_draft_v1';
    const saveDraft = () => {
        const html = document.getElementById('form-fields-container').innerHTML;
        localStorage.setItem(DRAFT_KEY, html);
    };
    let tId; const saveDraftDebounced = () => { clearTimeout(tId); tId = setTimeout(saveDraft, 300); };

    // Restaurar borrador
    const draft = localStorage.getItem(DRAFT_KEY);
    if (draft) {
        document.getElementById('form-fields-container').innerHTML = draft;
        // calcular fieldCounter máximo restaurado
        const names = [...document.querySelectorAll('input[name*="fields["]')].map(i=>i.name);
        const nums = names.map(n=>{ const m = n.match(/fields\[(\d+)\]/); return m?parseInt(m[1],10):0; });
        fieldCounter = Math.max(0, ...nums);
    }
});
</script>
@endsection
