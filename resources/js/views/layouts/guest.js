/**
 * Scripts para el layout de invitados (guest.blade.php)
 * Maneja alertas y funcionalidades específicas para usuarios no autenticados
 */

// ===========================================
// CONFIGURACIÓN DE SWEETALERT2
// ===========================================

// Configurar Toast para notificaciones
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// ===========================================
// FUNCIONES GLOBALES DE ALERTAS
// ===========================================

/**
 * Función global para alertas de información
 */
window.showInfo = function(message) {
    Swal.fire({
        icon: 'info',
        title: 'Información',
        text: message,
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        iconColor: '#2563eb',
        timer: 6000,
        timerProgressBar: true
    });
};

/**
 * Función global para alertas de error
 */
window.showError = function(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        iconColor: '#dc2626',
        timer: 5000,
        timerProgressBar: true
    });
};

/**
 * Función global para alertas de éxito
 */
window.showSuccess = function(message) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: message,
        confirmButtonColor: '#059669',
        confirmButtonText: '¡Perfecto!',
        background: '#ffffff',
        iconColor: '#059669',
        timer: 4000,
        timerProgressBar: true
    });
};

/**
 * Función global para alertas de error (versión alternativa)
 */
window.showErrorAlert = function(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: message,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Aceptar',
            background: '#ffffff',
            iconColor: '#dc2626',
            allowOutsideClick: true,
            allowEscapeKey: true
        });
    } else {
        alert('ERROR: ' + message);
    }
};

// ===========================================
// MANEJO DE ALERTAS DE SESIÓN
// ===========================================

/**
 * Muestra alertas desde sesiones de Laravel - UNA SOLA A LA VEZ
 */
function showSessionAlerts() {
    // Error
    if (window.sessionError) {
        document.body.setAttribute('data-has-session-message', 'true');
        
        setTimeout(function() {
            console.log('🔴 Mostrando alerta de ERROR:', window.sessionError);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: window.sessionError,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#dc2626',
                    timer: 5000,
                    timerProgressBar: true
                });
            }
        }, 800);
    }
    // Info
    else if (window.sessionInfo) {
        document.body.setAttribute('data-has-session-message', 'true');
        
        setTimeout(function() {
            console.log('🔵 Mostrando alerta de INFO:', window.sessionInfo);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Información Importante',
                    text: window.sessionInfo,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Aceptar',
                    background: '#ffffff',
                    iconColor: '#2563eb',
                    showCloseButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }
        }, 800);
    }
    // Success
    else if (window.sessionSuccess) {
        document.body.setAttribute('data-has-session-message', 'true');
        
        setTimeout(function() {
            console.log('🟢 Mostrando alerta de SUCCESS:', window.sessionSuccess);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: window.sessionSuccess,
                    confirmButtonColor: '#059669',
                    confirmButtonText: '¡Perfecto!',
                    background: '#ffffff',
                    iconColor: '#059669',
                    timer: 4000,
                    timerProgressBar: true
                });
            }
        }, 800);
    }
    // Status (Password Reset)
    else if (window.sessionStatus) {
        document.body.setAttribute('data-has-session-message', 'true');
        
        setTimeout(function() {
            console.log('📧 Mostrando alerta de STATUS (Password Reset):', window.sessionStatus);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Enlace Enviado!',
                    text: window.sessionStatus,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: '¡Perfecto!',
                    background: '#ffffff',
                    iconColor: '#2563eb',
                    timer: 5000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            }
        }, 800);
    }
}

// ===========================================
// APLICAR ESTILOS DE REGISTRO
// ===========================================

/**
 * Aplica clases CSS para el formulario de registro
 */
function applyRegisterStyles() {
    if (window.location.pathname === '/register') {
        document.body.classList.add('register-page');
        const container = document.querySelector('.min-h-screen');
        if (container) {
            container.classList.add('register-container');
        }
    }
}

// ===========================================
// INICIALIZACIÓN
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Scripts de guest cargados');
    
    // Aplicar estilos de registro
    applyRegisterStyles();
    
    // Mostrar alertas de sesión
    showSessionAlerts();
});

// ===========================================
// EXPORTAR FUNCIONES
// ===========================================
window.GuestAlerts = {
    showInfo: showInfo,
    showError: showError,
    showSuccess: showSuccess,
    showErrorAlert: showErrorAlert
};









