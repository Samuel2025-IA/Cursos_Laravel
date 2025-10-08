/**
 * Dashboard JavaScript - Diócesis de Apartadó
 * Funcionalidades para el dashboard y sidebar
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades del dashboard
    initializeDashboard();
    initializeSidebar();
    initializeNotifications();
    // initializeUserMenu(); // Comentado - usando Alpine.js en su lugar
    initializeAlerts();
    
    // Limpiar overlay en cambios de tamaño de pantalla
    window.addEventListener('resize', function() {
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay && window.innerWidth > 768) {
            overlay.remove();
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.remove('open');
            }
        }
    });
    
    // Limpiar overlay del sidebar cuando se abren notificaciones
    document.addEventListener('click', function(e) {
        if (e.target.closest('[x-data]') && e.target.closest('[x-data]').getAttribute('x-data').includes('open')) {
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.remove();
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    sidebar.classList.remove('open');
                }
            }
        }
    });
});

/**
 * Inicializar funcionalidades principales del dashboard
 */
function initializeDashboard() {
    console.log('Dashboard inicializado');
    
    // Añadir clase al body para identificar que estamos en el dashboard
    document.body.classList.add('dashboard-layout');
    
    // Inicializar tooltips si existen
    initializeTooltips();
    
    // Inicializar animaciones de entrada
    initializeAnimations();
}

/**
 * Inicializar funcionalidades del sidebar
 */
function initializeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('.sidebar-toggle');
    const mainContent = document.querySelector('.main-content-with-sidebar');
    
    if (!sidebar || !toggleButton) return;
    
    // Estado del sidebar (colapsado/expandido)
    let sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
    
    // Función para alternar sidebar
    function toggleSidebar() {
        sidebarCollapsed = !sidebarCollapsed;
        sidebar.classList.toggle('collapsed', sidebarCollapsed);
        
        // Actualizar ancho del sidebar
        if (sidebarCollapsed) {
            sidebar.style.width = '4rem';
            if (mainContent) {
                mainContent.style.marginLeft = '4rem';
            }
            
            // Ocultar elementos del contenido
            const sidebarContent = sidebar.querySelector('.sidebar-content');
            const userInfo = sidebar.querySelector('.user-info');
            const userInfoCollapsed = sidebar.querySelector('.user-info-collapsed');
            const userContainer = sidebar.querySelector('.sidebar-user-container');
            const navTexts = sidebar.querySelectorAll('.nav-text');
            const expandIcon = sidebar.querySelector('.toggle-icon-expand');
            const collapseIcon = sidebar.querySelector('.toggle-icon-collapse');
            
            if (sidebarContent) sidebarContent.style.display = 'none';
            if (userInfo) userInfo.style.display = 'none';
            if (userInfoCollapsed) userInfoCollapsed.style.display = 'flex';
            if (userContainer) userContainer.style.padding = '1rem 0.5rem';
            navTexts.forEach(text => text.style.display = 'none');
            if (expandIcon) expandIcon.style.display = 'none';
            if (collapseIcon) collapseIcon.style.display = 'block';
        } else {
            sidebar.style.width = '16rem';
            if (mainContent) {
                mainContent.style.marginLeft = '16rem';
            }
            
            // Mostrar elementos del contenido
            const sidebarContent = sidebar.querySelector('.sidebar-content');
            const userInfo = sidebar.querySelector('.user-info');
            const userInfoCollapsed = sidebar.querySelector('.user-info-collapsed');
            const userContainer = sidebar.querySelector('.sidebar-user-container');
            const navTexts = sidebar.querySelectorAll('.nav-text');
            const expandIcon = sidebar.querySelector('.toggle-icon-expand');
            const collapseIcon = sidebar.querySelector('.toggle-icon-collapse');
            
            if (sidebarContent) sidebarContent.style.display = 'flex';
            if (userInfo) userInfo.style.display = 'flex';
            if (userInfoCollapsed) userInfoCollapsed.style.display = 'none';
            if (userContainer) userContainer.style.padding = '1rem';
            navTexts.forEach(text => text.style.display = 'block');
            if (expandIcon) expandIcon.style.display = 'block';
            if (collapseIcon) collapseIcon.style.display = 'none';
        }
        
        localStorage.setItem('sidebar-collapsed', sidebarCollapsed);
        
        // Disparar evento personalizado
        window.dispatchEvent(new CustomEvent('sidebar-toggled', {
            detail: { collapsed: sidebarCollapsed }
        }));
    }
    
    // Aplicar estado inicial
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        sidebar.style.width = '4rem';
        if (mainContent) {
            mainContent.style.marginLeft = '4rem';
        }
        
        // Ocultar elementos del contenido
        const sidebarContent = sidebar.querySelector('.sidebar-content');
        const userInfo = sidebar.querySelector('.user-info');
        const userInfoCollapsed = sidebar.querySelector('.user-info-collapsed');
        const userContainer = sidebar.querySelector('.sidebar-user-container');
        const navTexts = sidebar.querySelectorAll('.nav-text');
        const expandIcon = sidebar.querySelector('.toggle-icon-expand');
        const collapseIcon = sidebar.querySelector('.toggle-icon-collapse');
        
        if (sidebarContent) sidebarContent.style.display = 'none';
        if (userInfo) userInfo.style.display = 'none';
        if (userInfoCollapsed) userInfoCollapsed.style.display = 'flex';
        if (userContainer) userContainer.style.padding = '1rem 0.5rem';
        navTexts.forEach(text => text.style.display = 'none');
        if (expandIcon) expandIcon.style.display = 'none';
        if (collapseIcon) collapseIcon.style.display = 'block';
    }
    
    // Event listener para el botón de toggle
    toggleButton.addEventListener('click', toggleSidebar);
    
    // Cerrar sidebar en móvil al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.sidebar-toggle');
            
            if (sidebar && !sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
    
    // Responsive sidebar
    function handleResize() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('mobile');
        } else {
            sidebar.classList.remove('mobile');
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize(); // Ejecutar al cargar
}

/**
 * Inicializar sistema de notificaciones
 */
function initializeNotifications() {
    const notificationButton = document.querySelector('.notifications-button');
    const notificationDropdown = document.querySelector('.notifications-dropdown');
    
    if (!notificationButton || !notificationDropdown) return;
    
    let isOpen = false;
    
    function toggleNotifications() {
        isOpen = !isOpen;
        notificationDropdown.style.display = isOpen ? 'block' : 'none';
        
        if (isOpen) {
            // Cerrar otros dropdowns
            closeUserMenu();
        }
    }
    
    function closeNotifications() {
        isOpen = false;
        notificationDropdown.style.display = 'none';
    }
    
    // Event listeners
    notificationButton.addEventListener('click', toggleNotifications);
    
    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
            closeNotifications();
        }
    });
    
    // Simular actualizaciones de notificaciones (en un proyecto real vendría del servidor)
    setInterval(function() {
        updateNotificationBadge();
    }, 30000); // Cada 30 segundos
}

/**
 * Inicializar menú de usuario
 * COMENTADO - Ahora usando Alpine.js en el header
 */
/*
function initializeUserMenu() {
    const userMenuButton = document.querySelector('.user-menu-button');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (!userMenuButton || !userDropdown) return;
    
    let isOpen = false;
    
    function toggleUserMenu() {
        isOpen = !isOpen;
        userDropdown.style.display = isOpen ? 'block' : 'none';
        
        if (isOpen) {
            // Cerrar otros dropdowns
            closeNotifications();
        }
    }
    
    function closeUserMenu() {
        isOpen = false;
        userDropdown.style.display = 'none';
    }
    
    // Event listeners
    userMenuButton.addEventListener('click', toggleUserMenu);
    
    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
            closeUserMenu();
        }
    });
}
*/

/**
 * Inicializar alertas del sistema
 */
function initializeAlerts() {
    // Mostrar alertas de sesión si existen
    if (window.sessionSuccess) {
        showAlert(window.sessionSuccess, 'success');
    }
    
    if (window.sessionError) {
        showAlert(window.sessionError, 'error');
    }
    
    if (window.sessionWarning) {
        showAlert(window.sessionWarning, 'warning');
    }
    
    if (window.sessionInfo) {
        showAlert(window.sessionInfo, 'info');
    }
    
    // Auto-ocultar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

/**
 * Mostrar alerta personalizada
 */
function showAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type}`;
    alertContainer.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button class="ml-4 text-lg font-semibold" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    
    // Insertar al inicio del contenido principal
    const mainContent = document.querySelector('.dashboard-content');
    if (mainContent) {
        mainContent.insertBefore(alertContainer, mainContent.firstChild);
    }
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertContainer.parentElement) {
            alertContainer.style.opacity = '0';
            setTimeout(() => {
                alertContainer.remove();
            }, 300);
        }
    }, 5000);
}

/**
 * Actualizar badge de notificaciones
 */
function updateNotificationBadge() {
    const badge = document.querySelector('.notifications-badge');
    if (!badge) return;
    
    // Simular nueva notificación (en un proyecto real harías una petición AJAX)
    const currentCount = parseInt(badge.textContent) || 0;
    if (Math.random() > 0.7) { // 30% de probabilidad
        badge.textContent = currentCount + 1;
        badge.style.animation = 'pulse 0.5s ease-in-out';
        setTimeout(() => {
            badge.style.animation = '';
        }, 500);
    }
}

/**
 * Inicializar tooltips
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            // Implementar tooltip personalizado si es necesario
        });
    });
}

/**
 * Inicializar animaciones
 */
function initializeAnimations() {
    // Animación de entrada para cards
    const cards = document.querySelectorAll('.dashboard-card, .stat-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(card => {
        observer.observe(card);
    });
}

/**
 * Función para alternar sidebar desde otros scripts
 */
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('.sidebar-toggle');
    
    if (sidebar && toggleButton) {
        toggleButton.click();
    }
}

/**
 * Función para alternar sidebar en móvil
 */
function toggleMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    
    // Solo funcionar en móvil (ancho menor a 768px)
    if (window.innerWidth > 768) {
        return;
    }
    
    if (sidebar) {
        sidebar.classList.toggle('open');
        
        // Añadir overlay solo para móvil
        if (sidebar.classList.contains('open')) {
            // Limpiar overlays existentes primero
            const existingOverlays = document.querySelectorAll('.sidebar-overlay');
            existingOverlays.forEach(overlay => overlay.remove());
            
            // Crear nuevo overlay
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 3.5rem;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 35;
                transition: opacity 0.3s ease;
            `;
            overlay.onclick = () => {
                sidebar.classList.remove('open');
                overlay.remove();
            };
            document.body.appendChild(overlay);
        } else {
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    }
}

/**
 * Función para mostrar loading en botones
 */
function setButtonLoading(button, loading = true) {
    if (!button) return;
    
    if (loading) {
        button.disabled = true;
        button.dataset.originalText = button.textContent;
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Cargando...
        `;
    } else {
        button.disabled = false;
        button.textContent = button.dataset.originalText || 'Enviar';
    }
}

/**
 * Función para hacer peticiones AJAX con loading
 */
function makeAjaxRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Error en petición AJAX:', error);
            showAlert('Error en la petición: ' + error.message, 'error');
            throw error;
        });
}

/**
 * Función para confirmar acciones
 */
function confirmAction(message, callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2f9f37',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    } else {
        // Fallback sin SweetAlert
        if (confirm(message)) {
            callback();
        }
    }
}

/**
 * Función para formatear fechas
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Función para formatear números
 */
function formatNumber(number) {
    return new Intl.NumberFormat('es-CO').format(number);
}

/**
 * Función para copiar al portapapeles
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copiado al portapapeles', 'success');
    }).catch(err => {
        console.error('Error al copiar:', err);
        showAlert('Error al copiar al portapapeles', 'error');
    });
}

/**
 * Función para validar formularios
 */
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    return isValid;
}

/**
 * Función para limpiar formularios
 */
function clearForm(form) {
    form.reset();
    const errorFields = form.querySelectorAll('.error');
    errorFields.forEach(field => {
        field.classList.remove('error');
    });
}

// Exportar funciones para uso global
window.dashboardUtils = {
    showAlert,
    toggleSidebar,
    setButtonLoading,
    makeAjaxRequest,
    confirmAction,
    formatDate,
    formatNumber,
    copyToClipboard,
    validateForm,
    clearForm
};
