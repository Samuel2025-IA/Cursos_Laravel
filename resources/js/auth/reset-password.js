// JavaScript para la vista de restablecimiento de contraseña

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reset-password-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Mostrar Loading Overlay
            showLoading('reset-password-loading', 'Restableciendo contraseña...');
            
            // Verificar errores de validación
            const errors = document.querySelectorAll('.text-red-600');
            if (errors.length > 0) {
                hideLoading('reset-password-loading');
            }
        });
    }
});

// Función para mostrar/ocultar contraseña
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
    const eyeSlashIcon = document.getElementById(`eye-slash-icon-${fieldId}`);
    
    // Debug: verificar que los elementos existen
    if (!field) {
        console.error(`Campo de contraseña no encontrado: ${fieldId}`);
        return;
    }
    if (!eyeIcon) {
        console.error(`Icono de ojo no encontrado: eye-icon-${fieldId}`);
        return;
    }
    if (!eyeSlashIcon) {
        console.error(`Icono de ojo tachado no encontrado: eye-slash-icon-${fieldId}`);
        return;
    }
    
    if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
    } else {
        field.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
    }
};
