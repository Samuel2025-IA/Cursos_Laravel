/**
 * ========================================
 * TOGGLE PASSWORD - FUNCIÓN GLOBAL UNIFICADA
 * ========================================
 * Función centralizada para mostrar/ocultar contraseñas
 * en toda la aplicación
 */

// Función global unificada para mostrar/ocultar contraseña
// Verificar si ya existe para evitar duplicidad en Edge
if (typeof window.togglePassword === 'undefined') {
    window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    
    if (!field) {
        console.error(`❌ Campo de contraseña no encontrado: ${fieldId}`);
        return;
    }
    
    // Buscar iconos con diferentes patrones de ID
    let eyeIcon = document.getElementById(`eye-icon-${fieldId}`) || 
                  document.getElementById(`eye-${fieldId}`);
    let eyeSlashIcon = document.getElementById(`eye-slash-icon-${fieldId}`) || 
                       document.getElementById(`eye-slash-${fieldId}`);
    
    // Si no se encuentran los iconos, solo alternar el tipo de campo
    if (!eyeIcon || !eyeSlashIcon) {
        console.warn(`⚠️ Iconos no encontrados para: ${fieldId}, solo alternando tipo de campo`);
        field.type = field.type === 'password' ? 'text' : 'password';
        return;
    }
    
    // Alternar visibilidad con iconos
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
}

// Configurar toggles automáticamente al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si ya se configuró (para evitar duplicidad en Edge)
    if (window.togglePasswordConfigured) {
        console.log('⚠️ Toggle password ya configurado, evitando duplicidad');
        return;
    }
    
    const toggleButtons = document.querySelectorAll('button[onclick*="togglePassword"]');
    
    toggleButtons.forEach(button => {
        // Agregar efecto hover
        button.addEventListener('mouseenter', () => {
            button.classList.add('text-gray-600');
        });
        
        button.addEventListener('mouseleave', () => {
            button.classList.remove('text-gray-600');
        });
    });
    
    // Marcar como configurado
    window.togglePasswordConfigured = true;
    
    console.log(`✅ Configurados ${toggleButtons.length} botones de toggle de contraseña`);
});

console.log('🔧 Función togglePassword() cargada y lista para usar');
