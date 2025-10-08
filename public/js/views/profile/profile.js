/**
 * ========================================
 * PROFILE MANAGEMENT - FUNCIONALIDADES
 * ========================================
 * Archivo principal para la gestión del perfil de usuario
 * Incluye: información del perfil, cambio de contraseña, validaciones
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Inicializando funcionalidades del perfil...');
    
    // Inicializar todas las funcionalidades del perfil
    initializeProfileFeatures();
    
    // Verificar mensajes de sesión
    checkSessionMessages();
});

/**
 * Inicializar todas las funcionalidades del perfil
 */
function initializeProfileFeatures() {
    // Configurar botones de mostrar/ocultar contraseña
    setupPasswordToggles();
    
    // Configurar validación del formulario de contraseña
    setupPasswordFormValidation();
    
    // Configurar animaciones y efectos visuales
    setupVisualEffects();
    
    console.log('✅ Funcionalidades del perfil inicializadas correctamente');
}

/**
 * Configurar botones de mostrar/ocultar contraseña
 */
function setupPasswordToggles() {
    const toggleButtons = document.querySelectorAll('button[data-field]');
    
    if (toggleButtons.length === 0) {
        console.log('ℹ️ No se encontraron botones de toggle de contraseña');
        return;
    }
    
    toggleButtons.forEach(button => {
        const fieldId = button.getAttribute('data-field');
        button.addEventListener('click', (e) => {
            e.preventDefault();
            togglePassword(fieldId);
        });
        
        // Agregar efecto hover
        button.addEventListener('mouseenter', () => {
            button.classList.add('text-gray-600');
        });
        
        button.addEventListener('mouseleave', () => {
            button.classList.remove('text-gray-600');
        });
    });
    
    console.log(`✅ Configurados ${toggleButtons.length} botones de toggle de contraseña`);
}

/**
 * Configurar validación del formulario de contraseña
 */
function setupPasswordFormValidation() {
    const form = document.querySelector('form[action*="password"]') || 
                 document.querySelector('form[method="post"]');
    
    if (!form) {
        console.log('ℹ️ No se encontró formulario de cambio de contraseña');
        return;
    }
    
    // Configurar atributos de validación HTML5
    const currentPassword = form.querySelector('input[name="current_password"]');
    const newPassword = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="password_confirmation"]');
    
    // Configurar validaciones
    if (currentPassword) {
        currentPassword.setAttribute('required', 'required');
        currentPassword.setAttribute('minlength', '1');
        currentPassword.setAttribute('placeholder', 'Ingresa tu contraseña actual');
    }
    
    if (newPassword) {
        newPassword.setAttribute('required', 'required');
        newPassword.setAttribute('minlength', '8');
        newPassword.setAttribute('placeholder', 'Mínimo 8 caracteres');
    }
    
    if (confirmPassword) {
        confirmPassword.setAttribute('required', 'required');
        confirmPassword.setAttribute('minlength', '8');
        confirmPassword.setAttribute('placeholder', 'Confirma tu nueva contraseña');
    }
    
    // Agregar evento de envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar formulario
        if (!validatePasswordForm(form, newPassword, confirmPassword)) {
            return false;
        }
        
        // Mostrar confirmación antes de enviar
        showPasswordChangeConfirmation(form);
    });
    
    // Validación en tiempo real
    if (newPassword && confirmPassword) {
        confirmPassword.addEventListener('input', () => {
            validatePasswordMatch(newPassword, confirmPassword);
        });
    }
    
    console.log('✅ Validación del formulario de contraseña configurada');
}

/**
 * Validar formulario de contraseña
 */
function validatePasswordForm(form, newPassword, confirmPassword) {
    // Verificar validación HTML5
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }
    
    // Validar longitud mínima
    if (newPassword && newPassword.value.length < 8) {
        showErrorAlert('La nueva contraseña debe tener al menos 8 caracteres');
        newPassword.focus();
        return false;
    }
    
    // Validar coincidencia de contraseñas
    if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
        showErrorAlert('Las contraseñas no coinciden. Por favor, verifica que ambas contraseñas sean idénticas.');
        confirmPassword.focus();
        return false;
    }
    
    return true;
}

/**
 * Validar coincidencia de contraseñas en tiempo real
 */
function validatePasswordMatch(newPassword, confirmPassword) {
    if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Las contraseñas no coinciden');
        confirmPassword.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        confirmPassword.classList.remove('border-gray-200', 'focus:border-[#2f9f37]', 'focus:ring-[#2f9f37]');
    } else {
        confirmPassword.setCustomValidity('');
        confirmPassword.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        confirmPassword.classList.add('border-gray-200', 'focus:border-[#2f9f37]', 'focus:ring-[#2f9f37]');
    }
}

/**
 * Mostrar confirmación de cambio de contraseña
 */
function showPasswordChangeConfirmation(form) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Cambiar contraseña?',
            text: '¿Estás seguro de que quieres cambiar tu contraseña?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2D3A73',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            background: '#ffffff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                showLoadingState(form);
                // Enviar formulario
                form.submit();
            }
        });
    } else {
        if (confirm('¿Estás seguro de que quieres cambiar tu contraseña?')) {
            form.submit();
        }
    }
}

/**
 * Mostrar estado de carga
 */
function showLoadingState(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Guardando...
        `;
    }
}

/**
 * Configurar efectos visuales
 */
function setupVisualEffects() {
    // Efecto de hover en las tarjetas
    const cards = document.querySelectorAll('.bg-white.shadow-lg');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('shadow-xl', 'transform', 'scale-[1.02]');
        });
        
        card.addEventListener('mouseleave', () => {
            card.classList.remove('shadow-xl', 'transform', 'scale-[1.02]');
        });
    });
    
    // Efecto de focus en los inputs
    const inputs = document.querySelectorAll('input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('ring-2', 'ring-[#2f9f37]/20');
        });
        
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('ring-2', 'ring-[#2f9f37]/20');
        });
    });
}

/**
 * Verificar mensajes de sesión
 */
function checkSessionMessages() {
    // Verificar mensaje de éxito de contraseña
    const successMeta = document.querySelector('meta[name="password-success"]');
    if (successMeta) {
        const message = successMeta.getAttribute('content');
        showSuccessAlert(message);
        successMeta.remove();
    }
    
    // Verificar otros mensajes de sesión
    const flashMessage = document.querySelector('meta[name="flash-message"]');
    if (flashMessage) {
        const message = flashMessage.getAttribute('content');
        const token = document.querySelector('meta[name="flash-token"]')?.getAttribute('content');
        
        if (message && token) {
            showSuccessAlert(message);
            flashMessage.remove();
        }
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
        console.error(`❌ Elementos no encontrados para el campo: ${fieldId}`);
        return;
    }
    
    if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
        console.log(`👁️ Mostrando contraseña para: ${fieldId}`);
    } else {
        field.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
        console.log(`🙈 Ocultando contraseña para: ${fieldId}`);
    }
};

/**
 * Funciones de alerta (usando las del layout principal)
 */
function showErrorAlert(message) {
    if (typeof window.showErrorAlert === 'function') {
        window.showErrorAlert(message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: message,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Aceptar',
            background: '#ffffff'
        });
    } else {
        alert('ERROR: ' + message);
    }
}

function showSuccessAlert(message) {
    if (typeof window.showSuccessAlert === 'function') {
        window.showSuccessAlert(message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: message,
            confirmButtonColor: '#2f9f37',
            confirmButtonText: '¡Perfecto!',
            background: '#ffffff',
            timer: 4000,
            timerProgressBar: true
        });
    } else {
        alert('ÉXITO: ' + message);
    }
}

console.log('📄 Profile.js cargado correctamente');

