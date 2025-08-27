/**
 * Sistema de Mensajes Flash Avanzado
 * Maneja mensajes flash con SweetAlert2 y localStorage
 */

class FlashMessageManager {
    constructor() {
        this.processedTokens = new Set();
        this.loadProcessedTokens();
        this.setupHistoryListener();
        this.processFlashMessages();
    }

    loadProcessedTokens() {
        try {
            const stored = localStorage.getItem('processedFlashTokens');
            if (stored) {
                this.processedTokens = new Set(JSON.parse(stored));
            }
        } catch (error) {
            // Si hay error, empezar con set vacío
            this.processedTokens = new Set();
        }
    }

    saveProcessedTokens() {
        try {
            localStorage.setItem('processedFlashTokens', JSON.stringify([...this.processedTokens]));
        } catch (error) {
            // Si hay error, no hacer nada
        }
    }

    processFlashMessages() {
        const flashMessage = document.querySelector('meta[name="flash-message"]');
        const flashToken = document.querySelector('meta[name="flash-token"]');
        
        if (flashMessage && flashToken) {
            const message = flashMessage.getAttribute('content');
            const token = flashToken.getAttribute('content');
            
            if (!this.processedTokens.has(token)) {
                this.showFlashMessage(message);
                this.processedTokens.add(token);
                this.saveProcessedTokens();
                
                // Limpiar meta tags
                flashMessage.remove();
                flashToken.remove();
            }
        }
    }

    showFlashMessage(message) {
        if (typeof Swal === 'undefined') {
            alert(message);
            return;
        }

        let icon = 'info';
        let title = 'Información';
        let confirmButtonText = 'Aceptar';

        // Determinar tipo de mensaje basado en el contenido
        if (message.includes('error') || message.includes('Error')) {
            icon = 'error';
            title = 'Error';
        } else if (message.includes('warning') || message.includes('Warning')) {
            icon = 'warning';
            title = 'Advertencia';
        } else if (message.includes('success') || message.includes('Success') || message.includes('exitosamente')) {
            icon = 'success';
            title = '¡Éxito!';
        }
        
        if (message.includes('enlace de recuperación')) {
            icon = 'success';
            title = '¡Enlace Enviado!';
            confirmButtonText = 'Entendido';
        } else if (message.includes('cambiada exitosamente')) {
            icon = 'success';
            title = '¡Éxito!';
            confirmButtonText = 'Aceptar';
        }

        // Mostrar la alerta
        Swal.fire({
            icon: icon,
            title: title,
            text: message,
            confirmButtonColor: '#059669',
            confirmButtonText: confirmButtonText,
            background: '#ffffff',
            iconColor: '#059669',
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }

    setupHistoryListener() {
        // Escuchar cambios en el historial del navegador
        window.addEventListener('popstate', () => {
            // Limpiar cualquier mensaje flash pendiente
            this.clearPendingMessages();
        });

        // Escuchar cuando se navega hacia adelante
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                // La página viene del cache del navegador
                this.clearPendingMessages();
            }
        });

        // Escuchar cuando se carga la página
        window.addEventListener('load', () => {
            // Limpiar mensajes flash pendientes al cargar
            this.clearPendingMessages();
        });

        // Escuchar cuando se hace clic en enlaces internos
        document.addEventListener('click', (event) => {
            if (event.target.tagName === 'A' && event.target.href.includes(window.location.origin)) {
                // Si es un enlace interno, limpiar mensajes flash
                setTimeout(() => {
                    this.clearPendingMessages();
                }, 100);
            }
        });
    }

    clearPendingMessages() {
        // Limpiar mensajes flash pendientes
        const flashMessage = document.querySelector('meta[name="flash-message"]');
        const flashToken = document.querySelector('meta[name="flash-token"]');
        
        if (flashMessage) {
            flashMessage.remove();
        }
        if (flashToken) {
            flashToken.remove();
        }
    }

    // Función para limpiar todos los tokens procesados
    clearAllProcessedTokens() {
        localStorage.removeItem('processedFlashTokens');
    }
}

// Función global para limpiar tokens
window.clearFlashTokens = function() {
    const manager = new FlashMessageManager();
    manager.clearAllProcessedTokens();
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new FlashMessageManager();
});


