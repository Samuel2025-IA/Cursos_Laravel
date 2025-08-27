/**
 * Sistema Simple de Alertas Flash
 * Versión mejorada para el sistema de mensajes
 */

// Función simple para mostrar alertas
function showSimpleFlashAlert() {
    // Buscar meta tags
    const flashMessage = document.querySelector('meta[name="flash-message"]');
    const flashToken = document.querySelector('meta[name="flash-token"]');
    
    if (flashMessage && flashToken) {
        const message = flashMessage.getAttribute('content');
        const token = flashToken.getAttribute('content');
        
        // Verificar localStorage
        const processedTokens = JSON.parse(localStorage.getItem('simpleFlashTokens') || '[]');
        
        if (!processedTokens.includes(token)) {
            // Mostrar alerta
            if (typeof Swal !== 'undefined') {
                // Determinar el título y botón basado en el mensaje
                let title = '¡Registro Exitoso!';
                let confirmButtonText = '¡Perfecto!';
                
                if (message.includes('contraseña') && message.includes('cambiada')) {
                    title = '¡Contraseña Cambiada!';
                    confirmButtonText = 'Aceptar';
                } else if (message.includes('enlace de recuperación')) {
                    title = '¡Enlace Enviado!';
                    confirmButtonText = 'Entendido';
                }
                
                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: message,
                    confirmButtonColor: '#059669',
                    confirmButtonText: confirmButtonText,
                    background: '#ffffff',
                    iconColor: '#059669',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            } else {
                // Fallback a alert nativo si SweetAlert2 no está disponible
                console.warn('SweetAlert2 no está disponible, usando alert nativo');
                alert(message);
            }
            
            // Marcar como procesado
            processedTokens.push(token);
            localStorage.setItem('simpleFlashTokens', JSON.stringify(processedTokens));
        }
        
        // Limpiar meta tags
        flashMessage.remove();
        flashToken.remove();
    }
}

// Función para limpiar tokens
window.clearSimpleFlashTokens = function() {
    localStorage.removeItem('simpleFlashTokens');
    console.log('Tokens de simple flash limpiados');
};

// Función para forzar mostrar alerta (debugging)
window.forceShowFlash = function() {
    showSimpleFlashAlert();
};

// Función para verificar si SweetAlert2 está disponible
function checkSweetAlert2() {
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 no está disponible. Esperando...');
        return false;
    }
    return true;
}

// Función principal que se ejecuta cuando todo esté listo
function initializeFlashSystem() {
    // Esperar un poco más para asegurar que SweetAlert2 esté cargado
    setTimeout(() => {
        if (checkSweetAlert2()) {
            showSimpleFlashAlert();
        } else {
            // Si aún no está disponible, mostrar con alert nativo
            showSimpleFlashAlert();
        }
    }, 1000);
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM listo, inicializando sistema de flash...');
    initializeFlashSystem();
});

// También ejecutar cuando la página termine de cargar
window.addEventListener('load', () => {
    console.log('Página cargada, verificando sistema de flash...');
    initializeFlashSystem();
});

// Ejecutar inmediatamente si ya está listo
if (document.readyState === 'loading') {
    // El DOM aún se está cargando
    console.log('DOM cargando, esperando...');
} else {
    // El DOM ya está listo
    console.log('DOM ya listo, ejecutando inmediatamente...');
    initializeFlashSystem();
}

