<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Plataforma de Cursos Gratuitos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/css/catedral-image.css', 'resources/css/welcome.css', 'resources/js/app.js'])
    </head>
    <body class="welcome-body">
        <header class="welcome-header">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="welcome-nav-link bordered">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="welcome-nav-link">
                            Iniciar Sesión
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="welcome-nav-link bordered">
                               Registrarse
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        
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
                            <a href="{{ route('register') }}" class="welcome-cta-button">
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

        @if (Route::has('login'))
            <div class="welcome-footer-spacer"></div>
        @endif
    </body>
</html>
