/**
 * Sistema de Dashboard - Maneja alertas flash y mensajes de bienvenida
 * Versión simplificada y robusta con doble sistema de bienvenida
 * 
 * Sistema de Bienvenida:
 * - Registro: "¡Bienvenido a la Diócesis de Apartadó, [nombre]! Tu cuenta ha sido creada exitosamente."
 * - Visitas posteriores: "¡Qué bueno verte por aquí otra vez, [nombre]!"
 * 
 * Mejoras implementadas:
 * - Tiempos optimizados: registro (0.3s), bienvenida normal (0.5s)
 * - Sistema dual: diferentes mensajes según el contexto
 * - Mejor UX: permite cerrar con ESC o click fuera, timer personalizado (10s/6s)
 */

// Variable para controlar que solo se ejecute una vez
let dashboardInitialized = false;

// Función para mostrar alertas flash
function showSimpleFlashAlert() {
    const flashMessage = document.querySelector('meta[name="flash-message"]');
    const flashToken = document.querySelector('meta[name="flash-token"]');
    
    if (flashMessage && flashToken) {
        const message = flashMessage.getAttribute('content');
        const token = flashToken.getAttribute('content');
        
        // Verificar localStorage
        const processedTokens = JSON.parse(localStorage.getItem('simpleFlashTokens') || '[]');
        
        if (!processedTokens.includes(token)) {
            console.log('Mostrando alerta flash:', message);
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({                                         
                    icon: 'success',
                    title: '¡Éxito!',
                    text: message,
                    confirmButtonColor: '#2f9f37',
                    confirmButtonText: '¡Perfecto!',
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    timer: 6000, // 6 segundos para mensajes de registro
                    timerProgressBar: true,
                    position: 'center',
                    backdrop: true,
                    toast: false,
                    width: 'auto',
                    padding: '2em'
                });
            } else {
                alert(message);
            }
            
            // Marcar como procesado
            processedTokens.push(token);
            localStorage.setItem('simpleFlashTokens', JSON.stringify(processedTokens));
        }
        
        // Limpiar meta tags
        flashMessage.remove();
        flashToken.remove();
    }
}

// Función para mostrar mensaje de bienvenida
function showWelcomeMessage() {
    const welcomeMessageMeta = document.querySelector('meta[name="welcome-message"]');
    
    if (welcomeMessageMeta) {
        const welcomeMessage = welcomeMessageMeta.getAttribute('content');
        console.log('Mostrando mensaje de bienvenida:', welcomeMessage);
        
        // Determinar el tipo de mensaje para personalizar la alerta
        const isRegistrationMessage = welcomeMessage.includes('Tu cuenta ha sido creada exitosamente');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: isRegistrationMessage ? '¡Bienvenido!' : '¡Hola de nuevo!',
                text: welcomeMessage,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#2563eb',
                timer: isRegistrationMessage ? 10000 : 6000, // 10s para registro, 6s para bienvenida
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: true,
                showCloseButton: false,
                position: 'center',
                backdrop: true,
                focusConfirm: true,
                toast: false,
                width: 'auto',
                padding: '2em',
                customClass: {
                    popup: 'swal-welcome-success'
                },
                didOpen: () => {
                    // Animación del checkmark verde
                    const icon = document.querySelector('.swal2-success-circular-line-right');
                    if (icon) {
                        icon.style.animation = 'swal2-success-circular-line-right 0.75s ease-in-out';
                    }
                }
            }).then(() => {
                console.log('Mensaje de bienvenida cerrado');
            });
        } else {
            console.warn('SweetAlert2 no está disponible, usando alert nativo');
            alert(welcomeMessage);
        }
        
                // Marcar como mostrado con timestamp
                sessionStorage.setItem('welcome_shown', 'true');
                sessionStorage.setItem('last_welcome_time', Date.now().toString());
                
                // Limpiar meta tag
                welcomeMessageMeta.remove();
                
                // Limpiar la sesión en el servidor
                fetch('/dashboard/clear-welcome-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).catch(error => console.log('Error limpiando sesión:', error));
    } else {
        console.log('No se encontró mensaje de bienvenida');
    }
}

// Función para mostrar mensaje de bienvenida por defecto (fallback)
function showDefaultWelcomeMessage() {
    console.log('Mostrando mensaje de bienvenida por defecto');
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: '¡Qué bueno verte por aquí!',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#2563eb',
            timer: 6000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: true,
            showCloseButton: false,
            position: 'center',
            backdrop: true,
            focusConfirm: true,
            toast: false,
            width: 'auto',
            padding: '2em',
            customClass: {
                popup: 'swal-welcome-success'
            },
            didOpen: () => {
                // Animación del checkmark verde
                const icon = document.querySelector('.swal2-success-circular-line-right');
                if (icon) {
                    icon.style.animation = 'swal2-success-circular-line-right 0.75s ease-in-out';
                }
            }
        }).then(() => {
            console.log('Mensaje de bienvenida por defecto cerrado');
        });
    } else {
        console.warn('SweetAlert2 no está disponible, usando alert nativo');
        alert('¡Bienvenido! ¡Qué bueno verte por aquí!');
    }
}

// Función principal que se ejecuta cuando todo esté listo
function initializeDashboardSystem() {
    console.log('Inicializando sistema de dashboard...');
    
    // Prevenir ejecución múltiple
    if (dashboardInitialized) {
        console.log('Dashboard ya inicializado, omitiendo...');
        return;
    }
    dashboardInitialized = true;
    
    console.log('Dashboard inicializado correctamente');
    console.log('SweetAlert2 disponible:', typeof Swal !== 'undefined');
    
    // Esperar a que SweetAlert2 esté cargado (tiempo reducido)
    setTimeout(() => {
        console.log('Ejecutando alertas...');
        
        // Verificar si hay alertas flash primero
        const hasFlashAlert = document.querySelector('meta[name="flash-message"]');
        const hasWelcomeMessage = document.querySelector('meta[name="welcome-message"]');
        
        console.log('Meta tags encontrados:');
        console.log('- flash-message:', hasFlashAlert ? hasFlashAlert.getAttribute('content') : 'NO ENCONTRADO');
        console.log('- welcome-message:', hasWelcomeMessage ? hasWelcomeMessage.getAttribute('content') : 'NO ENCONTRADO');
        
        if (hasWelcomeMessage) {
            // Priorizar mensaje de bienvenida (puede ser registro o login)
            const messageContent = hasWelcomeMessage.getAttribute('content');
            console.log('Mostrando mensaje de bienvenida:', messageContent);
            
            // Determinar si es mensaje de registro o de bienvenida
            if (messageContent.includes('Tu cuenta ha sido creada exitosamente')) {
                console.log('Es un mensaje de registro - mostrando inmediatamente');
                setTimeout(() => {
                    showWelcomeMessage();
                }, 300); // 0.3 segundos para registro
            } else {
                console.log('Es un mensaje de bienvenida normal - mostrando en 0.5s');
                setTimeout(() => {
                    showWelcomeMessage();
                }, 500); // 0.5 segundos para bienvenida normal
            }
        } else if (hasFlashAlert) {
            // Si hay alerta flash (fallback), mostrarla inmediatamente
            console.log('Mostrando alerta flash...');
            showSimpleFlashAlert();
        } else {
            // Verificar si ya se mostró la alerta en esta sesión
            const welcomeShown = sessionStorage.getItem('welcome_shown');
            if (!welcomeShown) {
                console.log('No se encontraron meta tags, mostrando alerta de bienvenida por defecto');
                showDefaultWelcomeMessage();
                // Marcar como mostrado en esta sesión con timestamp
                sessionStorage.setItem('welcome_shown', 'true');
                sessionStorage.setItem('last_welcome_time', Date.now().toString());
            } else {
                console.log('Alerta de bienvenida ya mostrada en esta sesión, omitiendo');
            }
        }
    }, 1000); // 1 segundo para asegurar que todo esté cargado
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si hay sesión activa (usuario autenticado)
    const hasAuthUser = document.querySelector('meta[name="csrf-token"]');
    if (!hasAuthUser) {
        console.log('No hay sesión activa, limpiando sessionStorage');
        sessionStorage.removeItem('welcome_shown');
        sessionStorage.removeItem('admin_welcome_shown');
    } else {
        // Si hay sesión activa, verificar si es una nueva sesión
        // Comparar con el timestamp de la última vez que se mostró la alerta
        const lastWelcomeTime = sessionStorage.getItem('last_welcome_time');
        const currentTime = Date.now();
        const sessionTimeout = 30 * 60 * 1000; // 30 minutos
        
        // Si ha pasado más de 30 minutos o es la primera vez, limpiar y mostrar
        if (!lastWelcomeTime || (currentTime - parseInt(lastWelcomeTime)) > sessionTimeout) {
            console.log('Nueva sesión detectada o timeout alcanzado, limpiando sessionStorage');
            sessionStorage.removeItem('welcome_shown');
            sessionStorage.removeItem('admin_welcome_shown');
        }
    }
    
    initializeDashboardSystem();
});

// También ejecutar cuando la página termine de cargar (solo como respaldo)
window.addEventListener('load', () => {
    if (!dashboardInitialized) {
        setTimeout(() => {
            initializeDashboardSystem();
        }, 1000);
    }
});