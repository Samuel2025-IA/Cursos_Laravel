/**
 * Scripts para la navegación (navigation.blade.php)
 * Maneja funcionalidades del menú de navegación y logout
 */

// ===========================================
// FUNCIÓN DE CONFIRMACIÓN DE LOGOUT
// ===========================================

/**
 * Confirma el logout del usuario con SweetAlert2
 */
window.confirmLogout = function() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: '¿Estás seguro de que quieres cerrar tu sesión?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Cerrar sesión',
            cancelButtonText: 'Cancelar',
            background: '#ffffff',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Marcar que se está haciendo logout real
                localStorage.setItem('legitimate_logout', 'true');
                
                // Intentar con el formulario de escritorio primero, luego el móvil
                const desktopForm = document.getElementById('logout-form');
                const mobileForm = document.getElementById('logout-form-mobile');
                
                if (desktopForm) {
                    desktopForm.submit();
                } else if (mobileForm) {
                    mobileForm.submit();
                }
            }
        });
    } else {
        // Fallback: confirmación nativa del navegador
        if (confirm('¿Estás seguro de que quieres cerrar tu sesión?')) {
            // Marcar que se está haciendo logout real
            localStorage.setItem('legitimate_logout', 'true');
            
            const desktopForm = document.getElementById('logout-form');
            const mobileForm = document.getElementById('logout-form-mobile');
            
            if (desktopForm) {
                desktopForm.submit();
            } else if (mobileForm) {
                mobileForm.submit();
            }
        }
    }
};

// ===========================================
// FUNCIONES DE NAVEGACIÓN RESPONSIVA
// ===========================================

/**
 * Maneja el estado del menú móvil
 */
function toggleMobileMenu() {
    const mobileMenu = document.querySelector('[x-data]');
    if (mobileMenu && mobileMenu._x_dataStack) {
        const data = mobileMenu._x_dataStack[0];
        if (data && typeof data.open !== 'undefined') {
            data.open = !data.open;
        }
    }
}

/**
 * Cierra el menú móvil
 */
function closeMobileMenu() {
    const mobileMenu = document.querySelector('[x-data]');
    if (mobileMenu && mobileMenu._x_dataStack) {
        const data = mobileMenu._x_dataStack[0];
        if (data && typeof data.open !== 'undefined') {
            data.open = false;
        }
    }
}

// ===========================================
// MANEJO DE EVENTOS
// ===========================================

/**
 * Configura los event listeners para la navegación
 */
function setupNavigationEvents() {
    // Cerrar menú móvil al hacer clic en un enlace
    const mobileLinks = document.querySelectorAll('.sm\\:hidden a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });

    // Cerrar menú móvil al hacer clic fuera
    document.addEventListener('click', function(event) {
        const nav = document.querySelector('nav');
        const mobileMenu = document.querySelector('.sm\\:hidden');
        
        if (nav && mobileMenu && !nav.contains(event.target)) {
            closeMobileMenu();
        }
    });

    // Cerrar menú móvil con tecla Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMobileMenu();
        }
    });
}

// ===========================================
// FUNCIONES DE UTILIDAD
// ===========================================

/**
 * Verifica si el usuario está en una ruta específica
 */
function isCurrentRoute(routeName) {
    return window.location.pathname === routeName;
}

/**
 * Obtiene el nombre de usuario actual
 */
function getCurrentUser() {
    // Esta función puede ser expandida para obtener datos del usuario
    return {
        name: document.querySelector('[data-user-name]')?.textContent || 'Usuario',
        email: document.querySelector('[data-user-email]')?.textContent || 'usuario@ejemplo.com'
    };
}

// ===========================================
// INICIALIZACIÓN
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Scripts de navegación cargados');
    
    // Configurar eventos de navegación
    setupNavigationEvents();
});

// ===========================================
// EXPORTAR FUNCIONES
// ===========================================
window.Navigation = {
    confirmLogout: confirmLogout,
    toggleMobileMenu: toggleMobileMenu,
    closeMobileMenu: closeMobileMenu,
    isCurrentRoute: isCurrentRoute,
    getCurrentUser: getCurrentUser
};

