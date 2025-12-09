<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 shadow-md w-full" style="background-color: #2a3882; border-bottom: 1px solid rgba(255,255,255,0.2); margin: 0; padding: 0;">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6 lg:px-8 header-content">
        <div class="flex justify-between items-center h-16 min-h-16">
            <!-- Logo integrado en el header - Clickeable para ir al dashboard -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity duration-200 cursor-pointer" title="Ir al Dashboard">
                    <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" alt="Escudo Diócesis de Apartadó" class="w-8 h-8 sm:w-10 sm:h-10 lg:w-11 lg:h-11 object-contain flex-shrink-0">
                    <span class="font-bold text-white text-xs sm:text-sm lg:text-base" style="font-family: 'Arsenal', sans-serif; white-space: nowrap;">Diócesis de Apartadó</span>
                </a>
            </div>

            <!-- Settings Dropdown - Derecha -->
            <div class="flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-200 hover:bg-white hover:bg-opacity-10 focus:outline-none transition ease-in-out duration-150 cursor-pointer">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>

                            <div class="ms-1">
                                <svg class="fill-current h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(optional(Auth::user())->rol === 'admin')
                        <x-dropdown-link :href="route('admin.panel')">
                            {{ __('Panel Admin') }}
                        </x-dropdown-link>
                        <hr class="my-1"/>
                        @endif
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link href="#">
                            {{ __('Configuración') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <div>
                            <button type="button" 
                                    onclick="confirmLogoutDirect()"
                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-[#2f9f37] hover:bg-[#2f9f37]/5 hover:text-[#2f9f37]/80 focus:outline-none focus:bg-[#2f9f37]/10 focus:text-[#2f9f37]/80 transition duration-150 ease-in-out">
                                {{ __('Cerrar Sesión') }}
                            </button>
                        </div>
                        
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Diócesis de Apartadó') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->display_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(optional(Auth::user())->rol === 'admin')
                <x-responsive-nav-link :href="route('admin.panel')">
                    {{ __('Panel Admin') }}
                </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <div>
                    <button type="button" 
                            onclick="confirmLogoutDirect()"
                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 transition duration-150 ease-in-out">
                        {{ __('Cerrar Sesión') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Componente de Loading para Navegación -->
<x-navigation-loading />

<!-- Scripts de Navegación -->
@vite(['resources/js/views/layouts/navigation.js'])

<!-- Función de Logout Directa -->
<script>
// Función simple y directa para logout
window.confirmLogoutDirect = function() {
    console.log('🔴 confirmLogoutDirect ejecutado');
    console.log('🔍 SweetAlert2 disponible:', typeof Swal !== 'undefined');
    
    if (typeof Swal !== 'undefined') {
        console.log('✅ Usando SweetAlert2 para confirmación');
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: '¿Estás seguro de que quieres cerrar tu sesión?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Cerrar sesión',
            cancelButtonText: 'Cancelar',
            background: '#ffffff',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('✅ Usuario confirmó logout');
                localStorage.setItem('legitimate_logout', 'true');
                
                // Crear y enviar formulario de logout
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            } else {
                console.log('❌ Usuario canceló logout');
            }
        });
    } else {
        console.log('⚠️ SweetAlert2 no disponible, usando confirmación nativa');
        if (confirm('¿Estás seguro de que quieres cerrar tu sesión?')) {
            console.log('✅ Usuario confirmó logout (nativo)');
            localStorage.setItem('legitimate_logout', 'true');
            
            // Crear y enviar formulario de logout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        } else {
            console.log('❌ Usuario canceló logout (nativo)');
        }
    }
};

console.log('✅ Función confirmLogoutDirect cargada');
</script>
