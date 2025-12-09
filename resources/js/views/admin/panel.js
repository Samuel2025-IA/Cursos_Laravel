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
    
    // Inicializar pestañas y funcionalidad de inserción masiva
    initializeTabs();
    initializeExcelUpload();
    initializeBulkForm();
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
 * Mostrar alerta de espera mientras se procesa una tarea
 */
function mostrarAlertaEspera(titulo = 'Procesando...', mensaje = 'Por favor espera mientras completamos la operación.') {
    if (typeof Swal === 'undefined') {
        return;
    }

    Swal.fire({
        title: titulo,
        text: mensaje,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
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

    // TODOS LOS EMAILS SON VÁLIDOS - Mostrar confirmación
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
        console.log('Respuesta del servidor (Inserción Individual):', data);
        
        const errors = data.errors || [];
        const sentCount = data.sent_count || 0;
        const successEmails = data.success_emails || [];

        // Si hay errores, mostrarlos siempre, incluso si success es true
        if (errors.length > 0) {
            console.log('Hay errores en la respuesta:', errors);
            
            let errorMessage = '';
            if (sentCount > 0) {
                errorMessage = `Se enviaron ${sentCount} invitación(es) correctamente, pero algunos correos tuvieron problemas:\n\n`;
            } else {
                errorMessage = 'No se pudo enviar ninguna invitación:\n\n';
            }
            
            // Construir mensaje de errores con sección colapsable
            let errorDetails = `
                <div style="margin-top: 20px;">
                    <button 
                        onclick="this.classList.toggle('active'); 
                                 const details = this.nextElementSibling; 
                                 const arrow = this.querySelector('.toggle-arrow');
                                 if(details.style.display === 'none') { 
                                     details.style.display = 'block'; 
                                     arrow.style.transform = 'rotate(90deg)';
                                     this.style.background = '#fee2e2';
                                     this.style.borderColor = '#fca5a5';
                                 } else { 
                                     details.style.display = 'none'; 
                                     arrow.style.transform = 'rotate(0deg)';
                                     this.style.background = '#f3f4f6';
                                     this.style.borderColor = '#e5e7eb';
                                 }" 
                        style="width: 100%; padding: 14px 16px; background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; color: #374151; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; margin-bottom: 10px;"
                        onmouseover="this.style.background='#e5e7eb'; this.style.borderColor='#d1d5db';"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='#f3f4f6'; this.style.borderColor='#e5e7eb'; } else { this.style.background='#fee2e2'; this.style.borderColor='#fca5a5'; }">
                        <span style="display: flex; align-items: center; gap: 10px;">
                            <svg style="width: 20px; height: 20px; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span>Ver detalles de los errores (${errors.length})</span>
                        </span>
                        <svg class="toggle-arrow" style="width: 16px; height: 16px; color: #6b7280; transition: transform 0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div style="display: none; text-align: left; max-height: 300px; overflow-y: auto; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; scrollbar-width: thin;">
            `;
            
            errors.forEach((error, index) => {
                // Determinar el tipo de error basado en el contenido del mensaje
                let errorIcon = '';
                let errorStyle = '';
                
                if (error.includes('ya está registrado en la base de datos')) {
                    errorIcon = '<svg style="width: 18px; height: 18px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"></path></svg>';
                    errorStyle = 'background: #fef2f2; border-left: 4px solid #dc2626;';
                } else if (error.includes('Ya tiene un código de invitación enviado')) {
                    errorIcon = '<svg style="width: 18px; height: 18px; color: #f59e0b;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
                    errorStyle = 'background: #fffbeb; border-left: 4px solid #f59e0b;';
                } else if (error.includes('Dominio mal escrito') || error.includes('Quisiste decir')) {
                    errorIcon = '<svg style="width: 18px; height: 18px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                    errorStyle = 'background: #fef2f2; border-left: 4px solid #dc2626;';
                } else if (error.includes('formato del correo') || error.includes('no es válido') || 
                           error.includes('espacios') || error.includes('puntos consecutivos') || 
                           error.includes('símbol') || error.includes('Punto extra') || 
                           error.includes('Coma en lugar') || error.includes('caracteres especiales')) {
                    errorIcon = '<svg style="width: 18px; height: 18px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                    errorStyle = 'background: #fef2f2; border-left: 4px solid #dc2626;';
                } else if (error.includes('Celda vacía') || error.includes('sin contenido')) {
                    errorIcon = '<svg style="width: 18px; height: 18px; color: #9ca3af;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>';
                    errorStyle = 'background: #f9fafb; border-left: 4px solid #9ca3af;';
                } else {
                    errorIcon = '<svg style="width: 18px; height: 18px; color: #f59e0b;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                    errorStyle = 'background: #fffbeb; border-left: 4px solid #f59e0b;';
                }
                
                errorDetails += `
                    <div style="padding: 12px; margin-bottom: 10px; ${errorStyle} border-radius: 6px; font-size: 13px; line-height: 1.6; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: start; gap: 10px;">
                            <span style="flex-shrink: 0;">${errorIcon}</span>
                            <span style="color: #374151; word-break: break-word;">${error}</span>
                        </div>
                    </div>
                `;
            });
            
            errorDetails += '</div></div>';
            
            Swal.fire({
                icon: sentCount > 0 ? 'warning' : 'error',
                title: sentCount > 0 ? 'Procesamiento con advertencias' : 'Error al procesar',
                html: `
                    <div style="text-align: left;">
                        <p style="margin-bottom: 15px;">
                            ${sentCount > 0 ? `Se enviaron <strong style="color: #2f9f37;">${sentCount}</strong> invitación(es) correctamente.` : 'No se pudo enviar ninguna invitación.'}
                        </p>
                        ${errorDetails}
                    </div>
                `,
                confirmButtonText: 'Entendido',
                confirmButtonColor: sentCount > 0 ? '#2f9f37' : '#dc2626',
                width: '700px'
            });
            
            // Limpiar el formulario solo si hubo al menos un éxito
            if (sentCount > 0) {
                const inputs = document.querySelectorAll('.email-input');
                inputs.forEach(input => input.value = '');
                
                // Recargar la página después de 3 segundos
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        } else if (data.success) {
            // Todo salió bien
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message || 'Invitaciones enviadas correctamente',
                confirmButtonText: '¡Perfecto!',
                confirmButtonColor: '#2f9f37'
            });
            
            // Limpiar el formulario
            const inputs = document.querySelectorAll('.email-input');
            inputs.forEach(input => input.value = '');
            
            // Recargar la página después de 2 segundos para ver los códigos actualizados
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Error general
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al enviar las invitaciones',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc2626'
            });
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
    console.log('Configurando búsquedas en tiempo real...');
    
    // Búsqueda de códigos activos
    const activeSearchInput = document.getElementById('activeSearchInput');
    const clearActiveSearch = document.getElementById('clearActiveSearch');
    const activeSearchResults = document.getElementById('activeSearchResults');
    const activeCodesContainer = document.getElementById('activeCodesContainer');
    const activeCodesPagination = document.getElementById('activeCodesPagination');
    
    console.log('Elementos encontrados:', {
        activeSearchInput: !!activeSearchInput,
        clearActiveSearch: !!clearActiveSearch,
        activeSearchResults: !!activeSearchResults,
        activeCodesContainer: !!activeCodesContainer,
        activeCodesPagination: !!activeCodesPagination
    });
    
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
                if (activeCodesPagination) {
                    activeCodesPagination.style.display = 'none';
                }
            } else {
                mostrarTodosLosCodigosActivos();
                clearActiveSearch.style.display = 'none';
                activeSearchResults.style.display = 'none';
                if (activeCodesPagination) {
                    activeCodesPagination.style.display = 'block';
                }
            }
        });
    }
    
    if (clearActiveSearch) {
        clearActiveSearch.addEventListener('click', function() {
            activeSearchInput.value = '';
            mostrarTodosLosCodigosActivos();
            this.style.display = 'none';
            activeSearchResults.style.display = 'none';
            if (activeCodesPagination) {
                activeCodesPagination.style.display = 'block';
            }
        });
    }
    
    // Botón de búsqueda de códigos activos
    const activeSearchButton = document.getElementById('activeSearchButton');
    console.log('Botón de búsqueda activos encontrado:', !!activeSearchButton);
    if (activeSearchButton) {
        activeSearchButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botón de búsqueda activos clickeado');
            const searchTerm = activeSearchInput.value.trim();
            console.log('Término de búsqueda activos:', searchTerm);
            if (searchTerm.length > 0) {
                buscarCodigosActivos(searchTerm);
            } else {
                console.log('No hay término de búsqueda');
                if (activeCodesPagination) {
                    activeCodesPagination.style.display = 'block';
                }
            }
        });
        
        // También añadir evento de Enter en el input
        if (activeSearchInput) {
            activeSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    console.log('Enter presionado en búsqueda activos');
                    const searchTerm = this.value.trim();
                    if (searchTerm.length > 0) {
                        buscarCodigosActivos(searchTerm);
                        if (activeCodesPagination) {
                            activeCodesPagination.style.display = 'none';
                        }
                    }
                }
            });
        }
    }
    
    // Búsqueda de historial
    const historySearchInput = document.getElementById('historySearchInput');
    const clearHistorySearch = document.getElementById('clearHistorySearch');
    const historySearchResults = document.getElementById('historySearchResults');
    const historyCodesContainer = document.getElementById('historyCodesContainer');
    const historyCodesPagination = document.getElementById('historyCodesPagination');
    
    console.log('Elementos de historial encontrados:', {
        historySearchInput: !!historySearchInput,
        clearHistorySearch: !!clearHistorySearch,
        historySearchResults: !!historySearchResults,
        historyCodesContainer: !!historyCodesContainer,
        historyCodesPagination: !!historyCodesPagination
    });
    
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
    
    // Botón de búsqueda de historial
    const historySearchButton = document.getElementById('historySearchButton');
    console.log('Botón de búsqueda historial encontrado:', !!historySearchButton);
    if (historySearchButton) {
        historySearchButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botón de búsqueda historial clickeado');
            const searchTerm = historySearchInput.value.trim();
            console.log('Término de búsqueda historial:', searchTerm);
            if (searchTerm.length > 0) {
                buscarHistorial(searchTerm);
            } else {
                console.log('No hay término de búsqueda');
            }
        });
        
        // También añadir evento de Enter en el input
        if (historySearchInput) {
            historySearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    console.log('Enter presionado en búsqueda historial');
                    const searchTerm = this.value.trim();
                    if (searchTerm.length > 0) {
                        buscarHistorial(searchTerm);
                    }
                }
            });
        }
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
    console.log('Buscando códigos activos:', searchTerm);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token no encontrado');
        return;
    }
    
    console.log('Ruta de búsqueda:', window.adminRoutes.searchActiveCodes);
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
            const pagination = document.getElementById('activeCodesPagination');
            if (pagination) {
                pagination.style.display = 'none';
            }
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
    console.log('Buscando historial:', searchTerm);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token no encontrado');
        return;
    }
    
    console.log('Ruta de búsqueda historial:', window.adminRoutes.searchHistoryCodes);
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

/**
 * Inicializar funcionalidad de pestañas
 */
function initializeTabs() {
    const tabIndividual = document.getElementById('tab-individual');
    const tabMasiva = document.getElementById('tab-masiva');
    const contentIndividual = document.getElementById('content-individual');
    const contentMasiva = document.getElementById('content-masiva');

    console.log('Inicializando pestañas:', {
        tabIndividual: !!tabIndividual,
        tabMasiva: !!tabMasiva,
        contentIndividual: !!contentIndividual,
        contentMasiva: !!contentMasiva
    });

    if (!tabIndividual || !tabMasiva || !contentIndividual || !contentMasiva) {
        console.warn('No se encontraron todos los elementos de pestañas');
        return; // Las pestañas no existen en esta página
    }

    // Cambiar a pestaña individual
    tabIndividual.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Click en pestaña individual');
        tabIndividual.classList.add('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
        tabIndividual.classList.remove('border-transparent', 'text-gray-500');
        tabMasiva.classList.remove('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
        tabMasiva.classList.add('border-transparent', 'text-gray-500');
        
        contentIndividual.classList.remove('hidden');
        contentMasiva.classList.add('hidden');
    });

    // Cambiar a pestaña masiva
    tabMasiva.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Click en pestaña masiva');
        tabMasiva.classList.add('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
        tabMasiva.classList.remove('border-transparent', 'text-gray-500');
        tabIndividual.classList.remove('active', 'border-[#2f9f37]', 'text-[#2f9f37]');
        tabIndividual.classList.add('border-transparent', 'text-gray-500');
        
        contentMasiva.classList.remove('hidden');
        contentIndividual.classList.add('hidden');
        
        // Re-inicializar la carga de archivos cuando se muestra la pestaña
        setTimeout(() => {
            initializeExcelUpload();
        }, 100);
    });
    
    console.log('Pestañas inicializadas correctamente');
}

/**
 * Inicializar funcionalidad de carga de archivo Excel
 */
function initializeExcelUpload() {
    console.log('Inicializando carga de archivo Excel...');
    
    const excelFileInput = document.getElementById('excel_file');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const downloadTemplate = document.getElementById('download-template');
    const fileUploadArea = document.getElementById('file-upload-area');
    const fileUploadIcon = document.getElementById('file-upload-icon');
    const fileUploadText = document.getElementById('file-upload-text');
    const fileUploadHint = document.getElementById('file-upload-hint');
    const fileSelectedInfo = document.getElementById('file-selected-info');
    const removeFileBtn = document.getElementById('remove-file-btn');

    console.log('Elementos encontrados:', {
        excelFileInput: !!excelFileInput,
        fileName: !!fileName,
        fileSize: !!fileSize,
        fileUploadArea: !!fileUploadArea,
        fileUploadIcon: !!fileUploadIcon,
        fileUploadText: !!fileUploadText,
        fileUploadHint: !!fileUploadHint,
        fileSelectedInfo: !!fileSelectedInfo,
        removeFileBtn: !!removeFileBtn
    });

    if (!excelFileInput) {
        console.warn('Input de archivo no encontrado');
        return; // El input no existe en esta página
    }

    /**
     * Función para formatear el tamaño del archivo
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    /**
     * Función para mostrar el archivo seleccionado
     */
    function showSelectedFile(file) {
        console.log('Mostrando archivo seleccionado:', file ? file.name : 'ninguno');
        
        if (file) {
            // Mostrar información del archivo
            if (fileName) fileName.textContent = file.name;
            if (fileSize) fileSize.textContent = formatFileSize(file.size);
            
            console.log('Archivo:', file.name, 'Tamaño:', formatFileSize(file.size));
            
            // Ocultar elementos iniciales
            if (fileUploadIcon) {
                fileUploadIcon.style.display = 'none';
                console.log('Icono ocultado');
            }
            if (fileUploadText) {
                fileUploadText.style.display = 'none';
                console.log('Texto ocultado');
            }
            if (fileUploadHint) {
                fileUploadHint.style.display = 'none';
                console.log('Hint ocultado');
            }
            
            // Mostrar información del archivo seleccionado
            if (fileSelectedInfo) {
                fileSelectedInfo.classList.remove('hidden');
                console.log('Info del archivo mostrada');
            }
            
            // Cambiar estilo del área de carga
            if (fileUploadArea) {
                fileUploadArea.classList.remove('border-gray-300');
                fileUploadArea.classList.add('border-green-500', 'bg-green-50');
                console.log('Estilo del área cambiado a verde');
            }
        } else {
            console.log('Ocultando información del archivo');
            
            // Ocultar información del archivo
            if (fileSelectedInfo) fileSelectedInfo.classList.add('hidden');
            
            // Mostrar elementos iniciales
            if (fileUploadIcon) fileUploadIcon.style.display = 'flex';
            if (fileUploadText) fileUploadText.style.display = 'flex';
            if (fileUploadHint) fileUploadHint.style.display = 'block';
            
            // Restaurar estilo del área de carga
            if (fileUploadArea) {
                fileUploadArea.classList.remove('border-green-500', 'bg-green-50');
                fileUploadArea.classList.add('border-gray-300');
            }
        }
    }

    // Mostrar archivo seleccionado cuando cambia el input
    excelFileInput.addEventListener('change', function(e) {
        console.log('Evento change disparado en input de archivo');
        console.log('Archivos:', e.target.files);
        
        if (e.target.files && e.target.files.length > 0) {
            console.log('Archivo seleccionado:', e.target.files[0].name);
            showSelectedFile(e.target.files[0]);
        } else {
            console.log('No hay archivo seleccionado');
            showSelectedFile(null);
        }
    });
    
    console.log('Event listener de cambio agregado al input de archivo');

    // Botón para eliminar archivo seleccionado
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Limpiar el input
            excelFileInput.value = '';
            
            // Ocultar información del archivo
            showSelectedFile(null);
        });
    }

    // Soporte para drag and drop
    if (fileUploadArea) {
        // Prevenir comportamiento por defecto
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // Cambiar estilo cuando se arrastra sobre el área
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, function() {
                fileUploadArea.classList.add('border-[#2f9f37]', 'bg-green-50');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, function() {
                if (!excelFileInput.files || excelFileInput.files.length === 0) {
                    fileUploadArea.classList.remove('border-[#2f9f37]', 'bg-green-50');
                }
            });
        });

        // Manejar el drop
        fileUploadArea.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                excelFileInput.files = files;
                showSelectedFile(files[0]);
            }
        });
    }

    // Descargar plantilla de ejemplo - ahora usa la ruta del servidor
    // El enlace ya está configurado en el HTML, solo necesitamos asegurarnos de que funcione
    if (downloadTemplate) {
        // El enlace ya tiene la ruta correcta, no necesitamos hacer nada más
        // Pero podemos agregar un log para debugging si es necesario
        downloadTemplate.addEventListener('click', function(e) {
            console.log('Descargando plantilla de ejemplo...');
            // El navegador manejará la descarga automáticamente
        });
    }
}

// Las funciones initializeTabs(), initializeExcelUpload() e initializeBulkForm()
// ahora se llaman desde initializePanel() que se ejecuta en el DOMContentLoaded principal

/**
 * Inicializar formulario de inserción masiva
 */
function initializeBulkForm() {
    const bulkForm = document.getElementById('bulkInvitationForm');
    const loadingOverlay = document.getElementById('invitation-loading');
    
    if (!bulkForm || !loadingOverlay) {
        return;
    }
    
    bulkForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('excel_file');
        
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un archivo (CSV, TXT o Excel).',
                    confirmButtonColor: '#dc2626'
                });
            }
            return false;
        }
        
        // Mostrar loading overlay
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
            const loadingText = loadingOverlay.querySelector('.loading-text');
            if (loadingText) {
                loadingText.textContent = 'Procesando archivo...';
            }
        }
        
        // Mostrar alerta de espera
        mostrarAlertaEspera(
            'Procesando archivo...',
            'Estamos leyendo la información del archivo masivo. Esto puede tardar unos segundos.'
        );
        
        // Enviar por AJAX
        const formData = new FormData(bulkForm);
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        if (!csrfToken) {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            mostrarAlerta('Error de seguridad. Por favor, recarga la página.', 'error');
            return false;
        }
        
        fetch(bulkForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            
            console.log('=== RESPUESTA DEL SERVIDOR ===');
            console.log('Respuesta completa:', data);
            console.log('Status HTTP:', response.status);
            console.log('Success:', data.success);
            console.log('Sent count:', data.sent_count);
            console.log('Total count:', data.total_count);
            console.log('Skipped count:', data.skipped_count);
            console.log('Skipped emails (array):', data.skipped_emails);
            console.log('Errors (array):', data.errors);
            console.log('Tipo de skipped_emails:', typeof data.skipped_emails, Array.isArray(data.skipped_emails));
            console.log('Tipo de errors:', typeof data.errors, Array.isArray(data.errors));
            
            // Ocultar loading overlay
            if (loadingOverlay) loadingOverlay.style.display = 'none';

            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            if (response.ok && data.success) {
                // Verificar si hay correos que no se pudieron procesar
                // Asegurarse de que siempre sean arrays
                const skippedEmails = Array.isArray(data.skipped_emails) ? data.skipped_emails : (data.skipped_emails ? [data.skipped_emails] : []);
                const errors = Array.isArray(data.errors) ? data.errors : (data.errors ? [data.errors] : []);
                const sentCount = parseInt(data.sent_count) || 0;
                const totalCount = parseInt(data.total_count) || 0;
                const skippedCount = parseInt(data.skipped_count) || 0;
                
                console.log('Datos procesados:', {
                    sentCount,
                    totalCount,
                    skippedCount,
                    errorsCount: errors.length,
                    skippedEmails: skippedEmails,
                    errors: errors,
                    dataMessage: data.message
                });
                
                // SIEMPRE mostrar alerta de error si NO se envió ningún correo
                // o si hay errores o correos omitidos
                if (sentCount === 0 || errors.length > 0 || skippedEmails.length > 0 || skippedCount > 0) {
                    console.log('=== MOSTRANDO ALERTA CON ERRORES ===');
                    console.log('Errores encontrados:', errors);
                    console.log('Correos omitidos:', skippedEmails);
                    console.log('Enviados exitosamente:', sentCount);
                    console.log('Total procesados:', totalCount);
                    
                    // Si no hay errores en el array pero hay correos omitidos, construir mensajes de error
                    let errorMessages = [...errors];
                    if (errorMessages.length === 0 && skippedEmails.length > 0) {
                        // Si no hay mensajes de error pero hay correos omitidos, crear mensajes genéricos
                        skippedEmails.forEach(email => {
                            errorMessages.push(`No se pudo procesar el correo: ${email}`);
                        });
                    }
                    
                    // Crear sección de errores colapsable y con scroll
                    let errorDetails = '';
                    
                    if (errorMessages.length > 0) {
                        // Botón para expandir/colapsar detalles
                        errorDetails = `
                            <div style="margin-top: 20px;">
                                <button 
                                    onclick="this.classList.toggle('active'); 
                                             const details = this.nextElementSibling; 
                                             const arrow = this.querySelector('.toggle-arrow');
                                             if(details.style.display === 'none') { 
                                                 details.style.display = 'block'; 
                                                 arrow.style.transform = 'rotate(90deg)';
                                                 this.style.background = '#fee2e2';
                                                 this.style.borderColor = '#fca5a5';
                                             } else { 
                                                 details.style.display = 'none'; 
                                                 arrow.style.transform = 'rotate(0deg)';
                                                 this.style.background = '#f3f4f6';
                                                 this.style.borderColor = '#e5e7eb';
                                             }" 
                                    style="width: 100%; padding: 14px 16px; background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; color: #374151; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; margin-bottom: 10px;"
                                    onmouseover="this.style.background='#e5e7eb'; this.style.borderColor='#d1d5db';"
                                    onmouseout="if(!this.classList.contains('active')) { this.style.background='#f3f4f6'; this.style.borderColor='#e5e7eb'; } else { this.style.background='#fee2e2'; this.style.borderColor='#fca5a5'; }">
                                    <span style="display: flex; align-items: center; gap: 10px;">
                                        <svg style="width: 20px; height: 20px; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span>Ver detalles de los errores (${errorMessages.length})</span>
                                    </span>
                                    <svg class="toggle-arrow" style="width: 16px; height: 16px; color: #6b7280; transition: transform 0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <div style="display: none; text-align: left; max-height: 350px; overflow-y: auto; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; scrollbar-width: thin;">
                                    <style>
                                        .error-scroll::-webkit-scrollbar { width: 8px; }
                                        .error-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
                                        .error-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
                                        .error-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                                    </style>
                        `;
                        
                        // Agregar mensajes de error
                        errorMessages.forEach((error, index) => {
                            // Determinar el tipo de error basado en el contenido del mensaje
                            let errorIcon = '';
                            let errorStyle = '';
                            
                            if (error.includes('ya está registrado en la base de datos')) {
                                errorIcon = '<svg style="width: 18px; height: 18px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"></path></svg>';
                                errorStyle = 'background: #fef2f2; border-left: 4px solid #dc2626;';
                            } else if (error.includes('Ya tiene un código de invitación enviado')) {
                                errorIcon = '<svg style="width: 18px; height: 18px; color: #f59e0b;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
                                errorStyle = 'background: #fffbeb; border-left: 4px solid #f59e0b;';
                            } else if (error.includes('Dominio mal escrito') || error.includes('Quisiste decir')) {
                                errorIcon = '<svg style="width: 18px; height: 18px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                                errorStyle = 'background: #fef2f2; border-left: 4px solid #dc2626;';
                            } else if (error.includes('formato del correo') || error.includes('no es válido') || 
                                       error.includes('espacios') || error.includes('puntos consecutivos') || 
                                       error.includes('símbolo') || error.includes('Punto extra') || 
                                       error.includes('Coma en lugar') || error.includes('caracteres especiales') ||
                                       error.includes('Falta la extensión')) {
                                errorIcon = '<svg style="width: 18px; height: 18px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                                errorStyle = 'background: #fef2f2; border-left: 4px solid #dc2626;';
                            } else if (error.includes('Celda vacía') || error.includes('sin contenido')) {
                                errorIcon = '<svg style="width: 18px; height: 18px; color: #9ca3af;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>';
                                errorStyle = 'background: #f9fafb; border-left: 4px solid #9ca3af;';
                            } else {
                                errorIcon = '<svg style="width: 18px; height: 18px; color: #f59e0b;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                                errorStyle = 'background: #fffbeb; border-left: 4px solid #f59e0b;';
                            }
                            
                            errorDetails += `
                                <div style="padding: 12px; margin-bottom: 10px; ${errorStyle} border-radius: 6px; font-size: 13px; line-height: 1.6; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <div style="display: flex; align-items: start; gap: 10px;">
                                        <span style="flex-shrink: 0;">${errorIcon}</span>
                                        <span style="color: #374151; word-break: break-word;">${error}</span>
                                    </div>
                                </div>
                            `;
                        });
                        
                        errorDetails += '</div></div>';
                    } else if (sentCount === 0) {
                        // Si no hay errores específicos pero no se envió nada, mostrar mensaje genérico
                        errorDetails = '<div style="text-align: left; margin-top: 15px; padding: 15px; background: #fef2f2; border-radius: 8px; border-left: 4px solid #dc2626;">';
                        errorDetails += '<p style="color: #dc2626; margin: 0; display: flex; align-items: center; gap: 10px;">';
                        errorDetails += '<svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                        errorDetails += '<span>No se pudo procesar ningún correo. Por favor, verifica que los correos sean válidos y no estén ya registrados en el sistema.</span></p>';
                        errorDetails += '</div>';
                    }
                    
                    // Determinar el tipo de alerta
                    const alertIcon = sentCount === 0 ? 'error' : 'warning';
                    const alertTitle = sentCount === 0 
                        ? 'No se pudo procesar ningún correo' 
                        : 'Procesamiento completado con advertencias';
                    
                    Swal.fire({
                        icon: alertIcon,
                        title: alertTitle,
                        html: `
                            <div style="text-align: left;">
                                <div style="background: ${sentCount > 0 ? '#f0fdf4' : '#fef2f2'}; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${sentCount > 0 ? '#2f9f37' : '#dc2626'};">
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>Total procesados:</strong> ${totalCount} correo(s)<br>
                                        <strong style="color: ${sentCount > 0 ? '#2f9f37' : '#dc2626'};">Enviados exitosamente:</strong> ${sentCount}<br>
                                        <strong style="color: #dc2626;">No procesados:</strong> ${skippedEmails.length || skippedCount}
                                    </p>
                                </div>
                                ${errorDetails}
                            </div>
                        `,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: sentCount === 0 ? '#dc2626' : '#2f9f37',
                        width: '800px',
                        customClass: {
                            popup: 'swal2-popup-custom'
                        }
                    });
                    
                    // NO recargar la página si hay errores - dejar que el usuario vea los errores
                    return;
                } else {
                    // Todo salió bien
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        html: `
                            <div style="text-align: center;">
                                <p style="margin-bottom: 10px;">
                                    Se enviaron <strong style="color: #2f9f37;">${sentCount}</strong> invitación(es) correctamente.
                                </p>
                            </div>
                        `,
                        confirmButtonText: 'Perfecto',
                        confirmButtonColor: '#2f9f37'
                    });
                }
                
                // Limpiar el formulario
                bulkForm.reset();
                const fileSelectedInfo = document.getElementById('file-selected-info');
                const fileUploadIcon = document.getElementById('file-upload-icon');
                const fileUploadText = document.getElementById('file-upload-text');
                const fileUploadHint = document.getElementById('file-upload-hint');
                const fileUploadArea = document.getElementById('file-upload-area');
                
                if (fileSelectedInfo) fileSelectedInfo.classList.add('hidden');
                if (fileUploadIcon) fileUploadIcon.style.display = 'flex';
                if (fileUploadText) fileUploadText.style.display = 'flex';
                if (fileUploadHint) fileUploadHint.style.display = 'block';
                if (fileUploadArea) {
                    fileUploadArea.classList.remove('border-green-500', 'bg-green-50');
                    fileUploadArea.classList.add('border-gray-300');
                }
                
                // Recargar la página después de 2 segundos para ver los códigos actualizados
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                
            } else {
                // Error en el procesamiento
                let errorMessage = data.message || 'Error al procesar el archivo. Por favor, intenta de nuevo.';
                
                if (data.validation_errors && Array.isArray(data.validation_errors)) {
                    errorMessage += '<div style="text-align: left; margin-top: 15px; max-height: 300px; overflow-y: auto;">';
                    errorMessage += '<strong style="color: #dc2626;">Errores encontrados:</strong><br><br>';
                    data.validation_errors.forEach((error, index) => {
                        errorMessage += `<div style="padding: 8px; margin-bottom: 8px; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px; font-size: 13px;"><strong>${error.email}:</strong> ${error.message}</div>`;
                    });
                    errorMessage += '</div>';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error al procesar',
                    html: errorMessage,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#dc2626',
                    width: '700px'
                });
            }
        })
        .catch(error => {
            // Ocultar loading overlay
            if (loadingOverlay) loadingOverlay.style.display = 'none';

            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión. Por favor, verifica tu internet e intenta de nuevo.',
                confirmButtonColor: '#dc2626'
            });
        });
        
        return false;
    });
}



