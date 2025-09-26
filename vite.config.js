import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/dashboard.css',
                'resources/css/sidebar.css',
                'resources/css/test-view.css',
                'resources/css/views/welcome/img/catedral-image.css',
                'resources/css/views/welcome/welcome-base.css',
                'resources/css/views/welcome/welcome-main.css',
                'resources/css/views/welcome/welcome-components.css',
                'resources/css/views/welcome/welcome-header.css',
                'resources/css/views/welcome/welcome-footer.css',
                'resources/css/views/errors/404.css',
                'resources/css/views/errors/500.css',
                'resources/css/admin/panel.css',
                'resources/css/views/auth/register.css',
                'resources/css/views/auth/login.css',
                'resources/css/views/auth/verify-invitation.css',
                'resources/css/views/auth/invitation-alert.css',
                'resources/js/dashboard.js',
                'resources/js/sidebar.js',
                'resources/js/simple-flash.js',
                'resources/js/welcome.js',
                'resources/js/auth/login.js',
                'resources/js/auth/register.js',
                'resources/js/auth/verify-invitation.js',
                'resources/js/auth/forgot-password.js',
                'resources/js/auth/reset-password.js',
                'resources/js/auth/unauthorized-access.js',
                'resources/js/views/errors/404.js',
                'resources/js/views/errors/500.js',
                'resources/js/views/auth/login-alerts.js',
                'resources/js/views/layouts/guest-alerts.js',
                'resources/js/views/layouts/app-alerts.js',
                'resources/js/views/components/loading-overlay.js',
                'resources/js/views/profile/update-password.js',
                'resources/js/views/profile/delete-user.js',
                'resources/js/global/toggle-password.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '192.168.80.237',
            port: 5173,
        },
        cors: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type, Authorization',
        },
    },
});
