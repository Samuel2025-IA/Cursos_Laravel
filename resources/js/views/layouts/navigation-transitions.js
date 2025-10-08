/**
 * ========================================
 * NAVIGATION TRANSITIONS - TRANSICIONES DE NAVEGACIÓN
 * ========================================
 * Maneja las alertas de carga para transiciones entre vistas
 */

// Función para mostrar loading de navegación
function showNavigationLoading(title = 'Cargando...', message = 'Por favor espera un momento') {
    const loadingOverlay = document.getElementById('navigation-loading');
    const loadingTitle = document.getElementById('loading-title');
    const loadingMessage = document.getElementById('loading-message');
    
    if (loadingOverlay && loadingTitle && loadingMessage) {
        loadingTitle.textContent = title;
        loadingMessage.textContent = message;
        loadingOverlay.classList.remove('hidden');
        
        // Agregar clase para animación de entrada
        setTimeout(() => {
            loadingOverlay.classList.add('opacity-100');
        }, 10);
    }
}

// Función para ocultar loading de navegación
function hideNavigationLoading() {
    const loadingOverlay = document.getElementById('navigation-loading');
    if (loadingOverlay) {
        loadingOverlay.classList.add('opacity-0');
        setTimeout(() => {
            loadingOverlay.classList.add('hidden');
            loadingOverlay.classList.remove('opacity-100', 'opacity-0');
        }, 300);
    }
}

// Función para interceptar navegación
function interceptNavigation(link, title, message) {
    if (link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            if (href && href !== '#') {
                showNavigationLoading(title, message);
                
                // Navegar después de mostrar el loading
                setTimeout(() => {
                    window.location.href = href;
                }, 100);
            }
        });
    }
}

// Función para limpiar modales atascados
function clearStuckModals() {
    // Limpiar cualquier modal de navegación que pueda estar atascado
    hideNavigationLoading();
    
    // Limpiar cualquier SweetAlert que pueda estar abierto
    if (typeof Swal !== 'undefined' && Swal.isVisible()) {
        Swal.close();
    }
    
    // Limpiar cualquier overlay de carga que pueda estar visible
    const stuckOverlays = document.querySelectorAll('[id*="loading"], [class*="loading"], [id*="overlay"]');
    stuckOverlays.forEach(overlay => {
        if (overlay && !overlay.classList.contains('hidden')) {
            overlay.classList.add('hidden', 'opacity-0');
        }
    });
}

// Inicializar interceptores cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Limpiar modales atascados al cargar la página
    clearStuckModals();
    // Enlaces del dropdown principal
    const panelLink = document.querySelector('a[href*="admin.panel"]');
    const profileLink = document.querySelector('a[href*="profile.edit"]');
    
    // Enlaces responsive
    const responsivePanelLink = document.querySelector('.responsive-nav-link[href*="admin.panel"]');
    const responsiveProfileLink = document.querySelector('.responsive-nav-link[href*="profile.edit"]');
    
    // Interceptar navegación
    interceptNavigation(panelLink, 'Cargando Panel Admin', 'Accediendo al panel de administración...');
    interceptNavigation(profileLink, 'Cargando Perfil', 'Accediendo a tu perfil...');
    interceptNavigation(responsivePanelLink, 'Cargando Panel Admin', 'Accediendo al panel de administración...');
    interceptNavigation(responsiveProfileLink, 'Cargando Perfil', 'Accediendo a tu perfil...');
    
    // Ocultar loading cuando la página termine de cargar
    window.addEventListener('load', function() {
        setTimeout(hideNavigationLoading, 500);
    });
    
    // Ocultar loading cuando se navega hacia atrás/adelante (importante para el problema)
    window.addEventListener('pageshow', function(event) {
        // Si la página se carga desde el cache (navegación hacia atrás/adelante)
        if (event.persisted) {
            setTimeout(hideNavigationLoading, 100);
        } else {
            // Carga normal
            setTimeout(hideNavigationLoading, 500);
        }
    });
    
    // Ocultar loading cuando se detecta navegación hacia atrás/adelante
    window.addEventListener('popstate', function() {
        setTimeout(hideNavigationLoading, 100);
    });
    
    // Ocultar loading si hay un error de carga
    window.addEventListener('error', function() {
        setTimeout(hideNavigationLoading, 1000);
    });
    
    // Ocultar loading al cambiar de página (evento beforeunload)
    window.addEventListener('beforeunload', function() {
        hideNavigationLoading();
    });
});

// Función global para usar desde otros scripts
window.showNavigationLoading = showNavigationLoading;
window.hideNavigationLoading = hideNavigationLoading;
window.clearStuckModals = clearStuckModals;
