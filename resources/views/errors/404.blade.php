<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página no encontrada - Diócesis de Apartadó</title>
    
    <!-- Favicon personalizado -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/views/errors/404.css'])
</head>
<body class="error-404-body">
    <!-- Logo de la Diócesis -->
    <img src="{{ asset('img/ESCUDO_DIOCESIS.png') }}" 
         alt="Escudo Diócesis de Apartadó" 
         class="error-404-logo">
    
    <!-- Título principal -->
    <h1 class="error-404-title">Página no encontrada</h1>
    
    <!-- Descripción -->
    <p class="error-404-description">
        No encontramos la página que buscas.
    </p>
    
    <!-- Botón de inicio -->
    <a href="{{ route('welcome') }}" class="error-404-home-button">
        Inicio
    </a>
    
    <!-- Scripts -->
    @vite(['resources/js/views/errors/404.js'])
</body>
</html>
