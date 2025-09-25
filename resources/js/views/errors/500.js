// Vista 500 - Error del servidor
console.log('🚀🚀🚀 CARGANDO VISTA 500 🚀🚀🚀');
console.log('✅ Archivo 500.js cargado correctamente - VERSIÓN ACTUALIZADA');
console.log('🔍 Iniciando verificación de Apache...');
console.log('⏰ Timestamp:', new Date().toISOString());

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista 500 cargada correctamente');
    
    // Inicializar funcionalidades de la vista 500
    initialize500View();
});

/**
 * Inicializar todas las funcionalidades de la vista 500
 */
function initialize500View() {
    // Configurar animaciones de entrada
    setupAnimations();
    
    // Configurar eventos de teclado
    setupKeyboardEvents();
    
    // Configurar indicadores de estado
    setupStatusIndicators();
}

/**
 * Configurar animaciones de entrada
 */
function setupAnimations() {
    const elements = document.querySelectorAll('.error-500-logo, .error-500-title, .error-500-description, .error-500-steps');
    
    elements.forEach((element, index) => {
        // Aplicar animación de entrada con delay escalonado
        setTimeout(() => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 100);
        }, index * 200);
    });
}

/**
 * Configurar eventos de teclado
 */
function setupKeyboardEvents() {
    document.addEventListener('keydown', function(e) {
        // Permitir recargar la página con Escape
        if (e.key === 'Escape') {
            e.preventDefault();
            window.location.reload();
        }
    });
}

/**
 * Configurar indicadores de estado
 */
function setupStatusIndicators() {
    const indicators = document.querySelectorAll('.error-500-status-indicator');
    
    indicators.forEach((indicator, index) => {
        // Animación de pulso para los indicadores
        setTimeout(() => {
            indicator.style.animation = 'pulse 2s infinite';
        }, 1000 + (index * 200));
    });
}

/**
 * Función para mostrar información de debug (solo en desarrollo)
 */
function showDebugInfo() {
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('========================');
        console.log('DEBUG - Vista 500');
        console.log('User Agent:', navigator.userAgent);
        console.log('Timestamp:', new Date().toISOString());
        console.log('Error: Servicio temporalmente no disponible');
        console.log('========================');
    }
}

/**
 * Función para verificar el estado de los servicios
 */
function checkServicesStatus() {
    const steps = document.querySelectorAll('.error-500-step');
    
    // Función para cambiar el estado de un indicador
    function updateIndicator(stepIndex, status) {
        const step = steps[stepIndex];
        if (step) {
            const indicator = step.querySelector('.error-500-status-indicator');
            if (indicator) {
                indicator.classList.remove('status-offline', 'status-checking', 'status-online');
                indicator.classList.add(status);
            }
        }
    }
    
    // Función para mostrar alerta de éxito con SweetAlert2
    function showSuccessAlert() {
        // Verificar si SweetAlert2 está disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¡Conexión Restablecida!',
                text: 'Querido usuario, usted ha vuelto a tener conexión. El sistema está funcionando correctamente.',
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#3B4A8C',
                background: '#ffffff',
                customClass: {
                    popup: 'swal2-popup-custom',
                    title: 'swal2-title-custom',
                    confirmButton: 'swal2-confirm-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut animate__faster'
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                focusConfirm: true,
                timer: null,
                didOpen: () => {
                    // Agregar estilos personalizados
                    const style = document.createElement('style');
                    style.textContent = `
                        .swal2-popup-custom {
                            border-radius: 20px !important;
                            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
                            border: 1px solid #ecf0f1 !important;
                        }
                        .swal2-title-custom {
                            color: #2c3e50 !important;
                            font-size: 1.8rem !important;
                            font-weight: 600 !important;
                            margin-bottom: 1rem !important;
                        }
                        .swal2-confirm-custom {
                            background: #3B4A8C !important;
                            border: none !important;
                            border-radius: 25px !important;
                            padding: 12px 30px !important;
                            font-size: 1rem !important;
                            font-weight: 600 !important;
                            transition: all 0.3s ease !important;
                        }
                        .swal2-confirm-custom:hover {
                            background: #2a3658 !important;
                            transform: translateY(-2px) !important;
                            box-shadow: 0 8px 25px rgba(59, 74, 140, 0.4) !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        } else {
            console.error('SweetAlert2 no está disponible');
            showFallbackAlert();
        }
    }
    
    // Función de respaldo en caso de que SweetAlert2 falle
    function showFallbackAlert() {
        alert('¡Conexión Restablecida!\n\nQuerido usuario, usted ha vuelto a tener conexión. El sistema está funcionando correctamente.');
        window.location.reload();
    }
    
    // Función para verificar MySQL
    async function checkMySQLStatus() {
        try {
            const response = await fetch('/check-database-connection');
            const data = await response.json();
            return data.status === 'success';
        } catch (error) {
            console.log('MySQL no disponible:', error);
            return false;
        }
    }
    
    // Función para verificar Apache (servidor web)
    async function checkApacheStatus() {
        try {
            console.log('🔍 Verificando Apache...');
            
            // Crear un AbortController para timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 3000);
            
            // Verificar si Apache está funcionando usando una petición a una ruta específica
            // Usar mode: 'no-cors' para evitar problemas de CORS
            const response = await fetch('http://localhost:80/xampp/', { 
                method: 'HEAD',
                cache: 'no-cache',
                mode: 'no-cors', // Esto evita el error de CORS
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            console.log('📡 Respuesta de Apache:', response.status, response.ok);
            
            // Con mode: 'no-cors', no podemos leer el status, pero si no hay error significa que Apache responde
            // Si llegamos aquí sin error, Apache está funcionando
            const isWorking = true; // Si no hay error, Apache está funcionando
            console.log('✅ Apache funcionando:', isWorking);
            return isWorking;
        } catch (error) {
            console.log('❌ Apache no disponible:', error.message);
            return false;
        }
    }
    
    // Variable para controlar si ya se mostró la alerta
    let alertShown = false;
    
    // Función para verificar ambos servicios y actualizar pasos
    async function verifyAndUpdateSteps() {
        console.log('🚀 Iniciando verificación de servicios...');
        
        // Verificar solo Apache y MySQL
        const [apacheStatus, mysqlStatus] = await Promise.all([
            checkApacheStatus(),
            checkMySQLStatus()
        ]);
        
        console.log('📊 Estado de servicios:', { 
            apacheStatus, 
            mysqlStatus,
            ambosFuncionan: apacheStatus && mysqlStatus
        });
        
        // Paso 1 (XAMPP): Siempre verde porque estás en la página
        updateIndicator(0, 'status-online');
        
        // Paso 2 (Apache): Verde si funciona, naranja si no
        updateIndicator(1, apacheStatus ? 'status-online' : 'status-checking');
        
        // Paso 3 (MySQL): Verde si funciona, naranja si no
        updateIndicator(2, mysqlStatus ? 'status-online' : 'status-checking');
        
        // Paso 4 (Iniciar proyecto): Solo verde si AMBOS servicios están funcionando
        if (apacheStatus && mysqlStatus) {
            updateIndicator(3, 'status-online');
            
            // Mostrar alerta de éxito solo una vez
            if (!alertShown) {
                alertShown = true;
                setTimeout(() => {
                    showSuccessAlert();
                }, 500);
            }
        } else {
            // Si alguno de los servicios no está funcionando, el paso 4 debe estar rojo
            updateIndicator(3, 'status-offline');
            alertShown = false; // Resetear para cuando estén todos funcionando
        }
    }
    
    // Iniciar verificación continua
    verifyAndUpdateSteps();
    
    // Verificar cada 3 segundos para detectar cuando se prenden los servicios
    setInterval(verifyAndUpdateSteps, 3000);
}

// Mostrar información de debug en desarrollo
showDebugInfo();

// Iniciar verificación de servicios después de 2 segundos
setTimeout(checkServicesStatus, 2000);