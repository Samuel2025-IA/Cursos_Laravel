@extends('layouts.dashboard')

@section('title', 'Subir Recurso - Diócesis de Apartadó')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Subir Nuevo Recurso</h1>
        <p class="text-gray-600">Comparte archivos PDF o Word para que los usuarios puedan descargarlos</p>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Encabezado del Formulario -->
        <div class="bg-[#2f9f37] h-16"></div>
        
        <form method="POST" action="{{ route('recursos.store') }}" enctype="multipart/form-data" class="px-6 py-8">
            @csrf

            <!-- Nombre del Recurso -->
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Recurso <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       required
                       value="{{ old('nombre') }}"
                       class="w-full px-4 py-3 border-2 @error('nombre') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-all duration-200"
                       placeholder="Ej: Guía de Teología Básica">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción (Opcional)
                </label>
                <textarea id="descripcion" 
                          name="descripcion" 
                          rows="4"
                          class="w-full px-4 py-3 border-2 @error('descripcion') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37] transition-all duration-200 resize-none"
                          placeholder="Describe brevemente el contenido del recurso...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Archivo -->
            <div class="mb-6">
                <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                    Archivo (PDF o Word) <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#2f9f37] transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-[#2f9f37] hover:text-[#27842f] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#2f9f37]">
                                <span>Selecciona un archivo</span>
                                <input id="archivo" 
                                       name="archivo" 
                                       type="file" 
                                       accept=".pdf,.doc,.docx"
                                       required
                                       class="sr-only"
                                       onchange="updateFileLabel(this)">
                            </label>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs text-gray-500" id="file-info">
                            PDF, DOC o DOCX (máximo 10MB)
                        </p>
                        <p class="text-xs text-red-500 mt-2" id="file-name" style="display: none;"></p>
                    </div>
                </div>
                @error('archivo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-[#2f9f37] hover:bg-[#27842f] text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Subir Recurso
                    </span>
                </button>
                
                <a href="{{ route('recursos.index') }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all duration-200 text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileLabel(input) {
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
        const maxSize = 10; // MB
        
        if (file.size > maxSize * 1024 * 1024) {
            fileName.textContent = 'El archivo es demasiado grande. Máximo 10MB.';
            fileName.style.display = 'block';
            fileName.classList.remove('text-gray-500');
            fileName.classList.add('text-red-500');
            input.value = '';
            fileInfo.textContent = 'PDF, DOC o DOCX (máximo 10MB)';
        } else {
            fileName.textContent = `Archivo seleccionado: ${file.name} (${fileSize} MB)`;
            fileName.style.display = 'block';
            fileName.classList.remove('text-red-500');
            fileName.classList.add('text-gray-500');
            fileInfo.textContent = `PDF, DOC o DOCX (máximo 10MB) - ${fileSize} MB seleccionado`;
        }
    } else {
        fileName.style.display = 'none';
        fileInfo.textContent = 'PDF, DOC o DOCX (máximo 10MB)';
    }
}
</script>
@endsection

