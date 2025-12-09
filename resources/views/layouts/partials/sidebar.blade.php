@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
    
    // Definir menú de navegación basado en el rol
    $navigation = [
        'home' => [
            'name' => 'Home',
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'route' => 'dashboard',
            'roles' => ['admin', 'estudiante', 'user']
        ],
        'cursos' => [
            'name' => 'Cursos',
            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'route' => 'cursos.index',
            'roles' => ['admin', 'estudiante', 'user']
        ],
        'crear-cursos' => [
            'name' => 'Crear Cursos',
            'icon' => 'M12 4v16m8-8H4',
            'route' => 'cursos.create',
            'roles' => ['admin']
        ],
        'recursos' => [
            'name' => 'Recursos',
            'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
            'route' => 'recursos.index',
            'roles' => ['admin', 'estudiante', 'user'],
            'submenu' => [
                'insertar' => [
                    'name' => 'Insertar',
                    'route' => 'recursos.index',
                    'roles' => ['admin', 'estudiante', 'user']
                ],
                'leer-protocolos' => [
                    'name' => 'Leer protocolos',
                    'route' => 'recursos.protocolos',
                    'roles' => ['admin', 'estudiante', 'user']
                ]
            ]
        ],
        'admin' => [
            'name' => 'Panel Admin',
            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'route' => 'admin.panel',
            'roles' => ['admin']
        ]
    ];
    
    // Filtrar menú según el rol del usuario
    $filteredNavigation = array_filter($navigation, function($item) use ($currentUser) {
        return in_array($currentUser->rol, $item['roles']);
    });
@endphp

<!-- Sidebar -->
<div class="sidebar" 
     style="background-color: #111827; color: white; width: 16rem; min-height: calc(100vh - 4rem); display: flex; flex-direction: column; transition: width 0.2s ease-in-out, left 0.3s ease-in-out; border-right: 1px solid #374151; position: fixed; top: 4rem; left: 0; z-index: 40;">
    
    <!-- Logo y Toggle -->
    <div class="flex items-center justify-between p-4 border-b border-gray-700">
        <div class="sidebar-content flex items-center">
            <div>
                <h1 class="text-lg font-bold">Sistema Cursos</h1>
            </div>
        </div>
        
        <!-- Botón de cerrar para móviles -->
        <button class="sidebar-close-mobile p-2 rounded-lg hover:bg-gray-800 transition-colors duration-200" 
                title="Cerrar menú"
                aria-label="Cerrar menú">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Botón de colapsar para desktop -->
        <button class="sidebar-toggle hidden md:block p-2 rounded-lg hover:bg-gray-800 transition-colors duration-200" 
                title="Colapsar menú"
                aria-label="Colapsar menú"
                onclick="toggleSidebar()">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Información del Usuario -->
    <div class="p-4 border-b border-gray-700 sidebar-user-container">
        <div class="user-info flex items-center" style="align-items: center;">
            <div class="w-10 h-10 bg-[#2f9f37] rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-semibold">
                    {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                </span>
            </div>
            <!-- Nombre del usuario visible cuando sidebar está expandido -->
            <div class="user-details flex-1 min-w-0 ml-2">
                <p class="text-sm font-medium truncate">{{ $currentUser->primer_nombre }} {{ $currentUser->primer_apellido }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ $currentUser->rol }}</p>
            </div>
        </div>
        <div class="user-info-collapsed flex justify-center items-center" style="display: none; width: 100%;">
            <div class="w-8 h-8 bg-[#2f9f37] rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-semibold">
                    {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navegación Principal -->
    <nav class="flex-1 p-4 space-y-2">
        @foreach($filteredNavigation as $key => $item)
            @if(isset($item['submenu']))
                <!-- Item con submenú -->
                <div class="nav-item-group">
                    <button onclick="toggleSubmenu('{{ $key }}')" 
                            class="nav-item flex items-center justify-between px-1 py-2 rounded-lg transition-all duration-200 w-full {{ request()->routeIs($item['route']) || request()->routeIs('recursos.*') ? 'bg-[#2f9f37] text-white' : 'text-gray-300 hover:bg-gray-800' }}"
                            title="{{ $item['name'] }}">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="flex items-center justify-center w-7 h-7 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                </svg>
                            </div>
                            <span class="nav-text text-sm font-medium">{{ $item['name'] }}</span>
                        </div>
                        <svg id="icon-{{ $key }}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="submenu-{{ $key }}" class="submenu hidden pl-4 mt-1 space-y-1">
                        @foreach($item['submenu'] as $subKey => $subItem)
                            @php
                                $subItemRoles = $subItem['roles'] ?? ['admin', 'estudiante', 'user'];
                                $hasAccess = in_array($currentUser->rol, $subItemRoles);
                                
                                // Cambiar el nombre "Insertar" a "Descargar" solo para estudiantes
                                $displayName = $subItem['name'];
                                if ($subKey === 'insertar' && $currentUser->rol === 'estudiante') {
                                    $displayName = 'Descargar';
                                }
                            @endphp
                            @if($hasAccess)
                                <a href="{{ route($subItem['route']) }}" 
                                   class="nav-item flex items-center justify-start px-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->routeIs($subItem['route']) ? 'bg-[#2f9f37] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}"
                                   title="{{ $displayName }}">
                                    <span class="nav-text">{{ $displayName }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Item sin submenú -->
                <a href="{{ route($item['route']) }}" 
                   class="nav-item flex items-center justify-start px-1 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs($item['route']) ? 'bg-[#2f9f37] text-white' : 'text-gray-300' }}"
                   title="{{ $item['name'] }}">
                    <div class="flex items-center space-x-3 w-full">
                        <div class="flex items-center justify-center w-7 h-7 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                        </div>
                        <span class="nav-text text-sm font-medium">{{ $item['name'] }}</span>
                    </div>
                </a>
            @endif
        @endforeach
    </nav>

</div>

<script>
function toggleSubmenu(key) {
    const submenu = document.getElementById('submenu-' + key);
    const icon = document.getElementById('icon-' + key);
    
    if (submenu && icon) {
        const isHidden = submenu.classList.contains('hidden');
        
        // Cerrar todos los demás submenús primero
        const allSubmenus = document.querySelectorAll('[id^="submenu-"]');
        const allIcons = document.querySelectorAll('[id^="icon-"]');
        
        allSubmenus.forEach(function(sub) {
            if (sub.id !== 'submenu-' + key) {
                sub.classList.add('hidden');
            }
        });
        
        allIcons.forEach(function(ic) {
            if (ic.id !== 'icon-' + key) {
                ic.style.transform = 'rotate(0deg)';
            }
        });
        
        // Ahora abrir o cerrar el submenú seleccionado
        if (isHidden) {
            submenu.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            submenu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
}

// Abrir submenú de Recursos si estamos en una ruta relacionada
document.addEventListener('DOMContentLoaded', function() {
    @if(request()->routeIs('recursos.*'))
        // Cerrar todos los demás submenús primero
        const allSubmenus = document.querySelectorAll('[id^="submenu-"]');
        const allIcons = document.querySelectorAll('[id^="icon-"]');
        
        allSubmenus.forEach(function(sub) {
            if (sub.id !== 'submenu-recursos') {
                sub.classList.add('hidden');
            }
        });
        
        allIcons.forEach(function(ic) {
            if (ic.id !== 'icon-recursos') {
                ic.style.transform = 'rotate(0deg)';
            }
        });
        
        // Abrir el submenú de Recursos
        const recursosSubmenu = document.getElementById('submenu-recursos');
        const recursosIcon = document.getElementById('icon-recursos');
        if (recursosSubmenu && recursosIcon) {
            recursosSubmenu.classList.remove('hidden');
            recursosIcon.style.transform = 'rotate(180deg)';
        }
    @endif
});
</script>
