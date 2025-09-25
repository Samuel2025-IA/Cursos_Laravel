/**
 * ========================================
 * LOADING OVERLAY - FUNCIONALIDADES
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades del loading overlay
    initializeLoadingOverlay();
});

/**
 * Inicializar funcionalidades del loading overlay
 */
function initializeLoadingOverlay() {
    // Las funciones globales se definen aquí para ser accesibles desde cualquier parte
    console.log('Loading overlay inicializado');
}

/**
 * Función global para mostrar/ocultar loading
 */
window.showLoading = function(id = 'loading-overlay', text = 'Cargando...') {
    const overlay = document.getElementById(id);
    if (overlay) {
        const textElement = overlay.querySelector('h3');
        if (textElement) {
            textElement.textContent = text;
        }
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
        }, 10);
    }
};

/**
 * Función global para ocultar loading
 */
window.hideLoading = function(id = 'loading-overlay') {
    const overlay = document.getElementById(id);
    if (overlay) {
        overlay.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
};

/**
 * Función para mostrar loading con SweetAlert2 (alternativa)
 */
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
