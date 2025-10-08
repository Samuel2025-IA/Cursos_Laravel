/**
 * Script para el menú hamburguesa de la página welcome
 * Maneja la funcionalidad del menú móvil
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando menú hamburguesa...');
    
    const menuToggle = document.getElementById('menuToggle');
    const headerNav = document.getElementById('headerNav');
    const navOverlay = document.getElementById('navOverlay');
    
    if (!menuToggle || !headerNav || !navOverlay) {
        console.error('Elementos del menú no encontrados:', {
            menuToggle: !!menuToggle,
            headerNav: !!headerNav,
            navOverlay: !!navOverlay
        });
        return;
    }
    
    console.log('Elementos del menú encontrados correctamente');
    
    // Función para verificar si debe mostrar menú móvil
    function shouldShowMobileMenu() {
        return window.innerWidth <= 900;
    }
    
    // Función para cerrar menú
    function closeMenu() {
        menuToggle.classList.remove('active');
        headerNav.classList.remove('active');
        navOverlay.classList.remove('active');
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
        
        const isActive = headerNav.classList.contains('active');
        
        if (isActive) {
            closeMenu();
        } else {
            menuToggle.classList.add('active');
            headerNav.classList.add('active');
            navOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            console.log('Menú abierto');
        }
    }
    
    // Mostrar/ocultar botón hamburguesa
    function updateMenuVisibility() {
        const shouldShow = shouldShowMobileMenu();
        console.log('Actualizando visibilidad - Debe mostrar:', shouldShow);
        
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
    
    // Event listeners
    menuToggle.addEventListener('click', toggleMenu);
    
    navOverlay.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closeMenu();
    });
    
    // Cerrar al hacer click en enlaces
    const navLinks = headerNav.querySelectorAll('.welcome-nav-link');
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
    console.log('Menú hamburguesa inicializado correctamente');
});
