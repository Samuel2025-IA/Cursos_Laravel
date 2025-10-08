// JavaScript para la vista de registro - VALIDACIONES COMPLETAS
// Función togglePassword() ahora está centralizada en toggle-password-unified.js

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Script de registro cargado correctamente');
    
    // ===== VALIDACIONES DE NOMBRES =====
    initializeNameValidations();
    
    // ===== VALIDACIONES DE DOCUMENTOS =====
    initializeDocumentValidations();
    
    // ===== VALIDACIONES DE EMAIL =====
    initializeEmailValidations();
    
    // ===== VALIDACIONES DE CONTRASEÑAS =====
    initializePasswordValidations();
    
    // ===== VALIDACIONES DE ENTIDAD =====
    initializeEntityValidations();
    
    // ===== VALIDACIÓN GENERAL DEL FORMULARIO =====
    initializeFormValidation();
});

// ===== FUNCIONES DE VALIDACIÓN DE NOMBRES =====
function initializeNameValidations() {
    const nameFields = ['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'];
    
    nameFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field) {
            console.log(`Campo no encontrado: ${fieldName}`);
            return;
        }
        
        console.log(`Inicializando validación para: ${fieldName}`);
        
        // Crear elemento de ayuda si no existe
        createHelpElement(field, fieldName);
        
        // Validación solo al perder el foco (blur) - no en tiempo real
        field.addEventListener('blur', function() {
            console.log(`Validando campo: ${fieldName}, valor: "${this.value}"`);
            validateNameField(this, fieldName);
        });
        
        // Limpiar estado de error al empezar a escribir
        field.addEventListener('input', function() {
            console.log(`Input en: ${fieldName}, valor: "${this.value}"`);
            clearValidationClasses(this, null);
            this.setAttribute('aria-invalid', 'false');
        });
        
        // Debug: verificar que no hay otros event listeners
        field.addEventListener('keydown', function(e) {
            console.log(`Keydown en: ${fieldName}, tecla: ${e.key}`);
        });
    });
}

function validateNameField(field, fieldName) {
    const value = field.value.trim();
    
    console.log(`Validando ${fieldName}: "${value}"`);
    
    // Limpiar clases de validación
    clearValidationClasses(field, null);
    
    // Actualizar atributos ARIA
    field.setAttribute('aria-invalid', 'false');
    
    // Si el campo está vacío
    if (value === '') {
        if (['primer_nombre', 'primer_apellido', 'segundo_apellido'].includes(fieldName)) {
            // Campo obligatorio vacío = ROJO
            console.log(`${fieldName} vacío - campo obligatorio`);
            setFieldError(field, null, 'Este campo es obligatorio');
            field.setAttribute('aria-invalid', 'true');
            return false;
        }
        // Segundo nombre es opcional, vacío = sin validación
        console.log(`${fieldName} vacío - campo opcional`);
        return true;
    }
    
    // Validar que solo contenga letras, espacios y caracteres especiales permitidos
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s'-]+$/.test(value)) {
        // Contiene caracteres no permitidos = ROJO
        console.log(`${fieldName} contiene caracteres no permitidos: "${value}"`);
        setFieldError(field, null, 'Solo se permiten letras, espacios y acentos');
        field.setAttribute('aria-invalid', 'true');
        return false;
    }
    
    // Validar que no tenga múltiples espacios consecutivos
    if (/\s{2,}/.test(value)) {
        // Múltiples espacios = ROJO
        console.log(`${fieldName} tiene múltiples espacios`);
        setFieldError(field, null, 'No se permiten múltiples espacios consecutivos');
        field.setAttribute('aria-invalid', 'true');
        return false;
    }
    
    // Si pasa todas las validaciones = VERDE
    console.log(`${fieldName} válido: "${value}"`);
    setFieldSuccess(field, null, '✓ Formato correcto');
    field.setAttribute('aria-invalid', 'false');
    return true;
}

// Función eliminada - ya no se previenen caracteres
// Ahora se permite escribir cualquier carácter y se valida solo al salir del campo

// ===== FUNCIONES DE VALIDACIÓN DE DOCUMENTOS =====
function initializeDocumentValidations() {
    const tipoDocumento = document.getElementById('tipo_documento');
    const numeroDocumento = document.getElementById('numero_documento');
    const documentHint = document.getElementById('document-hint');

    // Función para actualizar la ayuda contextual
    function updateDocumentHint() {
        const tipo = tipoDocumento.value;
        let hint = '';
        
        switch(tipo) {
            case 'CC':
                hint = 'Cédula de Ciudadanía: 7-10 dígitos ';
                break;
            case 'CE':
                hint = 'Cédula de Extranjería: 7-12 dígitos ';
                break;
            case 'TI':
                hint = 'Tarjeta de Identidad: 6-10 dígitos ';
                break;
            case 'PP':
                hint = 'Pasaporte: 6-9 caracteres alfanuméricos ';
                break;
            case 'NIT':
                hint = 'NIT: 9-10 dígitos (ej: 123456789)';
                break;
            default:
                hint = 'Ingresa tu número de documento';
        }
        
        documentHint.textContent = hint;
    }

    // Event listeners
    tipoDocumento.addEventListener('change', updateDocumentHint);
    
    // Validación en tiempo real del número de documento
    numeroDocumento.addEventListener('input', function() {
        const tipo = tipoDocumento.value;
        const numero = this.value;
        const documentStatus = document.getElementById('document-status');
        
        // Validación inmediata de caracteres no permitidos
        if (/[^0-9A-Za-z]/.test(numero)) {
            setFieldError(this, null, 'No se permiten guiones, espacios ni símbolos especiales');
            if (documentStatus) documentStatus.classList.add('hidden');
            return;
        }
        
        if (tipo && numero) {
            let isValid = false;
            
            switch(tipo) {
                case 'CC':
                    isValid = /^\d{7,10}$/.test(numero);
                    break;
                case 'CE':
                    isValid = /^\d{7,12}$/.test(numero);
                    break;
                case 'TI':
                    isValid = /^\d{6,10}$/.test(numero);
                    break;
                case 'PP':
                    if (numero.length < 6 || numero.length > 9) {
                        isValid = false;
                    } else {
                        const letras = (numero.match(/[A-Z]/gi) || []).length;
                        const numeros = (numero.match(/[0-9]/g) || []).length;
                        isValid = letras <= 3 && numeros > 0 && /^[A-Z0-9]+$/i.test(numero);
                    }
                    break;
                case 'NIT':
                    isValid = /^\d{9,10}$/.test(numero);
                    break;
            }
            
            if (isValid) {
                setFieldSuccess(this, null, '✓ Formato correcto');
                if (documentStatus) documentStatus.classList.remove('hidden');
            } else {
                setFieldError(this, null, 'Formato incorrecto para el tipo de documento seleccionado');
                if (documentStatus) documentStatus.classList.add('hidden');
            }
        } else {
            clearValidationClasses(this, null);
            if (documentStatus) documentStatus.classList.add('hidden');
            updateDocumentHint();
        }
    });

    // Prevenir entrada de caracteres no permitidos
    numeroDocumento.addEventListener('keydown', function(e) {
        const allowedKeys = [
            'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
            'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
            'Home', 'End'
        ];
        
        if (allowedKeys.includes(e.key)) {
            return;
        }
        
        if (!/[0-9A-Za-z]/.test(e.key)) {
            e.preventDefault();
            setFieldError(this, null, 'Solo se permiten números y letras');
        }
    });

    updateDocumentHint();
}

// ===== FUNCIONES DE VALIDACIÓN DE EMAIL =====
function initializeEmailValidations() {
    const emailField = document.getElementById('email');
    if (!emailField) return;
    
    createHelpElement(emailField, 'email');
    
    emailField.addEventListener('input', function() {
        validateEmailField(this);
    });
    
    emailField.addEventListener('blur', function() {
        validateEmailField(this);
    });
}

function validateEmailField(field) {
    const value = field.value.trim();
    
    clearValidationClasses(field, null);
    field.setAttribute('aria-invalid', 'false');
    
    if (value === '') {
        setFieldError(field, null, 'El correo electrónico es obligatorio');
        field.setAttribute('aria-invalid', 'true');
        return false;
    }
    
    // Validación de formato de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        setFieldError(field, null, 'Formato de email inválido');
        field.setAttribute('aria-invalid', 'true');
        return false;
    }
    
    // Validación de longitud
    if (value.length > 255) {
        setFieldError(field, null, 'Máximo 255 caracteres');
        field.setAttribute('aria-invalid', 'true');
        return false;
    }
    
    // Validaciones adicionales
    if (value.includes('..') || value.startsWith('.') || value.endsWith('.')) {
        setFieldError(field, null, 'Formato de email inválido');
        field.setAttribute('aria-invalid', 'true');
        return false;
    }
    
    setFieldSuccess(field, null, '✓ Formato correcto');
    field.setAttribute('aria-invalid', 'false');
    return true;
}

// ===== FUNCIONES DE VALIDACIÓN DE CONTRASEÑAS =====
function initializePasswordValidations() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    if (passwordField) {
        createHelpElement(passwordField, 'password');
        passwordField.addEventListener('input', function() {
            validatePasswordField(this);
            if (confirmPasswordField && confirmPasswordField.value) {
                validatePasswordConfirmation(confirmPasswordField);
            }
        });
        passwordField.addEventListener('blur', function() {
            validatePasswordField(this);
        });
    }
    
    if (confirmPasswordField) {
        createHelpElement(confirmPasswordField, 'password_confirmation');
        confirmPasswordField.addEventListener('input', function() {
            validatePasswordConfirmation(this);
        });
        confirmPasswordField.addEventListener('blur', function() {
            validatePasswordConfirmation(this);
        });
    }
}

function validatePasswordField(field) {
    const value = field.value;
    
    clearValidationClasses(field, null);
    
    if (value === '') {
        setFieldError(field, null, 'La contraseña es obligatoria');
        updatePasswordRequirements(value);
        return false;
    }
    
    // Validación simplificada: solo mínimo 8 caracteres
    if (value.length < 8) {
        setFieldError(field, null, 'Mínimo 8 caracteres');
        updatePasswordRequirements(value);
        return false;
    }
    
    setFieldSuccess(field, null, '✓ Contraseña válida');
    updatePasswordRequirements(value);
    return true;
}

// Función para actualizar indicadores de requisitos de contraseña
function updatePasswordRequirements(password) {
    const reqLength = document.getElementById('req-length');
    if (!reqLength) return;
    
    if (password.length >= 8) {
        reqLength.className = 'flex items-center text-green-600';
        reqLength.innerHTML = '<span>✓ Mínimo 8 caracteres</span>';
    } else {
        reqLength.className = 'flex items-center text-gray-500';
        reqLength.innerHTML = '<span>Mínimo 8 caracteres</span>';
    }
}

function validatePasswordConfirmation(field) {
    const value = field.value;
    const passwordValue = document.getElementById('password').value;
    
    clearValidationClasses(field, null);
    
    if (value === '') {
        setFieldError(field, null, 'Confirma tu contraseña');
        return false;
    }
    
    if (value !== passwordValue) {
        setFieldError(field, null, 'Las contraseñas no coinciden');
        return false;
    }
    
    setFieldSuccess(field, null, '✓ Las contraseñas coinciden');
    return true;
}

// ===== FUNCIONES DE VALIDACIÓN DE ENTIDAD =====
function initializeEntityValidations() {
    const entityField = document.getElementById('entidad');
    if (!entityField) return;
    
    createHelpElement(entityField, 'entidad');
    
    entityField.addEventListener('change', function() {
        validateEntityField(this);
    });
}

function validateEntityField(field) {
    const value = field.value;
    
    clearValidationClasses(field, null);
    
    if (value === '') {
        setFieldError(field, null, 'Debes seleccionar una entidad');
        return false;
    }
    
    const validEntities = ['funadpas', 'fundacion_isaias', 'diocesis_apartado', 'pastoral_social'];
    if (!validEntities.includes(value)) {
        setFieldError(field, null, 'Entidad no válida');
        return false;
    }
    
    setFieldSuccess(field, null, '✓ Entidad seleccionada');
    return true;
}

// ===== FUNCIONES AUXILIARES DE VALIDACIÓN =====
function createHelpElement(field, fieldName) {
    // NO crear elementos de ayuda - eliminados por ser redundantes
    return;
}

function getDefaultHelpText(fieldName) {
    const defaults = {
        'primer_nombre': 'Ingresa tu primer nombre',
        'segundo_nombre': 'Opcional',
        'primer_apellido': 'Ingresa tu primer apellido',
        'segundo_apellido': 'Ingresa tu segundo apellido',
        'email': 'Ingresa tu correo electrónico',
        'password': 'Mínimo 8 caracteres con mayúsculas, minúsculas, números y símbolos',
        'password_confirmation': 'Repite tu contraseña',
        'entidad': 'Selecciona tu entidad'
    };
    return defaults[fieldName] || '';
}

function clearValidationClasses(field, helpElement) {
    field.classList.remove('border-red-500', 'border-green-500', 'focus:border-red-500', 'focus:border-green-500', 'focus:ring-red-500', 'focus:ring-green-500');
    field.classList.add('border-gray-300', 'focus:border-emerald-500', 'focus:ring-emerald-500');
}

function setFieldError(field, helpElement, message) {
    field.classList.remove('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
    field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
}

function setFieldSuccess(field, helpElement, message) {
    field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    field.classList.add('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
}

function showTemporaryError(field, message) {
    // Crear o actualizar mensaje temporal de error
    let tempError = field.parentElement.querySelector('.temp-error');
    if (!tempError) {
        tempError = document.createElement('div');
        tempError.className = 'temp-error text-xs text-red-600 mt-1';
        field.parentElement.appendChild(tempError);
    }
    
    tempError.textContent = message;
    tempError.style.opacity = '1';
    
    // Remover el mensaje después de 2 segundos
    setTimeout(() => {
        if (tempError) {
            tempError.style.opacity = '0';
            setTimeout(() => {
                if (tempError && tempError.parentElement) {
                    tempError.parentElement.removeChild(tempError);
                }
            }, 300);
        }
    }, 2000);
}

// ===== VALIDACIÓN GENERAL DEL FORMULARIO =====
function initializeFormValidation() {
    const form = document.getElementById('register-form');
    if (!form) return;
    
    // Navegación con teclado TEMPORALMENTE DESHABILITADA PARA DEBUG
    /*
    const navigationInputs = form.querySelectorAll('input[type="email"], input[type="password"], input[name="numero_documento"], select');
    navigationInputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            // Enter en campos específicos va al siguiente campo
            if (e.key === 'Enter' && input.type !== 'submit') {
                e.preventDefault();
                const nextInput = navigationInputs[index + 1];
                if (nextInput) {
                    nextInput.focus();
                }
            }
        });
    });
    */
    
    form.addEventListener('submit', function(e) {
        if (!validateAllFields()) {
            e.preventDefault();
            showFormErrors();
        }
    });
}

function validateAllFields() {
    const fields = [
        { id: 'primer_nombre', validator: validateNameField },
        { id: 'primer_apellido', validator: validateNameField },
        { id: 'segundo_apellido', validator: validateNameField },
        { id: 'tipo_documento', validator: validateRequiredField },
        { id: 'numero_documento', validator: validateDocumentNumber },
        { id: 'email', validator: validateEmailField },
        { id: 'password', validator: validatePasswordField },
        { id: 'password_confirmation', validator: validatePasswordConfirmation },
        { id: 'entidad', validator: validateEntityField }
    ];
    
    let allValid = true;
    
    fields.forEach(field => {
        const element = document.getElementById(field.id);
        if (element) {
            const isValid = field.validator(element);
            if (!isValid) {
                allValid = false;
            }
        }
    });
    
    return allValid;
}

function validateRequiredField(field) {
    const value = field.value.trim();
    
    if (value === '') {
        setFieldError(field, null, 'Este campo es obligatorio');
        return false;
    }
    
    setFieldSuccess(field, null, '✓ Campo completado');
    return true;
}

function validateDocumentNumber(field) {
    const tipoDocumento = document.getElementById('tipo_documento');
    const numero = field.value;
    
    if (!tipoDocumento.value) {
        setFieldError(field, null, 'Selecciona primero el tipo de documento');
        return false;
    }
    
    if (numero === '') {
        setFieldError(field, null, 'El número de documento es obligatorio');
        return false;
    }
    
    // Usar la lógica de validación existente
    let isValid = false;
    switch(tipoDocumento.value) {
        case 'CC':
            isValid = /^\d{7,10}$/.test(numero);
            break;
        case 'CE':
            isValid = /^\d{7,12}$/.test(numero);
            break;
        case 'TI':
            isValid = /^\d{6,10}$/.test(numero);
            break;
        case 'PP':
            if (numero.length >= 6 && numero.length <= 9) {
                const letras = (numero.match(/[A-Z]/gi) || []).length;
                const numeros = (numero.match(/[0-9]/g) || []).length;
                isValid = letras <= 3 && numeros > 0 && /^[A-Z0-9]+$/i.test(numero);
            }
            break;
        case 'NIT':
            isValid = /^\d{9,10}$/.test(numero);
            break;
    }
    
    if (isValid) {
        setFieldSuccess(field, null, '✓ Formato correcto');
        return true;
    } else {
        setFieldError(field, null, 'Formato incorrecto para el tipo de documento');
        return false;
    }
}

function showFormErrors() {
    const firstError = document.querySelector('.border-red-500');
    if (firstError) {
        firstError.focus();
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
