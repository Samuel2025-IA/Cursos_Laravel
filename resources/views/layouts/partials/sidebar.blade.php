@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser->rol === 'admin';
    
    // Definir menú de navegación basado en el rol
    $navigation = [
        'dashboard' => [
            'name' => 'Dashboard',
            'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7zM8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z',
            'route' => 'dashboard',
            'roles' => ['admin', 'user']
        ],
        'cursos' => [
            'name' => 'Cursos',
            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'route' => 'cursos.index',
            'roles' => ['admin', 'user']
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
        <div class="sidebar-content flex items-center space-x-2">
            <div class="w-8 h-8 bg-[#2f9f37] rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold">Sistema Cursos</h1>
                <p class="text-xs text-gray-400">Diócesis de Apartadó</p>
            </div>
        </div>
        
        <button class="sidebar-toggle p-2 rounded-lg hover:bg-gray-800 transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      class="toggle-icon-expand"
                      d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      class="toggle-icon-collapse"
                      style="display: none;"
                      d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Información del Usuario -->
    <div class="p-4 border-b border-gray-700 sidebar-user-container">
        <div class="user-info flex items-center space-x-3" style="align-items: center;">
            <div class="w-10 h-10 bg-[#2f9f37] rounded-full flex items-center justify-center flex-shrink-0" style="margin: 0 auto 0 0;">
                <span class="text-sm font-semibold">
                    {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                </span>
            </div>
            <div class="user-details flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ $currentUser->primer_nombre }} {{ $currentUser->primer_apellido }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ $currentUser->rol }}</p>
            </div>
        </div>
        <div class="user-info-collapsed flex justify-center items-center" style="display: none; width: 100%;">
            <div class="w-10 h-10 bg-[#2f9f37] rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-semibold">
                    {{ strtoupper(substr($currentUser->primer_nombre, 0, 1)) }}{{ strtoupper(substr($currentUser->primer_apellido ?? '', 0, 1)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navegación Principal -->
    <nav class="flex-1 p-4 space-y-2">
        @foreach($filteredNavigation as $key => $item)
            <a href="{{ route($item['route']) }}" 
               class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-800 {{ request()->routeIs($item['route']) ? 'bg-[#2f9f37] text-white' : 'text-gray-300 hover:text-white' }}"
               title="{{ $item['name'] }}"
               style="align-items: center;">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 12px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                </svg>
                <span class="nav-text text-sm font-medium">{{ $item['name'] }}</span>
            </a>
        @endforeach
    </nav>

</div>
