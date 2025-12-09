/**
 * JavaScript para manejar alertas de bienvenida y despedida
 * Se ejecuta automáticamente al cargar la página
 */

// Variable global para evitar ejecuciones múltiples
window.alertsAlreadyShown = window.alertsAlreadyShown || false;

// ===========================================
// FUNCIÓN PARA MOSTRAR ALERTA DE BIENVENIDA
// ===========================================

function showWelcomeAlert() {
    console.log('🔍 Buscando alerta de bienvenida...');
    
    // Buscar meta tag con mensaje de bienvenida
    const welcomeMeta = document.querySelector('meta[name="welcome-message"]');
    
    if (welcomeMeta) {
        const message = welcomeMeta.getAttribute('content');
        console.log('📝 Meta tag encontrado:', message);
        
        if (message && typeof Swal !== 'undefined') {
            console.log(' Mostrando alerta de bienvenida:', message);
            
            Swal.fire({
                icon: 'success',
                title: message,
                confirmButtonColor: '#2f9f37',
                confirmButtonText: '¡Gracias!',
                background: '#ffffff',
                iconColor: '#059669',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true
            }).then(() => {
                console.log('✅ Alerta de bienvenida cerrada');
                
                // Limpiar la sesión de welcome_message
                fetch('/dashboard/clear-welcome-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                }).then(() => {
                    console.log('✅ Sesión de bienvenida limpiada');
                }).catch(err => {
                    console.error('❌ Error limpiando sesión:', err);
                });
            });
            
            // Eliminar el meta tag después de mostrar la alerta
            welcomeMeta.remove();
        } else {
            console.log('❌ No se puede mostrar: SweetAlert2 no disponible o mensaje vacío');
        }
    } else {
        console.log('❌ No se encontró meta tag de bienvenida');
    }
}

// ===========================================
// FUNCIÓN PARA MOSTRAR ALERTA DE DESPEDIDA
// ===========================================

function showGoodbyeAlert() {
    // Buscar meta tags con mensaje de despedida
    const goodbyeMeta = document.querySelector('meta[name="goodbye-message"]');
    const isLogoutMeta = document.querySelector('meta[name="is-logout-redirect"]');
    
    if (goodbyeMeta && isLogoutMeta) {
        const message = goodbyeMeta.getAttribute('content');
        const isLogout = isLogoutMeta.getAttribute('content') === 'true';
        
        if (message && isLogout && typeof Swal !== 'undefined') {
            console.log(' Mostrando alerta de despedida:', message);
            
            Swal.fire({
                icon: 'info',
                title: '¡Hasta pronto!',
                text: message,
                confirmButtonColor: '#2563eb',
                confirmButtonText: '¡Hasta luego!',
                background: '#ffffff',
                iconColor: '#2563eb',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true
            });
            
            // Eliminar los meta tags después de mostrar la alerta
            goodbyeMeta.remove();
            isLogoutMeta.remove();
        }
    }
}

// ===========================================
// FUNCIÓN PARA MOSTRAR ALERTA DE BIENVENIDA DE ADMIN
// ===========================================

function showAdminWelcomeAlert() {
    console.log('🔍 Buscando alerta de bienvenida de admin...');
    
    // Buscar meta tag con mensaje de bienvenida de admin
    const adminWelcomeMeta = document.querySelector('meta[name="admin-welcome-message"]');
    
    if (adminWelcomeMeta) {
        const message = adminWelcomeMeta.getAttribute('content');
        console.log('📝 Meta tag de admin encontrado:', message);
        
        if (typeof Swal !== 'undefined') {
            console.log(' Mostrando alerta de bienvenida de admin');
            
            Swal.fire({
                icon: 'success',
                title: '¡Hola Admin!',
                text: message,
                confirmButtonColor: '#2f9f37',
                confirmButtonText: '¡Gracias!',
                background: '#ffffff',
                iconColor: '#059669',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true
            }).then(() => {
                console.log('✅ Alerta de admin cerrada');
            });
            
            // Eliminar el meta tag después de mostrar la alerta
            adminWelcomeMeta.remove();
        } else {
            console.log('❌ No se puede mostrar admin: SweetAlert2 no disponible');
        }
    } else {
        console.log('❌ No se encontró meta tag de admin');
    }
}

// ===========================================
// INICIALIZACIÓN AUTOMÁTICA
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Sistema de alertas de bienvenida/despedida cargado');
    
    // Función para verificar y mostrar alertas
    function checkAndShowAlerts() {
        console.log('🔍 Verificando alertas disponibles...');
        
        // Esperar a que SweetAlert2 esté disponible
        if (typeof Swal === 'undefined') {
            console.log('⏳ Esperando SweetAlert2...');
            setTimeout(checkAndShowAlerts, 500);
            return;
        }
        
        console.log('✅ SweetAlert2 disponible');
        
        // Verificar meta tags con múltiples selectores
        const welcomeMeta = document.querySelector('meta[name="welcome-message"]') || document.getElementById('welcome-message-meta');
        const adminWelcomeMeta = document.querySelector('meta[name="admin-welcome-message"]') || document.getElementById('admin-welcome-message-meta');
        const goodbyeMeta = document.querySelector('meta[name="goodbye-message"]') || document.getElementById('goodbye-message-meta');
        
        console.log('📋 Meta tags encontrados:');
        console.log('- welcome-message:', welcomeMeta ? 'SÍ (' + welcomeMeta.getAttribute('content') + ')' : 'NO');
        console.log('- admin-welcome-message:', adminWelcomeMeta ? 'SÍ (' + adminWelcomeMeta.getAttribute('content') + ')' : 'NO');
        console.log('- goodbye-message:', goodbyeMeta ? 'SÍ (' + goodbyeMeta.getAttribute('content') + ')' : 'NO');
        
        // Debug adicional: listar todos los meta tags
        const allMetaTags = document.querySelectorAll('meta');
        console.log('🔍 Todos los meta tags en la página:', allMetaTags.length);
        allMetaTags.forEach((meta, index) => {
            if (meta.name && (meta.name.includes('welcome') || meta.name.includes('goodbye'))) {
                console.log(`Meta tag ${index}: name="${meta.name}" content="${meta.content}"`);
            }
        });
        
        // Evitar ejecuciones múltiples
        if (window.alertsAlreadyShown) {
            console.log('⚠️ Alertas ya mostradas, evitando duplicidad');
            return;
        }
        
        // Mostrar alertas si existen
        if (goodbyeMeta) {
            console.log('🎉 Mostrando alerta de despedida');
            window.alertsAlreadyShown = true;
            showGoodbyeAlert();
        } else if (adminWelcomeMeta) {
            console.log('🎉 Mostrando alerta de bienvenida de admin');
            window.alertsAlreadyShown = true;
            showAdminWelcomeAlert();
        } else if (welcomeMeta) {
            console.log('🎉 Mostrando alerta de bienvenida general');
            window.alertsAlreadyShown = true;
            showWelcomeAlert();
        } else {
            console.log('ℹ️ No hay alertas para mostrar');
        }
    }
    
    // Ejecutar después de un pequeño delay para asegurar que todo esté cargado
    setTimeout(checkAndShowAlerts, 200);
});

// ===========================================
// EXPORTAR FUNCIONES PARA USO MANUAL
// ===========================================

window.WelcomeGoodbyeAlerts = {
    showWelcome: showWelcomeAlert,
    showGoodbye: showGoodbyeAlert,
    showAdminWelcome: showAdminWelcomeAlert
};

// No exponemos funciones de prueba para evitar ejecuciones accidentales
