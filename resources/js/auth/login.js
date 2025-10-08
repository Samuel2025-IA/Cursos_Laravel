// JavaScript para la vista de login

document.addEventListener('DOMContentLoaded', function() {
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

// Función togglePassword() ahora está centralizada en toggle-password-unified.js
