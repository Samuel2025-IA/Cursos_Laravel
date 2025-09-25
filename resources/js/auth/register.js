// JavaScript para la vista de registro

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

// Validación en tiempo real para documentos
document.addEventListener('DOMContentLoaded', function() {
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
        
        // Validación inmediata de caracteres no permitidos
        if (/[^0-9A-Za-z]/.test(numero)) {
            // Caracteres no permitidos detectados - marcar en rojo inmediatamente
            this.classList.remove('border-green-500', 'focus:border-green-500', 'focus:ring-green-500', 'border-gray-300', 'focus:border-emerald-500', 'focus:ring-emerald-500');
            this.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            documentHint.classList.remove('text-green-500', 'text-gray-500');
            documentHint.classList.add('text-red-500');
            documentHint.textContent = 'No se permiten guiones, espacios ni símbolos especiales';
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
                    // Nueva validación del pasaporte: máximo 3 letras y debe tener números
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
            
            // Aplicar estilos de validación
            if (isValid) {
                this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.add('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
                documentHint.classList.remove('text-red-500');
                documentHint.classList.add('text-green-500');
                // Restaurar mensaje original
                updateDocumentHint();
            } else {
                this.classList.remove('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
                this.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                documentHint.classList.remove('text-green-500');
                documentHint.classList.add('text-red-500');
                documentHint.textContent = 'Formato incorrecto para el tipo de documento seleccionado';
            }
        } else {
            // Resetear estilos si no hay tipo seleccionado o número
            this.classList.remove('border-red-500', 'border-green-500', 'focus:border-red-500', 'focus:border-green-500', 'focus:ring-red-500', 'focus:ring-green-500');
            this.classList.add('border-gray-300', 'focus:border-emerald-500', 'focus:ring-emerald-500');
            documentHint.classList.remove('text-red-500', 'text-green-500');
            documentHint.classList.add('text-gray-500');
            // Restaurar mensaje original
            updateDocumentHint();
        }
    });

    // Prevenir entrada de caracteres no permitidos en tiempo real
    numeroDocumento.addEventListener('keydown', function(e) {
        const allowedKeys = [
            'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
            'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
            'Home', 'End'
        ];
        
        // Permitir teclas de navegación y control
        if (allowedKeys.includes(e.key)) {
            return;
        }
        
        // Permitir solo números y letras
        if (!/[0-9A-Za-z]/.test(e.key)) {
            e.preventDefault();
            // Marcar en rojo inmediatamente
            this.classList.remove('border-green-500', 'focus:border-green-500', 'focus:ring-green-500', 'border-gray-300', 'focus:border-emerald-500', 'focus:ring-emerald-500');
            this.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            documentHint.classList.remove('text-green-500', 'text-gray-500');
            documentHint.classList.add('text-red-500');
            documentHint.textContent = 'Solo se permiten números y letras';
        }
    });

    // Inicializar ayuda contextual
    updateDocumentHint();
});
