/**
 * Script para el panel de administración
 * Maneja la funcionalidad de invitaciones y gestión de códigos
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar panel administrativo
    initializePanel();
    
    // Mostrar alertas de sesión si existen
    showSessionAlerts();
    
});

/**
 * Inicializar el panel administrativo
 */
function initializePanel() {
    // Registrar event listeners directos
    registerEventListeners();
    
    // Configurar búsquedas en tiempo real
    configurarBusquedasTiempoReal();
}

/**
 * Mostrar alertas de sesión
 */
function showSessionAlerts() {
    // Las alertas de sesión se manejan desde el HTML con Blade
    // Esta función está aquí para futuras mejoras
}


/**
 * Función para alertas con SweetAlert2
 */
function mostrarAlerta(mensaje, tipo = 'error') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: tipo,
            title: tipo === 'success' ? '¡Éxito!' : tipo === 'warning' ? '¡Atención!' : '¡Error!',
            text: mensaje,
            confirmButtonText: '¡Perfecto!',
            confirmButtonColor: '#2f9f37',
            background: '#ffffff',
            color: '#374151'
        });
    } else {
        alert(tipo.toUpperCase() + ': ' + mensaje);
    }
}

/**
 * Validar formulario al enviar - función global
 */
window.validarFormulario = function() {
    const inputs = document.querySelectorAll('.email-input');
    const emails = [];
    const emailsInvalidos = [];
    
    // Recopilar y validar emails
    inputs.forEach(input => {
        const email = input.value.trim();
        if (email) {
            if (validarEmail(email)) {
                emails.push(email.toLowerCase());
            } else {
                emailsInvalidos.push(email);
            }
        }
    });

    // ERROR: No hay emails válidos
    if (emails.length === 0 && emailsInvalidos.length === 0) {
        mostrarAlerta('Por favor, ingresa al menos un correo electrónico válido.', 'error');
        return false;
    }

    // ERROR: Emails con formato inválido
    if (emailsInvalidos.length > 0) {
        const mensajesError = emailsInvalidos.map(email => obtenerMensajeErrorEmail(email));
        const mensajeCompleto = mensajesError.join('\n\n');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Emails con Errores',
                html: `<div style="text-align: left; font-size: 14px; line-height: 1.5;">
                    ${mensajesError.map(msg => `<div style="margin-bottom: 8px; padding: 8px; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px;">${msg}</div>`).join('')}
                </div>`,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Corregir Emails',
                background: '#ffffff',
                width: '600px'
            });
        } else {
            alert('ERROR: Emails con formato inválido:\n\n' + mensajeCompleto);
        }
        return false;
    }

    // ERROR: Verificar duplicados
    const emailsUnicos = [...new Set(emails)];
    if (emailsUnicos.length !== emails.length) {
        const duplicados = emails.filter((email, index) => emails.indexOf(email) !== index);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Emails Duplicados',
                html: `No puedes enviar el mismo correo múltiples veces:<br><strong>${duplicados.join(', ')}</strong>`,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Aceptar',
                background: '#ffffff',
                iconColor: '#dc2626'
            });
        } else {
            alert('ERROR: Se detectaron correos duplicados: ' + duplicados.join(', '));
        }
        return false;
    }

    // ✅ TODOS LOS EMAILS SON VÁLIDOS - Mostrar confirmación
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Enviar invitaciones?',
            text: `Se enviarán ${emailsUnicos.length} invitación(es) por correo electrónico`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2f9f37',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                mostrarLoadingOverlay();
                enviarInvitacionesAjax();
            }
        });
        return false;
    } else {
        if (confirm(`¿Enviar ${emailsUnicos.length} invitación(es)?`)) {
            mostrarLoadingOverlay();
            enviarInvitacionesAjax();
            return false;
        }
        return false;
    }
};

/**
 * Función para validar formato de email con validación estricta de dominio
 */
function validarEmail(email) {
    // Regex básico para formato de email
    const regexBasico = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!regexBasico.test(email)) {
        return false;
    }
    
    // Validación estricta de dominios comunes mal escritos
    const dominiosMalEscritos = [
        // Gmail mal escrito
        /@gmmail\./i, /@gmai\./i, /@gmial\./i, /@gmail\.com\./i, /@gmail\.co\./i,
        /@gmai\.com/i, /@gmmail\.com/i, /@gmial\.com/i, /@gmail\.comm/i,
        /@gmail\.c0m/i, /@gmail\.con/i, /@gmail\.co,/i, /@gmail\.com,/i,
        
        // Yahoo mal escrito
        /@yahoo\.co\./i, /@yahoo\.com\./i, /@yahoo\.c0m/i, /@yahoo\.con/i,
        /@yahhoo\./i, /@yahooo\./i, /@yahoo\.com,/i, /@yahoo\.co,/i,
        
        // Hotmail/Outlook mal escrito
        /@hotmail\.co\./i, /@hotmail\.com\./i, /@hotmail\.c0m/i, /@hotmail\.con/i,
        /@hotmial\./i, /@hotmaill\./i, /@hotmail\.com,/i, /@hotmail\.co,/i,
        /@outlook\.co\./i, /@outlook\.com\./i, /@outlook\.c0m/i, /@outlook\.con/i,
        /@outlok\./i, /@outloo\./i, /@outlook\.com,/i, /@outlook\.co,/i,
        
        // Otros errores comunes
        /@.*\.com\./i, /@.*\.co\./i, /@.*\.c0m/i, /@.*\.con/i,
        /@.*\.com,/i, /@.*\.co,/i, /@.*\.com;/i, /@.*\.co;/i,
        /@.*\.com\s/i, /@.*\.co\s/i, /@.*\.com\.com/i, /@.*\.co\.co/i,
        
        // Dominios con caracteres extra
        /@.*\.\w{3,}\./i, /@.*\.\w{2,}\./i, /@.*\.\w{1,}\./i,
        
        // Espacios y caracteres especiales
        /\s+@/, /@\s+/, /@.*\s+\./, /@.*\.\s+/, /@.*\.\w+\s+/,
        
        // Múltiples puntos
        /@.*\.{2,}/i, /@.*\.\w+\.{2,}/i
    ];
    
    // Verificar si contiene algún patrón mal escrito
    for (const patron of dominiosMalEscritos) {
        if (patron.test(email)) {
            return false;
        }
    }
    
    // Validación adicional: verificar que no haya caracteres extra al final
    const partes = email.split('@');
    if (partes.length !== 2) return false;
    
    const dominio = partes[1];
    
    // Verificar que el dominio no termine con caracteres extra
    if (dominio.match(/\.\w+[^\w\s]$/) || dominio.includes(' ') || dominio.includes(',')) {
        return false;
    }
    
    // Verificar que el dominio tenga al menos un punto y extensión válida
    const extensionesValidas = ['com', 'org', 'net', 'edu', 'gov', 'mil', 'int', 'co', 'uk', 'de', 'fr', 'es', 'it', 'br', 'mx', 'ar', 'cl', 'pe', 've', 'ec', 'uy', 'py', 'bo', 'cr', 'pa', 'gt', 'hn', 'sv', 'ni', 'cu', 'do', 'pr', 'jm', 'tt', 'bb', 'gd', 'lc', 'vc', 'ag', 'bs', 'bz', 'dm', 'kn', 'ai', 'vg', 'ky', 'tc', 'fk', 'gs', 'sh', 'ac', 'io', 'me', 'tv', 'cc', 'info', 'biz', 'name', 'pro', 'aero', 'coop', 'museum', 'travel', 'jobs', 'mobi', 'asia', 'cat', 'tel', 'xxx', 'post', 'geo', 'asia', 'jobs', 'mobi', 'tel', 'travel', 'xxx', 'aero', 'biz', 'coop', 'info', 'museum', 'name', 'pro'];
    
    const extensionDominio = dominio.split('.').pop().toLowerCase();
    if (!extensionesValidas.includes(extensionDominio)) {
        return false;
    }
    
    return true;
}

/**
 * Función para obtener mensaje de error específico del email
 */
function obtenerMensajeErrorEmail(email) {
    // Verificar errores comunes específicos
    if (email.includes('@gmmail') || email.includes('@gmai') || email.includes('@gmial')) {
        return `${email} - Error: Dominio mal escrito. ¿Quisiste decir "gmail.com"?`;
    }
    
    if (email.includes('@yahhoo') || email.includes('@yahooo')) {
        return `${email} - Error: Dominio mal escrito. ¿Quisiste decir "yahoo.com"?`;
    }
    
    if (email.includes('@hotmial') || email.includes('@hotmaill')) {
        return `${email} - Error: Dominio mal escrito. ¿Quisiste decir "hotmail.com"?`;
    }
    
    if (email.includes('@outlok') || email.includes('@outloo')) {
        return `${email} - Error: Dominio mal escrito. ¿Quisiste decir "outlook.com"?`;
    }
    
    if (email.includes('.com.') || email.includes('.co.')) {
        return `${email} - Error: Punto extra en el dominio.`;
    }
    
    if (email.includes('.com,') || email.includes('.co,')) {
        return `${email} - Error: Coma en el dominio.`;
    }
    
    if (email.includes('.c0m') || email.includes('.con')) {
        return `${email} - Error: Extensión mal escrita (c0m/con en lugar de com).`;
    }
    
    if (email.includes('.comm')) {
        return `${email} - Error: Extensión mal escrita (.comm en lugar de .com).`;
    }
    
    if (email.includes(' ') || email.includes(',')) {
        return `${email} - Error: Contiene espacios o comas no válidas.`;
    }
    
    if (email.match(/\.{2,}/)) {
        return `${email} - Error: Múltiples puntos consecutivos en el dominio.`;
    }
    
    // Error genérico
    return `${email} - Formato de email inválido.`;
}

/**
 * Función para mostrar loading overlay
 */
function mostrarLoadingOverlay() {
    showLoading('invitation-loading', 'Enviando invitaciones...');
}

/**
 * Función para ocultar loading overlay
 */
function ocultarLoadingOverlay() {
    hideLoading('invitation-loading');
}

/**
 * Función para enviar invitaciones por AJAX
 */
function enviarInvitacionesAjax() {
    const form = document.getElementById('invitationForm');
    const formData = new FormData(form);
    
    // Verificar CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        ocultarLoadingOverlay();
        mostrarAlerta('Error de seguridad. Por favor, recarga la página.', 'error');
        return;
    }

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            let errorMessage = `Error del servidor: HTTP ${response.status}`;
            
            try {
                const errorData = await response.json();
                if (errorData.message) {
                    errorMessage = errorData.message;
                } else if (errorData.errors) {
                    // Si hay errores de validación, mostrarlos de forma clara
                    const validationErrors = Object.values(errorData.errors).flat();
                    errorMessage = validationErrors.join('. ');
                }
            } catch (e) {
                // Si no se puede parsear el JSON, usar el mensaje por defecto
                if (response.status === 400) {
                    errorMessage = 'Error de validación. Verifique los datos ingresados.';
                } else if (response.status === 500) {
                    errorMessage = 'Error interno del servidor. Intente nuevamente.';
                }
            }
            
            throw new Error(errorMessage);
        }
        
        return response.json();
    })
    .then(data => {
        // Ocultar loading overlay
        ocultarLoadingOverlay();

        // Mostrar datos de respuesta
        console.log('Respuesta del servidor:', data);

        if (data.success) {
            // Mostrar mensaje de éxito
            mostrarAlerta(data.message || 'Invitaciones enviadas correctamente', 'success');
            
            // Limpiar el formulario
            const inputs = document.querySelectorAll('.email-input');
            inputs.forEach(input => input.value = '');
            
            // Recargar la página después de 2 segundos para ver los códigos actualizados
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Mostrar mensaje de error
            mostrarAlerta(data.message || 'Error al enviar las invitaciones', 'error');
        }
    })
    .catch(error => {
        // Ocultar loading overlay
        ocultarLoadingOverlay();
        
        // Mostrar mensaje de error más específico
        let errorMessage = 'Error al enviar las invitaciones. Por favor, intenta de nuevo.';
        if (error.message) {
            errorMessage = error.message;
        }
        
        mostrarAlerta(errorMessage, 'error');
    });
}

/**
 * Registrar event listeners
 */
function registerEventListeners() {
    // Validación en tiempo real de emails
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('email-input')) {
            const email = e.target.value.trim();
            const input = e.target;
            
            // Limpiar clases anteriores
            input.classList.remove('border-red-500', 'border-green-500', 'border-yellow-500');
            
            if (email === '') {
                // Campo vacío - sin validación
                return;
            }
            
            if (!validarEmail(email)) {
                // Email inválido
                input.classList.add('border-red-500');
                input.title = obtenerMensajeErrorEmail(email);
            } else {
                // Email válido
                input.classList.add('border-green-500');
                input.title = 'Email válido';
            }
        }
    });
    
    // Manejar clics en botones de eliminación
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const email = e.target.getAttribute('data-email');
            const form = e.target.closest('.delete-form');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar código?',
                    text: `${email}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(`¿Eliminar código para ${email}?`)) {
                    form.submit();
                }
            }
        }
    });
}

/**
 * Configurar búsquedas en tiempo real
 */
function configurarBusquedasTiempoReal() {
    // Búsqueda de códigos activos
    const activeSearchInput = document.getElementById('activeSearchInput');
    const clearActiveSearch = document.getElementById('clearActiveSearch');
    const activeSearchResults = document.getElementById('activeSearchResults');
    const activeCodesContainer = document.getElementById('activeCodesContainer');
    const activeCodesPagination = document.getElementById('activeCodesPagination');
    
    let activeSearchTimeout;
    
    if (activeSearchInput) {
        activeSearchInput.addEventListener('input', function() {
            clearTimeout(activeSearchTimeout);
            const searchTerm = this.value.trim();
            
            if (searchTerm.length > 0) {
                activeSearchTimeout = setTimeout(() => {
                    buscarCodigosActivos(searchTerm);
                }, 300); // 300ms de delay
                
                clearActiveSearch.style.display = 'block';
                activeCodesPagination.style.display = 'none';
            } else {
                mostrarTodosLosCodigosActivos();
                clearActiveSearch.style.display = 'none';
                activeSearchResults.style.display = 'none';
                activeCodesPagination.style.display = 'block';
            }
        });
    }
    
    if (clearActiveSearch) {
        clearActiveSearch.addEventListener('click', function() {
            activeSearchInput.value = '';
            mostrarTodosLosCodigosActivos();
            this.style.display = 'none';
            activeSearchResults.style.display = 'none';
            activeCodesPagination.style.display = 'block';
        });
    }
    
    // Búsqueda de historial
    const historySearchInput = document.getElementById('historySearchInput');
    const clearHistorySearch = document.getElementById('clearHistorySearch');
    const historySearchResults = document.getElementById('historySearchResults');
    const historyCodesContainer = document.getElementById('historyCodesContainer');
    const historyCodesPagination = document.getElementById('historyCodesPagination');
    
    let historySearchTimeout;
    
    if (historySearchInput) {
        historySearchInput.addEventListener('input', function() {
            clearTimeout(historySearchTimeout);
            const searchTerm = this.value.trim();
            
            if (searchTerm.length > 0) {
                historySearchTimeout = setTimeout(() => {
                    buscarHistorial(searchTerm);
                }, 300); // 300ms de delay
                
                clearHistorySearch.style.display = 'block';
                historyCodesPagination.style.display = 'none';
            } else {
                mostrarTodoElHistorial();
                clearHistorySearch.style.display = 'none';
                historySearchResults.style.display = 'none';
                historyCodesPagination.style.display = 'block';
            }
        });
    }
    
    if (clearHistorySearch) {
        clearHistorySearch.addEventListener('click', function() {
            historySearchInput.value = '';
            mostrarTodoElHistorial();
            this.style.display = 'none';
            historySearchResults.style.display = 'none';
            historyCodesPagination.style.display = 'block';
        });
    }
}

/**
 * Buscar códigos activos
 */
function buscarCodigosActivos(searchTerm) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token no encontrado');
        return;
    }
    
    fetch(window.adminRoutes.searchActiveCodes, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            search: searchTerm
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('activeCodesContainer').innerHTML = data.html;
            document.getElementById('activeSearchResults').innerHTML = 
                `Buscando: "<strong>${searchTerm}</strong>" - ${data.count} resultado(s)`;
            document.getElementById('activeSearchResults').style.display = 'block';
        } else {
            console.error('Error en búsqueda:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

/**
 * Buscar en historial
 */
function buscarHistorial(searchTerm) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token no encontrado');
        return;
    }
    
    fetch(window.adminRoutes.searchHistoryCodes, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            search: searchTerm
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('historyCodesContainer').innerHTML = data.html;
            document.getElementById('historySearchResults').innerHTML = 
                `Buscando: "<strong>${searchTerm}</strong>" - ${data.count} resultado(s)`;
            document.getElementById('historySearchResults').style.display = 'block';
        } else {
            console.error('Error en búsqueda:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

/**
 * Mostrar todos los códigos activos
 */
function mostrarTodosLosCodigosActivos() {
    // Recargar la página para mostrar todos los códigos con paginación
    window.location.reload();
}

/**
 * Mostrar todo el historial
 */
function mostrarTodoElHistorial() {
    // Recargar la página para mostrar todo el historial con paginación
    window.location.reload();
}



