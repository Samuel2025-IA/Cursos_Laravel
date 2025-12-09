// JavaScript para la vista de login

function resetLoginLoading() {
    const overlay = document.getElementById('login-loading');

    if (!overlay) {
        return;
    }

    overlay.style.display = 'none';
    overlay.classList.add('opacity-0', 'pointer-events-none');

    const textElement = overlay.querySelector('.loading-text');
    if (textElement) {
        textElement.textContent = 'Iniciando sesión...';
    }

    if (typeof hideLoading === 'function') {
        hideLoading('login-loading');
    }
}

function isBackOrForwardNavigation(event) {
    if (event && event.persisted) {
        return true;
    }

    if (window.performance) {
        const [navigationEntry] = window.performance.getEntriesByType?.('navigation') || [];
        if (navigationEntry && navigationEntry.type === 'back_forward') {
            return true;
        }

        const legacyNavigation = window.performance.navigation;
        if (legacyNavigation && legacyNavigation.type === legacyNavigation.TYPE_BACK_FORWARD) {
            return true;
        }
    }

    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    resetLoginLoading();

    const form = document.getElementById('login-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Mostrar Loading Overlay
            showLoading('login-loading', 'Iniciando sesión...');
        });
    }
    
    // Mostrar alertas de error con SweetAlert2
    // Verificar si hay errores en los meta tags
    const errorTypeMeta = document.querySelector('meta[name="error-type"]');
    const errorMessageMeta = document.querySelector('meta[name="error-message"]');
    
    if (errorTypeMeta && errorMessageMeta) {
        const errorType = errorTypeMeta.getAttribute('content');
        const errorMessage = errorMessageMeta.getAttribute('content');
        
        let icon = 'error';
        let title = 'Error';
        
        if (errorType === 'email') {
            icon = 'warning';
            title = 'Error de Email';
        } else if (errorType === 'password') {
            icon = 'error';
            title = 'Error de Contraseña';
        }
        
        Swal.fire({
            icon: icon,
            title: title,
            text: errorMessage,
            confirmButtonText: 'Entiendo',
            confirmButtonColor: '#dc2626',
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }
});

window.addEventListener('pageshow', () => {
    resetLoginLoading();
});

document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        resetLoginLoading();
    }
});

window.addEventListener('focus', resetLoginLoading);

// Función togglePassword() ahora está centralizada en toggle-password-unified.js
