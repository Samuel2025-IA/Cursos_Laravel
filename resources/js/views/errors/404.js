/**
 * ========================================
 * VISTA 404 - FUNCIONALIDADES JAVASCRIPT
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista 404 cargada correctamente');
    
    // Inicializar funcionalidades de la vista 404
    initialize404View();
});

/**
 * Inicializar todas las funcionalidades de la vista 404
 */
function initialize404View() {
    // Configurar botón de inicio
    setupHomeButton();
    
    // Configurar animaciones
    setupAnimations();
    
    // Configurar eventos de teclado
    setupKeyboardEvents();
}

/**
 * Configurar el botón de inicio
 */
function setupHomeButton() {
    const homeButton = document.querySelector('.error-404-home-button');
    
    if (homeButton) {
        // Agregar efecto de click
        homeButton.addEventListener('click', function(e) {
            // Agregar clase de loading
            this.style.opacity = '0.7';
            this.style.transform = 'scale(0.98)';
            
            // Restaurar después de un breve momento
            setTimeout(() => {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            }, 150);
        });
        
        // Efecto hover mejorado
        homeButton.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.04)';
        });
        
        homeButton.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
}

/**
 * Configurar animaciones de entrada
 */
function setupAnimations() {
    const logo = document.querySelector('.error-404-logo');
    const title = document.querySelector('.error-404-title');
    const description = document.querySelector('.error-404-description');
    const button = document.querySelector('.error-404-home-button');
    
    // Animación de entrada escalonada
    if (logo) {
        logo.style.opacity = '0';
        logo.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            logo.style.transition = 'all 0.6s ease';
            logo.style.opacity = '1';
            logo.style.transform = 'translateY(0)';
        }, 100);
    }
    
    if (title) {
        title.style.opacity = '0';
        title.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            title.style.transition = 'all 0.6s ease';
            title.style.opacity = '1';
            title.style.transform = 'translateY(0)';
        }, 300);
    }
    
    if (description) {
        description.style.opacity = '0';
        description.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            description.style.transition = 'all 0.6s ease';
            description.style.opacity = '1';
            description.style.transform = 'translateY(0)';
        }, 500);
    }
    
    if (button) {
        button.style.opacity = '0';
        button.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            button.style.transition = 'all 0.6s ease';
            button.style.opacity = '1';
            button.style.transform = 'translateY(0)';
        }, 700);
    }
}

/**
 * Configurar eventos de teclado
 */
function setupKeyboardEvents() {
    document.addEventListener('keydown', function(e) {
        // Presionar Enter o Espacio para ir al inicio
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            const homeButton = document.querySelector('.error-404-home-button');
            if (homeButton) {
                homeButton.click();
            }
        }
        
        // Presionar Escape para recargar la página
        if (e.key === 'Escape') {
            e.preventDefault();
            window.location.reload();
        }
    });
}

/**
 * Función para mostrar información de debug (solo en desarrollo)
 */
function showDebugInfo() {
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('=== DEBUG INFO 404 ===');
        console.log('URL actual:', window.location.href);
        console.log('User Agent:', navigator.userAgent);
        console.log('Timestamp:', new Date().toISOString());
        console.log('========================');
    }
}

// Mostrar información de debug en desarrollo
showDebugInfo();
