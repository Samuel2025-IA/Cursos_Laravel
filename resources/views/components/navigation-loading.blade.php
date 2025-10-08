<!-- Overlay de carga para navegación - Mismo estilo que login -->
<div id="navigation-loading" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300 opacity-0 pointer-events-none"
     style="display: none;">
    
    <div class="bg-white rounded-lg p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
        <!-- Spinner animado - Mismo que login -->
        <div class="flex justify-center mb-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2f9f37]"></div>
        </div>
        
        <!-- Texto de carga -->
        <h3 class="text-lg font-semibold text-gray-800" id="loading-title">Cargando...</h3>
    </div>
</div>

<script>
// Función para mostrar loading de navegación - Mismo patrón que loading-overlay
function showNavigationLoading(title = 'Cargando...') {
    const overlay = document.getElementById('navigation-loading');
    if (overlay) {
        const textElement = overlay.querySelector('h3');
        if (textElement) {
            textElement.textContent = title;
        }
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
        }, 10);
    }
}

// Función para ocultar loading de navegación - Mismo patrón que loading-overlay
function hideNavigationLoading() {
    const overlay = document.getElementById('navigation-loading');
    if (overlay) {
        overlay.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

// Función para interceptar navegación
function interceptNavigation(link, title) {
    if (link) {
        // Remover listeners anteriores para evitar duplicados
        link.removeEventListener('click', link._navigationHandler);
        
        link._navigationHandler = function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            if (href && href !== '#') {
                console.log('Interceptando navegación a:', href);
                showNavigationLoading(title);
                
                // Navegar después de mostrar el loading
                setTimeout(() => {
                    window.location.href = href;
                }, 100);
            }
        };
        
        link.addEventListener('click', link._navigationHandler);
    }
}

// Función para inicializar interceptores
function initializeNavigationInterceptors() {
    console.log('Inicializando interceptores de navegación...');
    
    // Buscar enlaces con diferentes selectores
    const selectors = [
        'a[href*="admin.panel"]',
        'a[href*="profile.edit"]',
        '.responsive-nav-link[href*="admin.panel"]',
        '.responsive-nav-link[href*="profile.edit"]',
        'a[href*="/admin/panel"]',
        'a[href*="/profile"]'
    ];
    
    let totalLinks = 0;
    selectors.forEach(selector => {
        const links = document.querySelectorAll(selector);
        console.log(`Selector "${selector}" encontró ${links.length} enlaces`);
        totalLinks += links.length;
        
        links.forEach(link => {
            if (link.href.includes('admin.panel') || link.href.includes('/admin/panel')) {
                interceptNavigation(link, 'Cargando Panel Admin');
            } else if (link.href.includes('profile.edit') || link.href.includes('/profile')) {
                interceptNavigation(link, 'Cargando Perfil');
            }
        });
    });
    
    console.log(`Total de enlaces interceptados: ${totalLinks}`);
}

// Inicializar interceptores cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeNavigationInterceptors();
    
    // También intentar después de un pequeño delay por si hay contenido dinámico
    setTimeout(initializeNavigationInterceptors, 1000);
    
    // Ocultar loading cuando la página termine de cargar
    window.addEventListener('load', function() {
        setTimeout(hideNavigationLoading, 500);
    });
    
    // Ocultar loading si hay un error de carga
    window.addEventListener('error', function() {
        setTimeout(hideNavigationLoading, 1000);
    });
});

// Función global para usar desde otros scripts
window.showNavigationLoading = showNavigationLoading;
window.hideNavigationLoading = hideNavigationLoading;
</script>
