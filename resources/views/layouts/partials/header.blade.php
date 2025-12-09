@php
    $currentUser = auth()->user();
    $notificationsCount = $notificationsCount ?? (isset($dashboardNotifications)
        ? (is_countable($dashboardNotifications) ? count($dashboardNotifications) : $dashboardNotifications->count())
        : 0);
@endphp

<!-- Header -->
<header class="dashboard-header" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; z-index: 50 !important; background: white; border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; height: 4rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); overflow: visible;">
    
    <!-- Lado izquierdo: Menú hamburguesa, Logo y Título -->
    <div class="flex items-center space-x-2 flex-1 min-w-0">
        <!-- Botón hamburguesa para móvil -->
        <button id="mobileMenuToggle" class="menu-toggle md:hidden" onclick="toggleMobileSidebar();" 
                aria-label="Abrir menú de navegación" 
                title="Abrir menú">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Logo -->
        <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center flex-shrink-0">
            <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" 
                 alt="Escudo de la Diócesis de Apartadó" 
                 class="w-full h-full object-contain">
        </div>
        
        <!-- Título -->
        <div class="ml-2 flex-1">
            <h1 class="text-sm sm:text-lg md:text-xl font-bold text-gray-900" style="font-family: 'Arsenal', sans-serif;">
                Diócesis de Apartadó
            </h1>
        </div>
    </div>

    <!-- Lado derecho: Menú de usuario -->
    <div class="flex items-center space-x-2 flex-shrink-0">
        @if(isset($dashboardNotifications))
            <div x-data="{ open: false }" 
                 x-init="
                    // Inicializar notificaciones cuando Alpine.js esté listo
                    setTimeout(() => {
                        if (typeof window.updateNotificationBadge === 'function') {
                            window.updateNotificationBadge();
                        }
                    }, 200);
                    
                    // Actualizar badge cuando se abre el dropdown
                    $watch('open', value => {
                        if (value && typeof window.updateNotificationBadge === 'function') {
                            setTimeout(() => window.updateNotificationBadge(), 50);
                        }
                    });
                 "
                 class="relative" style="position: relative !important;">
                <button @click="open = !open" @keydown.escape.window="open = false"
                        class="relative p-2 rounded-full text-gray-600 hover:bg-gray-100 transition-colors"
                        aria-label="Ver notificaciones" title="Notificaciones">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="notification-badge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white bg-red-500 rounded-full" style="display: {{ $notificationsCount > 0 ? 'inline-flex' : 'none' }};">
                        {{ $notificationsCount }}
                    </span>
                </button>

                <div x-show="open"
                     x-cloak
                     x-transition.opacity.duration.150
                     @click.outside="open = false"
                     x-effect="
                        if (open) {
                            const isMobile = window.innerWidth < 640;
                            if (isMobile) {
                                Object.assign($el.style, {
                                    position: 'fixed',
                                    left: '1rem',
                                    right: '1rem',
                                    top: '4.5rem',
                                    width: 'auto',
                                    maxWidth: 'none',
                                    marginTop: '0',
                                    transform: 'none',
                                    zIndex: '99999'
                                });
                            } else {
                                Object.assign($el.style, {
                                    position: 'absolute',
                                    left: 'auto',
                                    right: '0',
                                    top: 'calc(100% + 0.5rem)',
                                    width: '20rem',
                                    maxWidth: '20rem',
                                    marginTop: '0',
                                    transform: 'translateX(0)',
                                    zIndex: '99999'
                                });
                            }
                        } else {
                            const style = $el.style;
                            style.position = '';
                            style.left = '';
                            style.right = '';
                            style.top = '';
                            style.width = '';
                            style.maxWidth = '';
                            style.marginTop = '';
                            style.transform = '';
                            style.zIndex = '';
                        }
                     "
                     class="fixed left-4 right-4 top-16 sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-2 sm:w-80 sm:max-w-md bg-white border border-gray-200 rounded-lg shadow-lg z-[99999]">
                    <div class="max-h-64 overflow-y-auto" id="notifications-list">
                        @forelse($dashboardNotifications as $notification)
                            <div class="notification-item px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0 cursor-pointer" 
                                 data-notification-id="{{ $notification['id'] }}"
                                 ondblclick="markNotificationAsRead('{{ $notification['id'] }}', this)">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 mt-1">
                                        @if(isset($notification['tipo']) && $notification['tipo'] === 'nuevo')
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $notification['nombre'] }}</p>
                                        @if(isset($notification['tipo']) && $notification['tipo'] === 'nuevo')
                                            <p class="text-xs text-green-600 font-semibold">Nuevo Curso</p>
                                        @else
                                            <p class="text-xs text-red-600 font-semibold">{{ $notification['estado'] }}</p>
                                        @endif
                                        @if(!empty($notification['mensaje']))
                                            <p class="text-xs text-gray-500 mt-1">{{ $notification['mensaje'] }}</p>
                                        @elseif(!empty($notification['fecha']))
                                            <p class="text-xs text-gray-500 mt-1">Finalizado el {{ $notification['fecha'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-500">
                                No hay novedades por ahora.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        <!-- Menú de usuario - NUEVO Y SIMPLE -->
        <div x-data="{ isOpen: false }" 
             x-init="isOpen = false" 
             class="relative"
             style="position: relative !important;">
            <!-- Botón del usuario -->
            <button @click="isOpen = !isOpen" 
                    class="flex items-center space-x-2 p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <div class="w-8 h-8 bg-[#2f9f37] rounded-full flex items-center justify-center">
                    <span class="text-sm font-semibold text-white">
                        {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                    </span>
                </div>
                <div class="hidden xl:block text-left">
                    <p class="text-sm font-medium">{{ $currentUser->primer_nombre }} {{ $currentUser->primer_apellido }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $currentUser->rol }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="isOpen" 
                 @click.outside="isOpen = false"
                 @keydown.escape.window="isOpen = false"
                 x-effect="if (isOpen && window.innerWidth <= 768) { $el.style.position = 'fixed'; $el.style.top = '3.5rem'; $el.style.left = '1rem'; $el.style.right = '1rem'; $el.style.width = 'auto'; $el.style.maxWidth = 'none'; $el.style.zIndex = '99999'; $el.style.background = 'white'; $el.style.border = '1px solid #e5e7eb'; $el.style.borderRadius = '0.5rem'; $el.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)'; $el.style.marginTop = '0'; $el.style.padding = '0'; $el.style.overflow = 'hidden'; $el.style.display = 'block'; $el.style.visibility = 'visible'; $el.style.opacity = '1'; $el.style.transform = 'none'; } else if (isOpen && window.innerWidth > 768) { $el.style.position = 'absolute'; $el.style.top = '100%'; $el.style.left = 'auto'; $el.style.right = '0'; $el.style.width = '16rem'; $el.style.zIndex = '9999'; $el.style.background = 'white'; $el.style.border = '1px solid #e5e7eb'; $el.style.borderRadius = '0.5rem'; $el.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)'; $el.style.marginTop = '0.5rem'; $el.style.padding = '0'; $el.style.overflow = 'hidden'; $el.style.display = 'block'; $el.style.visibility = 'visible'; $el.style.opacity = '1'; $el.style.transform = 'translateX(0)'; }"
                 style="position: absolute; top: 100%; left: auto; right: 0; width: 16rem; z-index: 9999; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); margin-top: 0.5rem; padding: 0; overflow: hidden; display: block; visibility: visible; opacity: 1; transform: translateX(0);"
                 x-ref="dropdown">
                
                <!-- User Info -->
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#2f9f37] rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-white">
                                {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $currentUser->primer_nombre }} {{ $currentUser->primer_apellido }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $currentUser->email }}</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full font-medium">{{ ucfirst($currentUser->rol) }}</span>
                    </div>
                </div>
                
                <!-- Menu Items -->
                <div class="py-2">
                    <a href="{{ route('profile.edit') }}" @click="isOpen = false" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium">Mi Perfil</span>
                    </a>
                    
                    <a href="#" onclick="alert('Función próximamente disponible')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Configuración</span>
                    </a>
                    
                    <div class="border-t border-gray-100 my-2"></div>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" @submit="isOpen = false" onsubmit="localStorage.setItem('legitimate_logout', 'true')">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-medium">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
(function() {
    'use strict';
    
    // Clave para localStorage
    const STORAGE_KEY = 'readNotifications';
    
    // Funcionalidad para marcar notificaciones como leídas con doble clic
    window.markNotificationAsRead = function(notificationId, element) {
        // Guardar en localStorage que esta notificación fue vista
        // notificationId puede ser un string como 'finalized_1' o 'new_1'
        let readNotifications = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const notificationIdStr = String(notificationId);
        if (!readNotifications.includes(notificationIdStr)) {
            readNotifications.push(notificationIdStr);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(readNotifications));
        }
        
        // Ocultar la notificación inmediatamente
        element.style.display = 'none';
        element.style.visibility = 'hidden';
        
        // Actualizar el badge inmediatamente
        updateNotificationBadge();
        
        // Animación visual opcional
        element.style.opacity = '0';
        element.style.transform = 'translateX(10px)';
        element.style.transition = 'all 0.3s ease-out';
        
        // Eliminar del DOM después de la animación
        setTimeout(() => {
            if (element.parentNode) {
                element.remove();
            }
            // Actualizar nuevamente después de eliminar
            updateNotificationBadge();
            checkEmptyNotifications();
        }, 300);
    };
    
    // Actualizar el badge de notificaciones
    function updateNotificationBadge() {
        const readNotifications = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const notificationItems = document.querySelectorAll('.notification-item');
        let visibleCount = 0;
        
        // Contar solo las notificaciones visibles que no están marcadas como leídas
        notificationItems.forEach(item => {
            // Verificar si el elemento está realmente visible
            const computedStyle = window.getComputedStyle(item);
            const isVisible = computedStyle.display !== 'none' && 
                            computedStyle.visibility !== 'hidden' &&
                            item.offsetParent !== null &&
                            parseFloat(computedStyle.opacity) > 0;
            
            if (isVisible) {
                const id = item.getAttribute('data-notification-id');
                // El ID puede ser un string como 'finalized_1' o 'new_1'
                if (id && !readNotifications.includes(String(id))) {
                    visibleCount++;
                }
            }
        });
        
        const badge = document.getElementById('notification-badge');
        if (!badge) return;
        
        if (visibleCount > 0) {
            badge.textContent = visibleCount;
            badge.style.setProperty('display', 'inline-flex', 'important');
            badge.style.setProperty('visibility', 'visible', 'important');
            badge.style.setProperty('opacity', '1', 'important');
            badge.classList.remove('hidden');
            badge.removeAttribute('aria-hidden');
        } else {
            // Ocultar completamente el badge
            badge.style.setProperty('display', 'none', 'important');
            badge.style.setProperty('visibility', 'hidden', 'important');
            badge.style.setProperty('opacity', '0', 'important');
            badge.textContent = '0';
            badge.classList.add('hidden');
            badge.setAttribute('aria-hidden', 'true');
        }
    }
    
    // Verificar si no hay notificaciones y mostrar mensaje
    function checkEmptyNotifications() {
        const notificationsList = document.getElementById('notifications-list');
        if (!notificationsList) return;
        
        const notificationItems = notificationsList.querySelectorAll('.notification-item');
        let visibleCount = 0;
        
        notificationItems.forEach(item => {
            const computedStyle = window.getComputedStyle(item);
            if (computedStyle.display !== 'none' && 
                computedStyle.visibility !== 'hidden' &&
                item.offsetParent !== null) {
                visibleCount++;
            }
        });
        
        const existingMessage = notificationsList.querySelector('.no-notifications-message');
        
        if (visibleCount === 0) {
            if (!existingMessage) {
                const emptyMessage = document.createElement('div');
                emptyMessage.className = 'px-4 py-6 text-center text-sm text-gray-500 no-notifications-message';
                emptyMessage.textContent = 'No hay novedades por ahora.';
                notificationsList.appendChild(emptyMessage);
            }
        } else {
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }
    
    // Función para inicializar notificaciones
    function initializeNotifications() {
        const readNotifications = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const notificationItems = document.querySelectorAll('.notification-item');
        
        // Ocultar notificaciones ya leídas
        notificationItems.forEach(item => {
            const id = item.getAttribute('data-notification-id');
            // El ID puede ser un string como 'finalized_1' o 'new_1'
            if (id && readNotifications.includes(String(id))) {
                item.style.display = 'none';
                item.style.visibility = 'hidden';
            }
        });
        
        // Actualizar badge inmediatamente
        updateNotificationBadge();
        checkEmptyNotifications();
    }
    
    // Hacer la función disponible globalmente
    window.updateNotificationBadge = updateNotificationBadge;
    
    // Ejecutar cuando el DOM esté listo
    function init() {
        initializeNotifications();
    }
    
    // Ejecutar inmediatamente si el DOM ya está listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // También ejecutar después de un breve delay para asegurar (por si Alpine.js aún no está listo)
    setTimeout(init, 100);
    setTimeout(init, 300);
})();
</script>

