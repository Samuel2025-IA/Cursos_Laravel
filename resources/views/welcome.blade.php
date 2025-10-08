<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="margin: 0; padding: 0;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bienvenido - Diócesis de Apartadó</title>
        
        <!-- Favicon personalizado -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Arsenal:wght@400;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts - CSS unificado -->
        <link rel="stylesheet" href="{{ asset('css/all-styles.css') }}?v={{ time() }}">
        
        <!-- CSS de respaldo -->
        <link rel="stylesheet" href="{{ asset('css/fallback.css') }}?v={{ time() }}">
        
        <!-- JavaScript de welcome -->
        <script src="{{ asset('build/assets/welcome-BjK2c7iW.js') }}"></script>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class ="welcome-body">
        <!-- Meta tag para mensaje de despedida - solo cuando es logout exitoso -->
        @if(session('logout_success') && session('goodbye'))
            <meta name="goodbye-message" content="{{ session('goodbye') }}">
            <meta name="is-logout-redirect" content="true">
            @php
                session()->forget(['goodbye', 'logout_success']);
            @endphp
        @endif
                <header class="welcome-header">
            <div class="welcome-header-container">
                <!-- Logo de la Diócesis -->
                <div class="welcome-header-logo">
                    <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" alt="Escudo Diócesis de Apartadó" class="header-logo-img">
                    <span class="header-logo-text">Diócesis de Apartadó</span>
                </div>
                
                @if (Route::has('login'))
                    <nav class="welcome-header-nav" id="headerNav">
                        <a href="{{ url('/login') }}" class="welcome-nav-link login-link" id="loginBtn" onclick="showLoading('loginBtn', 'Iniciando sesión...')">
                            <span class="btn-text">Iniciar Sesión</span>
                            <span class="btn-loading" style="display: none;">
                                <svg class="loading-spinner" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416">
                                        <animate attributeName="stroke-dasharray" dur="2s" values="0 31.416;15.708 15.708;0 31.416" repeatCount="indefinite"/>
                                        <animate attributeName="stroke-dashoffset" dur="2s" values="0;-15.708;-31.416" repeatCount="indefinite"/>
                                    </circle>
                                </svg>
                                <span class="loading-text">Iniciando sesión...</span>
                            </span>
                        </a>

                        @if (Route::has('register'))
                            @php
                                // Verificar si hay una verificación de código válida
                                $hasValidVerification = session('invitation_email') && 
                                                      session('invitation_code_id') && 
                                                      session('invitation_status') === 'verified';
                                
                                // Debug temporal - remover después
                                if (config('app.debug')) {
                                    \Log::info('Welcome page - Session check', [
                                        'invitation_email' => session('invitation_email'),
                                        'invitation_code_id' => session('invitation_code_id'),
                                        'invitation_status' => session('invitation_status'),
                                        'has_valid_verification' => $hasValidVerification,
                                        'register_url' => $hasValidVerification ? 'register' : 'verify-invitation'
                                    ]);
                                }
                                
                                // Si tiene verificación válida, ir directo a registro
                                $registerUrl = $hasValidVerification 
                                    ? route('register') 
                                    : url('/verify-invitation');
                                    
                                $loadingText = $hasValidVerification 
                                    ? 'Cargando registro...' 
                                    : 'Verificando...';
                            @endphp
                            <a href="{{ $registerUrl }}" class="welcome-nav-link register-link" id="registerBtn" onclick="event.preventDefault(); showLoading('registerBtn', '{{ $loadingText }}')" 
                               @if($hasValidVerification) title="Código ya verificado - Ir directo al registro" @endif>
                                <span class="btn-text">
                                    @if($hasValidVerification)
                                        ✓ Registrarse
                                    @else
                                        Registrarse
                                    @endif
                                </span>
                                <span class="btn-loading" style="display: none;">
                                    <svg class="loading-spinner" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416">
                                            <animate attributeName="stroke-dasharray" dur="2s" values="0 31.416;15.708 15.708;0 31.416" repeatCount="indefinite"/>
                                        </svg>
                                        <span class="loading-text">Registrando...</span>
                                    </span>
                                </span>
                            </a>
                        @endif
                    </nav>
                @endif
                
                <!-- Botón de menú hamburguesa -->
                <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú de navegación">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </header>
        
        <!-- Overlay para cerrar menú móvil -->
        <div class="nav-overlay" id="navOverlay"></div>
        
        <!-- Mensaje de cuenta eliminada -->
        @if (session('account_deleted'))
            <div class="account-deleted-message">
                <div class="account-deleted-content">
                    <div class="account-deleted-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="account-deleted-text">{{ session('account_deleted') }}</p>
                    <button onclick="this.parentElement.parentElement.style.display='none'" class="account-deleted-close">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
        
        <div class="welcome-main-container">
            <main class="welcome-main">
                <div class="welcome-text-container">
                    <h1 class="welcome-title">Bienvenido a la Plataforma de Cursos Gratuitos</h1> 
                    <br>
                    <p class="welcome-description">Aprende, crece y comparte conocimiento.<br>Aquí encontrarás cursos en línea completamente gratuitos, diseñados para fortalecer tus habilidades y abrirte nuevas oportunidades.</p>
                    
                    <ul class="welcome-features-list">
                        <li>Explora nuestro catálogo de cursos.</li>
                        <li>Inscríbete y comienza hoy mismo.</li>
                    </ul>
                    
                    <ul class="welcome-cta-container">
                        <li>
                            <a href="{{ url('/verify-invitation') }}" class="welcome-cta-button">
                                Comenzar ahora
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="catedral-image-container">
                    {{-- Imagen de la Catedral --}}
                    <img src="{{ asset('img/CATEDRAL.png') }}" 
                         alt="Catedral - Plataforma de Cursos" 
                         class="catedral-image" />
                </div>
            </main>
        </div>

        <!-- Footer de la Diócesis de Apartadó -->
        <footer class="diocesis-footer">
            <div class="diocesis-footer-container">
                <!-- Logo y redes sociales -->
                <div class="diocesis-logo-section">
                    <div class="diocesis-logo">
                        <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" alt="Escudo Diócesis de Apartadó">
                    </div>
                    <div class="diocesis-name">Diócesis de Apartadó</div>
                    <div class="diocesis-social">
                        <a href="https://www.facebook.com/diocesisapartado/" target="_blank" title="Facebook" class="social-facebook">
                            <span class="social-icon-text">f</span>
                        </a>
                        <a href="https://www.instagram.com/diocesisdeapartado/" target="_blank" title="Instagram" class="social-instagram">
                            <div class="instagram-icon">
                                <div class="instagram-camera">
                                    <div class="instagram-lens"></div>
                                    <div class="instagram-dot"></div>
                                </div>
                            </div>
                        </a>
                        <a href="https://x.com/dioapartado" target="_blank" title="Twitter/X" class="social-twitter">
                            <span class="social-icon-text">𝕏</span>
                        </a>
                        <a href="https://www.youtube.com/@diocesisdeapartado3092" target="_blank" title="YouTube" class="social-youtube">
                            <span class="social-icon-text">▶</span>
                        </a>
                        <a href="https://www.tiktok.com/@diocesisdeapartado?lang=es" target="_blank" title="TikTok" class="social-tiktok">
                            <div class="tiktok-logo">
                                <div class="tiktok-shape tiktok-cyan"></div>
                                <div class="tiktok-shape tiktok-red"></div>
                                <div class="tiktok-shape tiktok-white"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="diocesis-contact">
                    <h3 class="contact-section-title" style="margin-bottom: 1.5rem;">Información de Contacto</h3>
                    
                    <!-- Dirección -->
                    <div class="contact-item" style="margin-bottom: 1.5rem;">
                        <div class="contact-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <h4>Dirección</h4>
                            <p>Calle 100 N° 94A - 109. Apartadó, Antioquia</p>
                        </div>
                    </div>

                    <!-- Correos electrónicos -->
                    <div class="contact-item" style="margin-bottom: 1.5rem;">
                        <div class="contact-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <h4>Correos electrónicos</h4>
                            <p><a href="mailto:dioaartadocuria@gmail.com">dioaartadocuria@gmail.com</a></p>
                            <p><a href="mailto:curia@diocesisdeapartado.org">curia@diocesisdeapartado.org</a></p>
                        </div>
                    </div>

                    <!-- Teléfonos -->
                    <div class="contact-item" style="margin-bottom: 1.5rem;">
                        <div class="contact-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <h4>Teléfonos</h4>
                            <p>Tel. 320 6829530</p>
                        </div>
                    </div>
                    
                    <!-- Extensiones -->
                    <div class="contact-item" style="margin-top: 1rem; margin-bottom: 1.5rem;">
                        <div class="contact-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <h4>Extensiones</h4>
                            <p>Recepción: 320 6829530</p>
                            <p>Contabilidad: 310 3676593</p>
                            <p>Talento Humano: 301 5161847</p>
                            <p>Tesorería: 318 8694729</p>
                            <p>Tribunal Eclesiástico: 317 6590491</p>
                            <p>Funerales: 321 8346471</p>
                            <p>Administración Cementerios: 313 2906270</p>
                        </div>
                    </div>
                </div>

                <!-- Horarios y políticas -->
                <div class="diocesis-info">
                    <h3 class="info-section-title" style="margin-bottom: 1.5rem;">Información Adicional</h3>
                    
                    <!-- Horarios de atención -->
                    <div class="info-item" style="margin-bottom: 1.5rem;">
                        <div class="info-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="info-text">
                            <h4>Horarios de atención</h4>
                            <p>Lunes a Viernes<br>8:00 a.m. a 12:00 p.m. - 1:00 p.m. a 4:40 p.m.</p>
                        </div>
                    </div>
                    
                    <!-- Política de Protección de datos -->
                    <div class="info-item" style="margin-bottom: 1.5rem;">
                        <div class="info-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="info-text">
                            <h4>Política de Protección de datos</h4>
                        </div>
                    </div>
                    
                    <!-- Notificación de Procesos Judiciales -->
                    <div class="info-item" style="margin-bottom: 1.5rem;">
                        <div class="info-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div class="info-text">
                            <h4>Notificación de Procesos Judiciales</h4>
                            <p>De la Diócesis de Apartadó notificar al correo electrónico <a href="mailto:juridica@reidc.co">juridica@reidc.co</a> <a href="mailto:juridica@diocesisdeapartado.org">juridica@diocesisdeapartado.org</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="diocesis-copyright text-center">
                <p>Diseñado por <a href="#">Diócesis de Apartadó</a> | 2025 | © Todos los derechos reservados</p>
            </div>
        </footer>

        <!-- WhatsApp flotante -->
        <div class="whatsapp-float">
            <a href="https://wa.me/3206829530" target="_blank" title="Contactar por WhatsApp">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
            </a>
        </div>

        @if (Route::has('login'))
            <div class="welcome-footer-spacer"></div>
        @endif


        <!-- Script para alerta de despedida -->
        <script src="{{ asset('js/views/welcome/goodbye-alert.js') }}"></script>
        
        <!-- Script para el menú hamburguesa -->
        <script src="{{ asset('js/views/welcome/welcome-hamburger-menu.js') }}"></script>

    </body>
</html>

