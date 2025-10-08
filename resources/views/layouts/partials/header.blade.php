@php
    $currentUser = auth()->user();
@endphp

<!-- Header -->
<header class="dashboard-header" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; z-index: 50 !important; background: white; border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; height: 4rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); overflow: visible;">
    
    <!-- Lado izquierdo: Menú hamburguesa, Logo y Título -->
    <div class="flex items-center space-x-2 flex-1 min-w-0">
        <!-- Botón hamburguesa para móvil -->
        <button id="mobileMenuToggle" class="menu-toggle md:hidden" style="display: none;">
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
        <div class="min-w-0 hidden sm:block">
            <h1 class="text-lg md:text-xl font-bold text-black truncate" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1); font-family: 'Arsenal', sans-serif; font-weight: 700; letter-spacing: 0.5px;">
                Diócesis de Apartadó
            </h1>
        </div>
    </div>

    <!-- Lado derecho: Menú de usuario -->
    <div class="flex items-center space-x-2 flex-shrink-0">
        <!-- Menú de usuario -->
        <div x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" 
                    class="flex items-center space-x-2 p-1 md:p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200"
                    id="user-dropdown-button">
                <div class="w-8 h-8 bg-[#2f9f37] rounded-full flex items-center justify-center">
                    <span class="text-sm font-semibold text-white">
                        {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                    </span>
                </div>
                <div class="hidden xl:block text-left">
                    <p class="text-sm font-medium text-gray-900">{{ $currentUser->primer_nombre }} {{ $currentUser->primer_apellido }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $currentUser->rol }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <!-- Dropdown del usuario -->
            <div x-show="open" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                 id="user-dropdown-menu">
                <div class="p-4 border-b border-gray-200">
                    <p class="text-sm font-medium text-gray-900">{{ $currentUser->primer_nombre }} {{ $currentUser->primer_apellido }}</p>
                    <p class="text-xs text-gray-500">{{ $currentUser->email }}</p>
                </div>
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Perfil
                    </a>
                    <a href="#" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Configuración
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Función agresiva para cerrar el dropdown
function forceCloseDropdown() {
    // Cerrar via ID específico
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    if (dropdownMenu) {
        dropdownMenu.style.display = 'none';
        dropdownMenu.setAttribute('style', 'display: none !important;');
    }
    
    // Cerrar via Alpine.js
    const alpineElement = document.querySelector('[x-data*="open"]');
    if (alpineElement && alpineElement._x_dataStack && alpineElement._x_dataStack[0]) {
        const data = alpineElement._x_dataStack[0];
        if (typeof data.open !== 'undefined') {
            data.open = false;
        }
    }
    
    // Cerrar cualquier elemento con x-show="open"
    const openElements = document.querySelectorAll('[x-show="open"]');
    openElements.forEach(function(element) {
        element.style.display = 'none';
        element.setAttribute('style', 'display: none !important;');
    });
}

// Ejecutar múltiples veces para asegurar que esté cerrado
document.addEventListener('DOMContentLoaded', function() {
    forceCloseDropdown();
    setTimeout(forceCloseDropdown, 50);
    setTimeout(forceCloseDropdown, 100);
    setTimeout(forceCloseDropdown, 300);
});

// Ejecutar después de Alpine.js
document.addEventListener('alpine:init', function() {
    setTimeout(forceCloseDropdown, 50);
    setTimeout(forceCloseDropdown, 150);
});

// Ejecutar en window.load
window.addEventListener('load', function() {
    setTimeout(forceCloseDropdown, 100);
    setTimeout(forceCloseDropdown, 300);
});

// Ejecutar después de que otros scripts se carguen
setTimeout(forceCloseDropdown, 500);
setTimeout(forceCloseDropdown, 1000);
</script>