document.addEventListener('DOMContentLoaded', function() {
    // Sidebar admin
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.getElementById('adminSidebarFixed');
    const brand = document.getElementById('adminSidebarBrand');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebar) {
        document.body.classList.add('admin-has-fixed-sidebar');
    }

    function closeMobileSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }
    }

    if (mobileToggle && sidebar && overlay) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }

    if (brand && sidebar) {
        brand.addEventListener('click', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.toggle('expanded');

                const mainContent = document.querySelector('.min-h-screen');
                const header = document.querySelector('header');
                const nav = document.querySelector('nav');
                if (mainContent) mainContent.classList.toggle('sidebar-expanded');
                if (header) header.classList.toggle('sidebar-expanded');
                if (nav) nav.classList.toggle('sidebar-expanded');
            }
        });
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileSidebar();
        }
    });

    // Bienvenida (requiere meta[name="welcome-message"]) y SweetAlert2
    const welcomeMessageMeta = document.querySelector('meta[name="welcome-message"]');
    if (welcomeMessageMeta) {
        const welcomeMessage = welcomeMessageMeta.getAttribute('content');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: welcomeMessage,
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#2f9f37',
                timer: 4000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: true
            });
        } else {
            console.error('SweetAlert2 no está disponible');
        }
    }
});
