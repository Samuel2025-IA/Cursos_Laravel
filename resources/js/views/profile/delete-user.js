/**
 * ========================================
 * DELETE USER - FUNCIONALIDADES
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades del formulario de eliminación de usuario
    initializeDeleteUserForm();
});

/**
 * Inicializar funcionalidades del formulario de eliminación de usuario
 */
function initializeDeleteUserForm() {
    // Configurar botón de eliminación
    setupDeleteButton();
    
    // Verificar si hay errores de validación
    checkValidationErrors();
}

/**
 * Configurar botón de eliminación
 */
function setupDeleteButton() {
    const deleteButton = document.querySelector('button[onclick="confirmAccountDeletion()"]');
    if (deleteButton) {
        // Remover el onclick inline y agregar event listener
        deleteButton.removeAttribute('onclick');
        deleteButton.addEventListener('click', confirmAccountDeletion);
    }
}

/**
 * Verificar errores de validación
 */
function checkValidationErrors() {
    // Los errores se manejan a través del sistema de flash messages
    // que se ejecuta desde la vista Blade
}

/**
 * Función para confirmar eliminación de cuenta
 */
function confirmAccountDeletion() {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    Swal.fire({
        title: '¿Eliminar tu cuenta?',
        text: 'Esta acción eliminará permanentemente todos tus datos y no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        color: '#374151'
    }).then((result) => {
        if (result.isConfirmed) {
            // Segunda confirmación con contraseña
            confirmPasswordForDeletion();
        }
    });
}

/**
 * Confirmar contraseña para eliminación
 */
function confirmPasswordForDeletion() {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }

    Swal.fire({
        title: 'Confirma tu contraseña',
        input: 'password',
        inputPlaceholder: 'Ingresa tu contraseña actual',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirmar eliminación',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes ingresar tu contraseña';
            }
        }
    }).then((passwordResult) => {
        if (passwordResult.isConfirmed) {
            // Enviar formulario con la contraseña
            const passwordField = document.getElementById('delete-password');
            const deleteForm = document.getElementById('delete-account-form');
            
            if (passwordField && deleteForm) {
                passwordField.value = passwordResult.value;
                deleteForm.submit();
            } else {
                console.error('Formulario de eliminación no encontrado');
            }
        }
    });
}
