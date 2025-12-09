/**
 * Script para el menú hamburguesa del dashboard
 * Basado en la estructura de welcome que funciona correctamente
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando menú hamburguesa del dashboard...');
    
    const menuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.querySelector('.sidebar');
    const navOverlay = document.getElementById('sidebarOverlay');
    
    if (!menuToggle || !sidebar) {
        console.error('Elementos del menú no encontrados:', {
            menuToggle: !!menuToggle,
            sidebar: !!sidebar,
            navOverlay: !!navOverlay
        });
        return;
    }
    
    console.log('Elementos del menú encontrados correctamente');
    
    // Función para verificar si debe mostrar menú móvil
    function shouldShowMobileMenu() {
        return window.innerWidth <= 768;
    }
    
    // Función para cerrar menú
    function closeMenu() {
        if (menuToggle) {
            menuToggle.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Abrir menú de navegación');
        }
        if (sidebar) sidebar.classList.remove('open');
        if (navOverlay) {
            navOverlay.classList.remove('active');
            setTimeout(() => {
                if (navOverlay) navOverlay.style.display = 'none';
            }, 300);
        }
        document.body.style.overflow = '';
        console.log('Menú cerrado');
    }
    
    // Función para abrir/cerrar menú
    function toggleMenu(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        console.log('Toggle menú - Ancho pantalla:', window.innerWidth);
        
        if (!shouldShowMobileMenu()) {
            closeMenu();
            return;
        }
        
        const isActive = sidebar.classList.contains('open');
        
        if (isActive) {
            closeMenu();
        } else {
            openMenu();
        }
    }
    
    // Función para abrir menú
    function openMenu() {
        if (menuToggle) {
            menuToggle.classList.add('active');
            menuToggle.setAttribute('aria-expanded', 'true');
            menuToggle.setAttribute('aria-label', 'Cerrar menú de navegación');
        }
        if (sidebar) sidebar.classList.add('open');
        if (navOverlay) {
            navOverlay.style.display = 'block';
            navOverlay.classList.add('active');
        }
        document.body.style.overflow = 'hidden';
        console.log('Menú abierto');
    }
    
    // Mostrar/ocultar botón hamburguesa
    function updateMenuVisibility() {
        const shouldShow = shouldShowMobileMenu();
        console.log('Actualizando visibilidad - Debe mostrar:', shouldShow);
        
        if (menuToggle) {
            if (shouldShow) {
                menuToggle.style.display = 'flex';
                menuToggle.style.visibility = 'visible';
                menuToggle.style.opacity = '1';
            } else {
                menuToggle.style.display = 'none';
                menuToggle.style.visibility = 'hidden';
                menuToggle.style.opacity = '0';
                closeMenu();
            }
        }
    }
    
    // Event listeners
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleMenu);
    }
    
    if (navOverlay) {
        navOverlay.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeMenu();
        });
    }
    
    // Botón de cerrar del sidebar
    const sidebarCloseBtn = document.querySelector('.sidebar-close-mobile');
    if (sidebarCloseBtn) {
        sidebarCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeMenu();
        });
    }
    
    // Cerrar al hacer click en enlaces del sidebar
    const navLinks = sidebar.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (shouldShowMobileMenu()) {
                closeMenu();
            }
        });
    });
    
    // Redimensionar ventana
    window.addEventListener('resize', function() {
        updateMenuVisibility();
        if (!shouldShowMobileMenu()) {
            closeMenu();
        }
    });
    
    // Inicializar
    updateMenuVisibility();
    console.log('Menú hamburguesa del dashboard inicializado correctamente');
});

// Función global para compatibilidad
function toggleMobileSidebar() {
    const menuToggle = document.getElementById('mobileMenuToggle');
    if (menuToggle) {
        menuToggle.click();
    }
}

// Función para colapsar/expandir el sidebar en desktop
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content-with-sidebar');
    
    if (!sidebar || !mainContent) return;
    
    // Toggle de la clase collapsed
    sidebar.classList.toggle('collapsed');
    
    // Ajustar el margen del contenido principal
    if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '4rem'; // Ancho del sidebar colapsado
    } else {
        mainContent.style.marginLeft = '16rem'; // Ancho del sidebar expandido
    }
    
    console.log('Sidebar toggled:', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
}
