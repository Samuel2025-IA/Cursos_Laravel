@extends('layouts.dashboard')

@section('title', 'Iniciar Curso: ' . $curso->nombre . ' - Diócesis de Apartadó')

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
            <a href="{{ route('cursos.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Formulario del Curso -->
    @if($curso->has_form && !empty($curso->form_fields))
        <form method="POST" action="{{ route('cursos.completar', $curso) }}" class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            @csrf
            
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#2f9f37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Completa el Formulario
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
                                <input type="text" name="field_{{$index}}" 
                                       value="{{ old("field_{$index}") }}"
                                       @if(isset($field['required']) && $field['required']) required @endif
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                            </div>
                        @elseif($field['type'] === 'text-long')
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $field['question'] ?? 'Campo de texto largo' }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <textarea name="field_{{$index}}" rows="4"
                                          @if(isset($field['required']) && $field['required']) required @endif
                                          class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">{{ old("field_{$index}") }}</textarea>
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
                                    @php
                                        $oldValue = old("field_{$index}");
                                    @endphp
                                    @foreach($field['options'] ?? [] as $optionIndex => $option)
                                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input type="radio" name="field_{{$index}}" value="{{ $optionIndex }}"
                                                   @if($oldValue !== null && $oldValue == $optionIndex) checked @endif
                                                   @if(isset($field['required']) && $field['required']) required @endif
                                                   class="mr-2 text-[#2f9f37] focus:ring-[#2f9f37]">
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
                                    @foreach($field['options'] ?? [] as $optionIndex => $option)
                                        <label class="flex items-center text-gray-700 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input type="checkbox" name="field_{{$index}}[]" value="{{ $optionIndex }}"
                                                   @if(in_array($optionIndex, old("field_{$index}", []))) checked @endif
                                                   class="mr-2 text-[#2f9f37] focus:ring-[#2f9f37] rounded">
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
                                <select name="field_{{$index}}"
                                        @if(isset($field['required']) && $field['required']) required @endif
                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
                                    <option value="">Selecciona una opción</option>
                                    @foreach($field['options'] ?? [] as $optionIndex => $option)
                                        <option value="{{ $optionIndex }}" @if(old("field_{$index}") == $optionIndex) selected @endif>{{ $option }}</option>
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
                                <input type="date" name="field_{{$index}}"
                                       value="{{ old("field_{$index}") }}"
                                       @if(isset($field['required']) && $field['required']) required @endif
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]">
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
                                        <label class="cursor-pointer">
                                            <input type="radio" name="field_{{$index}}" value="{{ $i }}"
                                                   @if(old("field_{$index}") == $i) checked @endif
                                                   @if(isset($field['required']) && $field['required']) required @endif
                                                   class="hidden rating-input">
                                            <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 rating-star" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500">Escala de 1 a {{ $field['scale'] ?? 5 }} estrellas</p>
                            </div>
                        @elseif($field['type'] === 'image')
                            <div class="space-y-2">
                                @if(isset($field['question']) && $field['question'])
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $field['question'] }}
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
                                        @if($imageUrl && $imagePath)
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
                                    </div>
                                    @if(isset($field['image_name']))
                                        <p class="mt-2 text-sm text-gray-500 text-center">{{ $field['image_name'] }}</p>
                                    @endif
                                @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                                        <p>No hay imagen disponible</p>
                                    </div>
                                @endif
                            </div>
                        @elseif($field['type'] === 'video')
                            <div class="space-y-2">
                                @if(isset($field['question']) && $field['question'])
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $field['question'] }}
                                    </label>
                                @endif
                                @if(isset($field['video_url']))
                                    @php
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
                                    @endif
                                @elseif(isset($field['video_path']))
                                    <div class="mt-4 flex justify-center">
                                        <video controls class="rounded-lg shadow-md border-2 border-gray-300 mx-auto"
                                               style="width: {{ $field['video_width'] ?? '100%' }}; height: {{ $field['video_height'] ?? 'auto' }}; max-width: 100%;">
                                            <source src="{{ asset('storage/' . $field['video_path']) }}" type="video/mp4">
                                            Tu navegador no soporta la reproducción de videos.
                                        </video>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Botón de envío -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Completar Curso
                </button>
            </div>
        </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la experiencia de las estrellas de calificación
    document.querySelectorAll('.rating-input').forEach(function(input) {
        const star = input.nextElementSibling;
        const container = star.closest('.flex');
        
        input.addEventListener('change', function() {
            const value = parseInt(this.value);
            container.querySelectorAll('.rating-star').forEach(function(s, index) {
                if (index < value) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        // Hover effect
        container.querySelectorAll('.rating-star').forEach(function(s, index) {
            s.addEventListener('mouseenter', function() {
                const hoverValue = index + 1;
                container.querySelectorAll('.rating-star').forEach(function(star, i) {
                    if (i < hoverValue) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-300');
                    } else {
                        star.classList.remove('text-yellow-300');
                        star.classList.add('text-gray-300');
                    }
                });
            });
        });
        
        container.addEventListener('mouseleave', function() {
            const checked = container.querySelector('.rating-input:checked');
            if (checked) {
                const value = parseInt(checked.value);
                container.querySelectorAll('.rating-star').forEach(function(s, index) {
                    if (index < value) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            } else {
                container.querySelectorAll('.rating-star').forEach(function(s) {
                    s.classList.remove('text-yellow-300', 'text-yellow-400');
                    s.classList.add('text-gray-300');
                });
            }
        });
    });
});
</script>
@endsection




