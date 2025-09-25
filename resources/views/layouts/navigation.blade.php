<nav x-data="{ open: false }" class="sticky top-0 z-60 shadow-md" style="background-color: #2a3882; border-bottom: 1px solid rgba(255,255,255,0.2);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 header-content">
        <div class="flex justify-between items-center h-16 min-h-16">
            <!-- Logo integrado en el header -->
            <div class="flex items-center flex-shrink-0">
                <div class="flex items-center gap-2 sm:gap-3">
                    <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" alt="Escudo Diócesis de Apartadó" class="w-8 h-8 sm:w-10 sm:h-10 lg:w-11 lg:h-11 object-contain flex-shrink-0">
                    <span class="font-bold text-white text-xs sm:text-sm lg:text-base hidden sm:block" style="font-family: 'Arsenal', sans-serif; white-space: nowrap;">Diócesis de Apartadó</span>
                </div>
            </div>

            <!-- Settings Dropdown -->
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

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf

                            <x-dropdown-link href="#"
                                    onclick="event.preventDefault(); confirmLogout();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                        
                        <script>
                        function confirmLogout() {
                            if (typeof Swal !== 'undefined') {
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
                                        // Marcar que se está haciendo logout real
                                        localStorage.setItem('legitimate_logout', 'true');
                                        
                                        // Intentar con el formulario de escritorio primero, luego el móvil
                                        const desktopForm = document.getElementById('logout-form');
                                        const mobileForm = document.getElementById('logout-form-mobile');
                                        
                                        if (desktopForm) {
                                            desktopForm.submit();
                                        } else if (mobileForm) {
                                            mobileForm.submit();
                                        }
                                    }
                                });
                            } else {
                                // Fallback: confirmación nativa del navegador
                                if (confirm('¿Estás seguro de que quieres cerrar tu sesión?')) {
                                    // Marcar que se está haciendo logout real
                                    localStorage.setItem('legitimate_logout', 'true');
                                    
                                    const desktopForm = document.getElementById('logout-form');
                                    const mobileForm = document.getElementById('logout-form-mobile');
                                    
                                    if (desktopForm) {
                                        desktopForm.submit();
                                    } else if (mobileForm) {
                                        mobileForm.submit();
                                    }
                                }
                            }
                        }
                        </script>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden ml-2">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:bg-white focus:bg-opacity-10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                    @csrf

                    <x-responsive-nav-link href="#"
                            onclick="event.preventDefault(); confirmLogout();">
                        {{ __('Cerrar Sesión') }}
                    </x-dropdown-link>
                </form>
            </div>
        </div>
    </div>
</nav>
