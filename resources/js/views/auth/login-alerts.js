/**
 * ========================================
 * LOGIN - MANEJO DE ALERTAS Y NOTIFICACIONES
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay mensajes de sesión
    checkSessionMessages();
});

/**
 * Verificar y mostrar mensajes de sesión
 */
function checkSessionMessages() {
    // Verificar mensaje de error
    const errorMeta = document.querySelector('meta[name="unauthorized-error"]');
    if (errorMeta) {
        showErrorAlert(errorMeta.getAttribute('content'));
    }
}

/**
 * Mostrar alerta de error
 */
function showErrorAlert(message) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    setTimeout(function() {
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
    }, 100);
}

/**
 * Mostrar alerta de éxito
 */
function showSuccessAlert(message) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    setTimeout(function() {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: message,
            confirmButtonColor: '#059669',
            confirmButtonText: 'Aceptar',
            background: '#ffffff',
            iconColor: '#059669',
            timer: 4000,
            timerProgressBar: true
        });
    }, 100);
}
