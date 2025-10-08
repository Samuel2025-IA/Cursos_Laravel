/**
 * Script para alerta de despedida
 * Maneja la visualización de mensajes de despedida después del logout
 */
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay mensaje de despedida (solo para logout exitoso)
    const goodbyeMeta = document.querySelector('meta[name="goodbye-message"]');
    const logoutRedirectMeta = document.querySelector('meta[name="is-logout-redirect"]');
    
    if (goodbyeMeta && logoutRedirectMeta && typeof Swal !== 'undefined') {
        const message = goodbyeMeta.getAttribute('content');
        if (message && message.trim() !== '') {
            // Verificar si la página se cargó por navegación del navegador
            const navigationEntries = performance.getEntriesByType('navigation');
            const isBackForwardNavigation = navigationEntries.length > 0 && 
                navigationEntries[0].type === 'back_forward';
            
            // Verificar si viene desde páginas de login o verify-invitation
            const referrer = document.referrer;
            const isFromAuthPages = referrer.includes('/login') || 
                                  referrer.includes('/verify-invitation') || 
                                  referrer.includes('/register');
            
            // Verificar si es un logout legítimo
            const isLegitimateLogout = localStorage.getItem('legitimate_logout');
            
            // Solo mostrar si es un logout legítimo Y NO es navegación hacia atrás/adelante Y NO viene de páginas de auth
            if (isLegitimateLogout && !isBackForwardNavigation && !isFromAuthPages) {
                // Verificar que no se haya mostrado ya
                const alreadyShown = sessionStorage.getItem('logout_farewell_shown');
                if (!alreadyShown) {
                    // Marcar como mostrado para evitar duplicados
                    sessionStorage.setItem('logout_farewell_shown', 'true');
                    
                    // Mostrar alerta de despedida
                    Swal.fire({
                        icon: 'success',
                        title: '¡Hasta pronto!',
                        text: message,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#2563eb',
                        background: '#ffffff',
                        timer: 4000,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Limpiar el storage después de cerrar la alerta
                        sessionStorage.removeItem('logout_farewell_shown');
                        localStorage.removeItem('legitimate_logout');
                    });
                }
            } else {
                // Si es navegación no permitida, limpiar inmediatamente
                if (isBackForwardNavigation) {
                    console.log('Navegación hacia atrás/adelante detectada. Omitiendo alerta de despedida.');
                }
                if (isFromAuthPages) {
                    console.log('Navegación desde páginas de autenticación detectada. Omitiendo alerta de despedida.');
                    console.log('Referrer:', referrer);
                }
                if (!isLegitimateLogout) {
                    console.log('No es un logout legítimo. Omitiendo alerta de despedida.');
                }
                
                // Limpiar localStorage si no es logout legítimo
                if (!isLegitimateLogout || isFromAuthPages || isBackForwardNavigation) {
                    localStorage.removeItem('legitimate_logout');
                }
            }
            
            // Limpiar los meta tags después de procesar
            goodbyeMeta.remove();
            logoutRedirectMeta.remove();
        }
    }
});
