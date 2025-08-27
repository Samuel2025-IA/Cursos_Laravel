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

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/css/catedral-image.css', 'resources/css/welcome.css', 'resources/js/app.js'])
        <style>
            /* Asegurar que no hay márgenes o padding que interfieran */
            * { box-sizing: border-box; }
            html, body { margin: 0; padding: 0; height: 100%; }
        </style>
    </head>
    <body class="welcome-body">
                <header class="welcome-header sticky top-0 z-50">
            <div class="welcome-header-container">
                <!-- Logo de la Diócesis -->
                <div class="welcome-header-logo">
                    <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" alt="Escudo Diócesis de Apartadó" class="header-logo-img">
                    <span class="header-logo-text">Diócesis de Apartadó</span>
                </div>
                
                @if (Route::has('login'))
                    <nav class="welcome-header-nav">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="welcome-nav-link dashboard-link">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ url('/login') }}" class="welcome-nav-link login-link" onclick="console.log('Login link clicked, href: /login')">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ url('/register') }}" class="welcome-nav-link register-link" onclick="console.log('Register link clicked, href: /register')">
                                   Registrarse
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>
        
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
                        <li class="welcome-feature-item">
                            <span class="welcome-feature-icon-container">
                                <span class="welcome-feature-icon">
                                    <span class="welcome-feature-dot"></span>
                                </span>
                            </span>
                            <span>
                                Explora nuestro catálogo de cursos.
                            </span>
                        </li>
                        <li class="welcome-feature-item">
                            <span class="welcome-feature-icon-container">
                                <span class="welcome-feature-icon">
                                    <span class="welcome-feature-dot"></span>
                                </span>
                            </span>
                            <span>
                                Inscríbete y comienza hoy mismo.
                            </span>
                        </li>
                    </ul>
                    
                    <ul class="welcome-cta-container">
                        <li>
                            <a href="{{ url('/register') }}" class="welcome-cta-button">
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
                    <h3 class="contact-section-title">Información de Contacto</h3>
                    
                    <div class="contact-info-grid">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="contact-text">
                    <p>Calle 100 N° 94A - 109. Apartadó, Antioquia</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="contact-text">
                                <p><a href="mailto:dioaartadocuria@gmail.com">dioaartadocuria@gmail.com</a></p>
                                <p><a href="mailto:curia@diocesisdeapartado.org">curia@diocesisdeapartado.org</a></p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="contact-text">
                                <p>Tel. 320 6829530 - WhatsApp 320 6829530</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-extensions">
                        <h4>Extensiones:</h4>
                        <div class="extensions-container">
                            <div class="extensions-grid">
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
                </div>

                <!-- Horarios y políticas -->
                <div class="diocesis-info">
                    <h3 class="info-section-title">Información Adicional</h3>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="info-text">
                    <h4>Horarios de atención:</h4>
                    <p>Lunes a Viernes<br>8:00 a.m. a 12:00 p.m. - 1:00 p.m. a 4:40 p.m.</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="info-text">
                    <h4>Política de Protección de datos</h4>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="diocesis-copyright">
                <p>Diseñado por <a href="#">Diócesis de Apartadó</a> | 2021 | © Todos los derechos reservados</p>
            </div>
        </footer>

        @if (Route::has('login'))
            <div class="welcome-footer-spacer"></div>
        @endif


    </body>
</html>
