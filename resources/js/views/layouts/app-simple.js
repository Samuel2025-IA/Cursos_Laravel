/**
 * Scripts para el layout simple (app-simple.blade.php)
 * Maneja alertas y funcionalidades básicas para vistas simples
 */

// ===========================================
// CONFIGURACIÓN DE TAILWIND
// ===========================================
if (typeof tailwind !== 'undefined') {
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': '#2f9f37',
                }
            }
        }
    }
}

// ===========================================
// FUNCIONES GLOBALES DE ALERTAS
// ===========================================

/**
 * Función global para alertas de error
 */
window.showErrorAlert = function(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: message,
            confirmButtonColor: '#2f9f37', // Verde
            confirmButtonText: '¡Perfecto!',
            background: '#ffffff',
            iconColor: '#dc2626'
        });
    } else {
        alert('ERROR: ' + message);
    }
};

/**
 * Función global para alertas de advertencia
 */
window.showWarningAlert = function(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: '¡Atención!',
            text: message,
            confirmButtonColor: '#2f9f37', // Verde
            confirmButtonText: '¡Perfecto!',
            background: '#ffffff',
            iconColor: '#d97706'
        });
    } else {
        alert('ADVERTENCIA: ' + message);
    }
};

/**
 * Función global para alertas de información
 */
window.showInfoAlert = function(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: message,
            confirmButtonColor: '#2563eb', // Azul
            confirmButtonText: 'Aceptar',
            background: '#ffffff',
            iconColor: '#2563eb'
        });
    } else {
        alert('INFO: ' + message);
    }
};

/**
 * Función global para alertas de éxito
 */
window.showSuccessAlert = function(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: message,
            confirmButtonColor: '#2f9f37', // Verde
            confirmButtonText: '¡Perfecto!',
            background: '#ffffff',
            iconColor: '#059669',
            timer: 4000,
            timerProgressBar: true
        });
    } else {
        alert('ÉXITO: ' + message);
    }
};

// ===========================================
// MANEJO DE ALERTAS DE SESIÓN
// ===========================================

/**
 * Muestra alertas desde sesiones de Laravel
 */
function showSessionAlerts() {
    // Error
    if (window.sessionError) {
        console.log('🔴 Mostrando alerta de error:', window.sessionError);
        showErrorAlert(window.sessionError);
    }

    // Warning
    if (window.sessionWarning) {
        console.log('🟡 Mostrando alerta de advertencia:', window.sessionWarning);
        showWarningAlert(window.sessionWarning);
    }

    // Info
    if (window.sessionInfo) {
        console.log('🔵 Mostrando alerta de información:', window.sessionInfo);
        showInfoAlert(window.sessionInfo);
    }

    // Success
    if (window.sessionSuccess) {
        console.log('🟢 Mostrando alerta de éxito:', window.sessionSuccess);
        showSuccessAlert(window.sessionSuccess);
    }
}

// ===========================================
// FUNCIONES DE UTILIDAD
// ===========================================

/**
 * Aplica estilos específicos para vistas simples
 */
function applySimpleStyles() {
    // Aplicar estilos específicos si es necesario
    const body = document.body;
    if (body) {
        body.classList.add('app-simple-layout');
    }
}

/**
 * Configura funcionalidades básicas
 */
function setupBasicFeatures() {
    // Configurar funcionalidades básicas para vistas simples
    console.log('✅ Configurando funcionalidades básicas para app-simple');
}

// ===========================================
// INICIALIZACIÓN
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Scripts de app-simple cargados');
    
    // Aplicar estilos
    applySimpleStyles();
    
    // Configurar funcionalidades básicas
    setupBasicFeatures();
    
    // Mostrar alertas de sesión
    showSessionAlerts();
});

// ===========================================
// EXPORTAR FUNCIONES
// ===========================================
window.AppSimple = {
    showError: showErrorAlert,
    showWarning: showWarningAlert,
    showInfo: showInfoAlert,
    showSuccess: showSuccessAlert
};









