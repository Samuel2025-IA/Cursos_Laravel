// JavaScript para manejar alertas de acceso no autorizado

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay un mensaje de error de acceso no autorizado
    const errorMessage = document.querySelector('meta[name="unauthorized-error"]');
    
    if (errorMessage) {
        const message = errorMessage.getAttribute('content');
        
        // Mostrar alerta con SweetAlert2
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: message,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Iniciar Sesión',
                background: '#ffffff',
                iconColor: '#dc2626',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir al login si el usuario hace clic en "Iniciar Sesión"
                    window.location.href = '/login';
                }
            });
        } else {
            // Fallback si SweetAlert2 no está disponible
            alert(message);
            window.location.href = '/login';
        }
        
        // Limpiar el meta tag
        errorMessage.remove();
    }
});














