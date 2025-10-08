/**
 * Script para el panel de administración
 * Maneja la funcionalidad de invitaciones y gestión de códigos
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar panel administrativo
    initializePanel();
    
    // Mostrar alertas de sesión si existen
    showSessionAlerts();
    
    // Configurar ajustes del sidebar
    setupSidebarAdjustments();
});

/**
 * Inicializar el panel administrativo
 */
function initializePanel() {
    // Registrar event listeners directos
    registerEventListeners();
}

/**
 * Mostrar alertas de sesión
 */
function showSessionAlerts() {
    // Las alertas de sesión se manejan desde el HTML con Blade
    // Esta función está aquí para futuras mejoras
}

/**
 * Configurar ajustes del sidebar
 */
function setupSidebarAdjustments() {
    // Escuchar el evento personalizado del sidebar
    document.addEventListener('sidebarToggle', function(event) {
        adjustContentForSidebar(event.detail.collapsed);
    });

    // Ajustar inicialmente cuando el DOM esté listo
    adjustContentForSidebar();
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
        mostrarAlerta(`Los siguientes emails no son válidos: ${emailsInvalidos.join(', ')}`, 'error');
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
 * Función para validar formato de email
 */
function validarEmail(email) {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }
        
        // Intentar parsear como JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Si no es JSON, asumir éxito (probablemente una redirección)
            return { success: true, message: 'Invitaciones enviadas correctamente' };
        }
    })
    .then(data => {
        // Ocultar loading overlay
        ocultarLoadingOverlay();

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
        if (error.message && error.message.includes('HTTP Error')) {
            errorMessage = `Error del servidor: ${error.message}`;
        } else if (error.message) {
            errorMessage = `Error: ${error.message}`;
        }
        
        mostrarAlerta(errorMessage, 'error');
    });
}

/**
 * Registrar event listeners
 */
function registerEventListeners() {
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
 * Ajustar contenido cuando el sidebar se colapsa/expande
 */
function adjustContentForSidebar(collapsed = null) {
    const sidebar = document.querySelector('.app-sidebar');
    const content = document.querySelector('.dashboard-content');
    const headerContent = document.querySelector('.header-content');
    
    if (sidebar && content) {
        const isCollapsed = collapsed !== null ? collapsed : sidebar.classList.contains('collapsed');
        
        if (isCollapsed) {
            content.style.marginLeft = '84px';
            if (headerContent) {
                headerContent.style.marginLeft = '84px';
            }
        } else {
            content.style.marginLeft = '280px';
            if (headerContent) {
                headerContent.style.marginLeft = '280px';
            }
        }
    }
}
