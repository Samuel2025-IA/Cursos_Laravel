@extends('layouts.dashboard')

@section('title', $curso->nombre . ' - Diócesis de Apartadó')

@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $curso->nombre }}</h1>
                @if($curso->descripcion)
                    <p class="text-gray-600">{{ $curso->descripcion }}</p>
                @endif
            </div>
            <div class="flex gap-3">
                @if($isAdmin)
                    <a href="{{ route('cursos.resultados', $curso) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Ver Resultados
                    </a>
                @endif
                <a href="{{ route('cursos.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>
        
        <!-- Información del curso -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Información del Curso
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($curso->instructor)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium">Instructor:</span>
                        <span class="ml-2">{{ $curso->instructor }}</span>
                    </div>
                @endif
                
                @if($curso->fecha_inicio)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Inicia:</span>
                        <span class="ml-2">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</span>
                    </div>
                @endif
                
                @if($curso->fecha_fin)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Termina:</span>
                        <span class="ml-2">{{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</span>
                    </div>
                @endif
                
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $curso->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario del Curso -->
    @if($curso->has_form && !empty($curso->form_fields))
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Formulario del Curso
            </h2>
            
            <div class="space-y-6">
                @foreach($curso->form_fields as $index => $field)
                    <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                        @if($field['type'] === 'text-short')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Campo de texto corto' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="text" disabled 
                                       class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                                       placeholder="Respuesta de texto corto">
                            </div>
                        @elseif($field['type'] === 'text-long')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Campo de texto largo' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <textarea disabled rows="4"
                                          class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                                          placeholder="Respuesta de texto largo"></textarea>
                            </div>
                        @elseif($field['type'] === 'multiple-choice')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Selección múltiple' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="space-y-2">
                                    @foreach($field['options'] ?? [] as $option)
                                        <label class="flex items-center text-gray-600">
                                            <input type="radio" disabled name="preview_radio_{{$index}}" class="mr-2">
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($field['type'] === 'checkbox')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Casillas de verificación' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="space-y-2">
                                    @foreach($field['options'] ?? [] as $option)
                                        <label class="flex items-center text-gray-600">
                                            <input type="checkbox" disabled class="mr-2">
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($field['type'] === 'dropdown')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Lista desplegable' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <select disabled class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-600">
                                    <option>Selecciona una opción</option>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif($field['type'] === 'date')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Campo de fecha' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="date" disabled 
                                       class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-600">
                            </div>
                        @elseif($field['type'] === 'rating')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Calificación' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= ($field['scale'] ?? 5); $i++)
                                        <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500">Escala de 1 a {{ $field['scale'] ?? 5 }} estrellas</p>
                            </div>
                        @elseif($field['type'] === 'image')
                            <div class="space-y-2">
                                @if(isset($field['question']) && $field['question'])
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $field['question'] }}
                                        @if(isset($field['required']) && $field['required'])
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                @endif
                                @if(isset($field['image_path']))
                                    @php
                                        $imageUrl = null;
                                        $imagePath = $field['image_path'] ?? null;
                                        if ($imagePath) {
                                            try {
                                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                                                    $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath);
                                                } elseif (file_exists(public_path('storage/' . $imagePath))) {
                                                    $imageUrl = asset('storage/' . $imagePath);
                                                }
                                            } catch (\Exception $e) {
                                                $imageUrl = null;
                                            }
                                        }
                                    @endphp
                                    <div class="mt-4">
                                        @if($imageUrl)
                                            <img src="{{ route('media.show', ['path' => $imagePath]) }}" 
                                                 alt="{{ $field['question'] ?? 'Imagen del curso' }}"
                                                 class="rounded-lg shadow-md border-2 border-gray-300 mx-auto"
                                                 style="width: {{ $field['image_width'] ?? '100%' }}; height: {{ $field['image_height'] ?? 'auto' }}; max-width: 100%; object-fit: contain;">
                                        @else
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                                                <p>No se pudo cargar la imagen.</p>
                                                @if(!empty($imagePath))
                                                    <p class="text-xs break-all mt-1">Ruta: {{ $imagePath }}</p>
                                                @endif
                                            </div>
                                        @endif
                                        @if(isset($field['image_name']))
                                            <p class="mt-2 text-sm text-gray-500 text-center">{{ $field['image_name'] }}</p>
                                        @endif
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p>No hay imagen disponible</p>
                                    </div>
                                @endif
                            </div>
                        @elseif($field['type'] === 'video')
                            <div class="space-y-2">
                                @if(isset($field['question']) && $field['question'])
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $field['question'] }}
                                        @if(isset($field['required']) && $field['required'])
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                @endif
                                @if(isset($field['video_url']))
                                    <div class="mt-4">
                                        @php
                                            // Extraer ID de YouTube
                                            $youtubeId = null;
                                            if (strpos($field['video_url'], 'youtube.com/watch?v=') !== false) {
                                                $youtubeId = explode('v=', $field['video_url'])[1];
                                                $youtubeId = explode('&', $youtubeId)[0];
                                            } elseif (strpos($field['video_url'], 'youtu.be/') !== false) {
                                                $youtubeId = explode('youtu.be/', $field['video_url'])[1];
                                                $youtubeId = explode('?', $youtubeId)[0];
                                            }
                                        @endphp
                                        @if($youtubeId)
                                            <div class="rounded-lg overflow-hidden shadow-md border-2 border-gray-300 mx-auto" 
                                                 style="width: {{ $field['video_width'] ?? '100%' }}; height: {{ $field['video_height'] ?? '450px' }}; max-width: 100%;">
                                                <iframe class="w-full h-full" 
                                                        src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                                        frameborder="0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen>
                                                </iframe>
                                            </div>
                                        @else
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                                                <p>URL de video no válida</p>
                                                <a href="{{ $field['video_url'] }}" target="_blank" class="text-blue-600 hover:underline mt-2 inline-block">Ver enlace</a>
                                            </div>
                                        @endif
                                    </div>
                                @elseif(isset($field['video_path']))
                                    <div class="mt-4 flex justify-center">
                                        <video controls class="rounded-lg shadow-md border-2 border-gray-300 mx-auto"
                                               style="width: {{ $field['video_width'] ?? '100%' }}; height: {{ $field['video_height'] ?? 'auto' }}; max-width: 100%;">
                                            <source src="{{ asset('storage/' . $field['video_path']) }}" type="video/mp4">
                                            Tu navegador no soporta la reproducción de videos.
                                        </video>
                                    </div>
                                    @if(isset($field['video_name']))
                                        <p class="mt-2 text-sm text-gray-500 text-center">{{ $field['video_name'] }}</p>
                                    @endif
                                @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <p>No hay video disponible</p>
                                    </div>
                                @endif
                            </div>
                        @elseif($field['type'] === 'grid')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Cuadrícula' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-300 rounded-lg">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-2 border border-gray-300"></th>
                                                @foreach($field['columns'] ?? [] as $column)
                                                    <th class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700">{{ $column }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($field['rows'] ?? [] as $row)
                                                <tr>
                                                    <td class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700">{{ $row }}</td>
                                                    @foreach($field['columns'] ?? [] as $column)
                                                        <td class="px-4 py-2 border border-gray-300 text-center">
                                                            <input type="radio" disabled name="grid_{{$index}}_{{$loop->parent->index}}" class="cursor-not-allowed">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-12 border border-gray-200">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Este curso no tiene formulario</h3>
                <p class="text-sm text-gray-600">Aún no se ha creado un formulario para este curso.</p>
            </div>
        </div>
    @endif
</div>
@endsection

