<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicio Temporalmente No Disponible - Diócesis de Apartadó</title>
    
    <!-- Favicon personalizado -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Animate.css para animaciones de SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/views/errors/500.css'])
</head>
<body class="error-500-body">
    <!-- Logo de la Diócesis -->
    <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" 
         alt="Escudo Diócesis de Apartadó" 
         class="error-500-logo">
    
    <!-- Título principal -->
    <h1 class="error-500-title">Servicio Temporalmente No Disponible</h1>
    
    <!-- Descripción -->
    <p class="error-500-description">
        El sistema de cursos no puede conectarse a la base de datos. 
        Esto suele ocurrir cuando los servicios de XAMPP no están activos.
    </p>
    
    <!-- Pasos para solucionarlo -->
    <div class="error-500-steps">
        <h3> Pasos para solucionarlo:</h3>
        <div class="error-500-step">
            <div class="error-500-step-number">1</div>
            <div>
                <span class="error-500-status-indicator status-offline"></span>
                <strong>Abrir XAMPP Control Panel</strong>
            </div>
        </div>
        <div class="error-500-step">
            <div class="error-500-step-number">2</div>
            <div>
                <span class="error-500-status-indicator status-offline"></span>
                <strong>Iniciar Apache</strong> (botón "Start")
            </div>
        </div>
        <div class="error-500-step">
            <div class="error-500-step-number">3</div>
            <div>
                <span class="error-500-status-indicator status-offline"></span>
                <strong>Iniciar MySQL</strong> (botón "Start")
            </div>
        </div>
        <div class="error-500-step">
            <div class="error-500-step-number">4</div>
            <div>
                <span class="error-500-status-indicator status-offline"></span>
                <strong>Iniciar el proyecto</strong> desde el escritorio
            </div>
        </div>
    </div>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/js/views/errors/500.js'])
</body>
</html>
