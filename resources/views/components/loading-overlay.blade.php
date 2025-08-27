@props(['id' => 'loading-overlay', 'text' => 'Cargando...', 'show' => false])

<div id="{{ $id }}" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300 {{ $show ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
     style="display: {{ $show ? 'flex' : 'none' }};">
    
    <div class="bg-white rounded-lg p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <!-- Spinner animado -->
        <div class="flex justify-center mb-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
        </div>
        
        <!-- Texto de carga -->
        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $text }}</h3>
        <p class="text-sm text-gray-600">Por favor espera...</p>
        
        <!-- Barra de progreso animada -->
        <div class="mt-4 bg-gray-200 rounded-full h-2 overflow-hidden">
            <div class="bg-emerald-600 h-2 rounded-full animate-pulse" style="animation: progress 2s ease-in-out infinite;"></div>
        </div>
    </div>
</div>

<style>
@keyframes progress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
}
</style>

<script>
// Función global para mostrar/ocultar loading
window.showLoading = function(id = 'loading-overlay', text = 'Cargando...') {
    const overlay = document.getElementById(id);
    if (overlay) {
        overlay.querySelector('h3').textContent = text;
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.remove('opacity-0', 'pointer-events-none'), 10);
    }
};

window.hideLoading = function(id = 'loading-overlay') {
    const overlay = document.getElementById(id);
    if (overlay) {
        overlay.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => overlay.style.display = 'none', 300);
    }
};

// Función para mostrar loading con SweetAlert2 (alternativa)
window.showSweetLoading = function(title = 'Cargando...', text = 'Por favor espera...') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
};
</script>



