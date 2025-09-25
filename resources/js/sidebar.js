document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.app-sidebar');
    const toggleButtons = document.querySelectorAll('[data-toggle-sidebar]');
    const root = document.documentElement; // usamos clases en <html>

    function setCollapsed(collapsed) {
        if (!sidebar) return;
        sidebar.classList.toggle('collapsed', collapsed);
        root.classList.toggle('sidebar-collapsed', collapsed);
        root.classList.toggle('with-sidebar', true); // Asegurar que siempre tenga la clase
        
        // Disparar evento personalizado para notificar el cambio
        const event = new CustomEvent('sidebarToggle', { 
            detail: { collapsed: collapsed } 
        });
        document.dispatchEvent(event);
    }

    // Inicializar con sidebar expandido
    setCollapsed(false);

    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('collapsed');
            setCollapsed(!isCollapsed);
        });
    });

    // Toggle en móvil (abre/cierra)
    const mobileOpenBtn = document.querySelector('[data-open-sidebar]');
    const mobileCloseArea = document.querySelector('[data-close-sidebar]');
    if (mobileOpenBtn && sidebar) {
        mobileOpenBtn.addEventListener('click', () => {
            sidebar.classList.add('open');
        });
    }
    if (mobileCloseArea && sidebar) {
        mobileCloseArea.addEventListener('click', () => {
            sidebar.classList.remove('open');
        });
    }
});
