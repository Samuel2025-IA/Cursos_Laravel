/**
 * ========================================
 * TOGGLE PASSWORD - FUNCIÓN GLOBAL
 * ========================================
 */

// Función global para mostrar/ocultar contraseña
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    
    if (!field) {
        console.error(`Campo de contraseña no encontrado: ${fieldId}`);
        return;
    }
    
    // Intentar encontrar los iconos con diferentes patrones de ID
    let eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
    let eyeSlashIcon = document.getElementById(`eye-slash-icon-${fieldId}`);
    
    // Si no se encuentran con el patrón icon-, intentar sin icon-
    if (!eyeIcon) {
        eyeIcon = document.getElementById(`eye-${fieldId}`);
    }
    if (!eyeSlashIcon) {
        eyeSlashIcon = document.getElementById(`eye-slash-${fieldId}`);
    }
    
    // Debug: verificar que los iconos existen
    if (!eyeIcon) {
        console.error(`Icono de ojo no encontrado para: ${fieldId}. Buscando: eye-icon-${fieldId} o eye-${fieldId}`);
        return;
    }
    if (!eyeSlashIcon) {
        console.error(`Icono de ojo tachado no encontrado para: ${fieldId}. Buscando: eye-slash-icon-${fieldId} o eye-slash-${fieldId}`);
        return;
    }
    
    // Alternar visibilidad
    if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
        console.log(`Mostrando contraseña para: ${fieldId}`);
    } else {
        field.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
        console.log(`Ocultando contraseña para: ${fieldId}`);
    }
};


