@props(['id' => 'loading-overlay', 'text' => 'Cargando...', 'show' => false])

<div id="{{ $id }}" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300 {{ $show ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
     style="display: {{ $show ? 'flex' : 'none' }};">
    
    <div class="bg-white rounded-lg p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <!-- Spinner animado -->
        <div class="flex justify-center mb-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2f9f37]"></div>
        </div>
        
        <!-- Texto de carga -->
        <h3 class="text-lg font-semibold text-gray-800">{{ $text }}</h3>
    </div>
</div>

@vite('resources/js/views/components/loading-overlay.js')





