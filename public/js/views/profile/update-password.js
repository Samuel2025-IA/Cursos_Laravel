/**
 * ========================================
 * UPDATE PASSWORD - FUNCIONALIDADES
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades del formulario de actualización de contraseña
    initializeUpdatePasswordForm();
    
    // Verificar si hay mensaje de éxito
    checkSuccessMessage();
});

/**
 * Inicializar funcionalidades del formulario de actualización de contraseña
 */
function initializeUpdatePasswordForm() {
    // Configurar botones de mostrar/ocultar contraseña
    setupPasswordToggles();
    
    // Configurar validación del formulario
    setupFormValidation();
}

/**
 * Configurar botones de mostrar/ocultar contraseña
 */
function setupPasswordToggles() {
    const toggleButtons = document.querySelectorAll('button[data-field]');
    
    toggleButtons.forEach(button => {
        const fieldId = button.getAttribute('data-field');
        button.addEventListener('click', () => togglePassword(fieldId));
    });
}

/**
 * Configurar validación del formulario
 */
function setupFormValidation() {
    // Buscar el formulario de cambio de contraseña
    const form = document.querySelector('form[action*="password"]') || 
                 document.querySelector('form[method="post"]');
    
    if (!form) {
        console.error('Formulario de cambio de contraseña no encontrado');
        return;
    }
    
    console.log('Formulario encontrado:', form);
    
    // Agregar atributos de validación HTML5 a los campos
    const currentPassword = form.querySelector('input[name="current_password"]');
    const newPassword = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="password_confirmation"]');
    
    if (currentPassword) {
        currentPassword.setAttribute('required', 'required');
        currentPassword.setAttribute('minlength', '1');
    }
    
    if (newPassword) {
        newPassword.setAttribute('required', 'required');
        newPassword.setAttribute('minlength', '8');
    }
    
    if (confirmPassword) {
        confirmPassword.setAttribute('required', 'required');
        confirmPassword.setAttribute('minlength', '8');
    }
    
    // Agregar evento de envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Permitir que el navegador haga la validación nativa
        if (!form.checkValidity()) {
            // El navegador mostrará automáticamente los mensajes de validación
            return false;
        }
        
        // Validar que las contraseñas coincidan
        if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
            // Mostrar alerta con SweetAlert2
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Contraseñas',
                    text: 'Las contraseñas no coinciden. Por favor, verifica que ambas contraseñas sean idénticas.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#dc2626',
                    background: '#ffffff',
                    iconColor: '#dc2626'
                });
            }
            return false;
        }
        
        // Si llegamos aquí, la validación pasó - enviar el formulario
        form.submit();
    });
}

/**
 * Verificar si hay mensaje de éxito
 */
function checkSuccessMessage() {
    const successMeta = document.querySelector('meta[name="password-success"]');
    
    if (successMeta) {
        const message = successMeta.getAttribute('content');
        
        // Mostrar alerta de éxito con SweetAlert2
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: message,
                confirmButtonText: '¡Perfecto!',
                confirmButtonColor: '#059669',
                background: '#ffffff',
                iconColor: '#059669',
                timer: 4000,
                timerProgressBar: true
            });
        }
        
        // Limpiar el meta tag
        successMeta.remove();
    }
}

/**
 * Función para alternar la visibilidad de la contraseña
 */
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(`eye-${fieldId}`);
    const eyeSlashIcon = document.getElementById(`eye-slash-${fieldId}`);
    
    if (!field || !eyeIcon || !eyeSlashIcon) {
        console.error(`Elementos no encontrados para el campo: ${fieldId}`);
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
