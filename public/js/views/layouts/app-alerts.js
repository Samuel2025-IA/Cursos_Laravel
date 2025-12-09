/**
 * ========================================
 * APP LAYOUT - MANEJO DE ALERTAS GLOBALES
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
    // Verificar mensaje de información
    const infoMeta = document.querySelector('meta[name="session-info"]');
    if (infoMeta) {
        const message = infoMeta.getAttribute('content');
        showInfoAlert(message);
        infoMeta.remove(); // Limpiar el meta tag
    }

    // Verificar mensaje de éxito
    const successMeta = document.querySelector('meta[name="session-success"]');
    if (successMeta) {
        const message = successMeta.getAttribute('content');
        showSuccessAlert(message);
        successMeta.remove(); // Limpiar el meta tag
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

    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Entiendo',
        background: '#ffffff',
        iconColor: '#dc2626'
    });
}

/**
 * Mostrar alerta de advertencia
 */
function showWarningAlert(message) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    Swal.fire({
        icon: 'warning',
        title: 'Advertencia',
        text: message,
        confirmButtonColor: '#d97706',
        confirmButtonText: 'Entiendo',
        background: '#ffffff',
        iconColor: '#d97706'
    });
}

/**
 * Mostrar alerta de información
 */
function showInfoAlert(message) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    Swal.fire({
        icon: 'info',
        title: 'Información',
        text: message,
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        iconColor: '#2563eb'
    });
}

/**
 * Mostrar alerta de éxito
 */
function showSuccessAlert(message) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    Swal.fire({
        icon: 'success',
        title: '¡Exito!',
        text: message,
        confirmButtonColor: '#059669',
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        iconColor: '#059669',
        timer: 4000,
        timerProgressBar: true
    });
}

// La función confirmLogout se maneja en navigation.js para evitar conflictos
