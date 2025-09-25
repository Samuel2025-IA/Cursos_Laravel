// JavaScript para la vista de bienvenida - Solo funciones de utilidad
// El menú móvil se maneja completamente en el script inline del HTML

// Función para mostrar indicador de carga
window.showLoading = function(buttonId, loadingText) {
    const button = document.getElementById(buttonId);
    if (!button) return;
    
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');
    const loadingTextElement = button.querySelector('.loading-text');
    
    if (btnText && btnLoading && loadingTextElement) {
        // Ocultar texto del botón y mostrar carga
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        loadingTextElement.textContent = loadingText;
        
        // Deshabilitar el botón
        button.style.pointerEvents = 'none';
        button.style.opacity = '0.7';
        
        // Redirección real después de mostrar loading
        setTimeout(() => {
            // Permitir que el enlace funcione normalmente
            window.location.href = button.href;
        }, 800);
    }
}