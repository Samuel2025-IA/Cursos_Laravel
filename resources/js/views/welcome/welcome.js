// JavaScript para la vista de bienvenida - Solo funciones de utilidad
// El menú móvil se maneja completamente en el script inline del HTML

// Función para mostrar indicador de carga
const WELCOME_BUTTON_IDS = ['loginBtn', 'registerBtn'];

function resetLoadingState(button) {
    if (!button) return;

    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');

    if (btnText) {
        btnText.style.display = '';
    }

    if (btnLoading) {
        btnLoading.style.display = 'none';
    }

    button.style.pointerEvents = '';
    button.style.opacity = '';
}

function resetNavigationButtons() {
    WELCOME_BUTTON_IDS.forEach((id) => {
        const button = document.getElementById(id);
        resetLoadingState(button);
    });
}

window.showLoading = function(buttonId, loadingText) {
    const button = document.getElementById(buttonId);
    if (!button) return;
    
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');
    const loadingTextElement = button.querySelector('.loading-text');
    
    if (btnText && btnLoading && loadingTextElement) {
        // Ocultar texto del botón y mostrar carga
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        loadingTextElement.textContent = loadingText;
        
        // Deshabilitar el botón
        button.style.pointerEvents = 'none';
        button.style.opacity = '0.7';
        
        // Redirección real después de mostrar loading
        setTimeout(() => {
            // Permitir que el enlace funcione normalmente
            window.location.href = button.href;
        }, 800);
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

window.addEventListener('pageshow', (event) => {
    if (isBackOrForwardNavigation(event)) {
        resetNavigationButtons();
    }
});

document.addEventListener('DOMContentLoaded', resetNavigationButtons);