/**
 * ========================================
 * GUEST LAYOUT - MANEJO DE ALERTAS GLOBALES
 * ========================================
 */

// Las funciones de alerta están disponibles globalmente

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
        title: 'Información Importante',
        html: `
            <div style="text-align: left; line-height: 1.6;">
                <p style="margin-bottom: 15px; font-size: 16px;">${message}</p>
                <div style="background: #eff6ff; border-left: 4px solid #2563eb; padding: 12px; border-radius: 4px; margin-top: 15px;">
                    <p style="margin: 0; font-size: 14px; color: #1e40af; font-weight: 500;">
                        <strong>🔒 Protección de Datos:</strong> Tus datos personales serán tratados de forma segura y confidencial.
                    </p>
                </div>
            </div>
        `,
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Entiendo',
        background: '#ffffff',
        iconColor: '#2563eb',
        timer: 8000,
        timerProgressBar: true,
        showCloseButton: true,
        customClass: {
            popup: 'swal2-popup-custom',
            title: 'swal2-title-custom',
            content: 'swal2-content-custom'
        }
    });
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
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        iconColor: '#dc2626',
        timer: 5000,
        timerProgressBar: true
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
        title: 'Éxito',
        text: message,
        confirmButtonColor: '#059669',
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        iconColor: '#059669',
        timer: 4000,
        timerProgressBar: true
    });
}
