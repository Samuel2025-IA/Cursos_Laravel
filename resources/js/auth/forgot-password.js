
// JavaScript para la vista de recuperación de contraseña

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgot-password-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Mostrar Loading Overlay
            showLoading('forgot-password-loading', 'Enviando enlace de recuperación...');
            
            // Verificar errores de validación
            const errors = document.querySelectorAll('.text-red-600');
            if (errors.length > 0) {
                hideLoading('forgot-password-loading');
            }
        });
    }
});

