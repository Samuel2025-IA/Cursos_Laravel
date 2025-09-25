// JavaScript para la vista de verificación de invitación

document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad del formulario de verificación
    const form = document.getElementById('verify-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Mostrar Loading Overlay - con verificación de disponibilidad
            try {
                if (typeof showLoading === 'function') {
                    showLoading('verify-loading', 'Verificando código...');
                } else {
                    // Fallback: mostrar loading manualmente
                    const overlay = document.getElementById('verify-loading');
                    if (overlay) {
                        const textElement = overlay.querySelector('h3');
                        if (textElement) {
                            textElement.textContent = 'Verificando código...';
                        }
                        overlay.style.display = 'flex';
                        setTimeout(() => {
                            overlay.classList.remove('opacity-0', 'pointer-events-none');
                        }, 10);
                    }
                }
            } catch (error) {
                console.log('No se pudo mostrar el loading overlay:', error);
                // Continuar con el envío del formulario sin bloquear
            }
        });
    }
    
    // Mostrar información de invitación solo si NO hay alertas de sesión
    setTimeout(function() {
        // Verificar si hay mensajes de sesión (error, info, success)
        const hasSessionAlert = document.querySelector('script[data-session-alert]') || 
                               window.location.search.includes('error') ||
                               document.body.getAttribute('data-has-session-message') === 'true';
        
        // Solo mostrar si no hay alertas de sesión y SweetAlert está disponible
        if (!hasSessionAlert && typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Información',
                text: 'Recuerda pedirle al administrador el código de invitación para poder registrarte en el sistema.',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Aceptar',
                background: '#ffffff',
                iconColor: '#2563eb',
                timer: 3500,
                timerProgressBar: true,
                allowOutsideClick: true,
                allowEscapeKey: true
            });
        }
    }, 1000); // Delay reducido para aparecer más rápido
});











