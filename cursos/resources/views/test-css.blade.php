<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test CSS - Diócesis de Apartadó</title>
    
    <!-- CSS unificado -->
    <link rel="stylesheet" href="{{ asset('css/all-styles.css') }}">
    
    <!-- CSS de respaldo -->
    <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
</head>
<body>
    <div style="padding: 2rem; text-align: center;">
        <h1 style="color: #2f9f37; font-size: 2rem; margin-bottom: 1rem;">
            ✅ Test de CSS - Diócesis de Apartadó
        </h1>
        
        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
            <h2 style="color: #1f2937; margin-bottom: 0.5rem;">Estado de los CSS:</h2>
            <p style="color: #6b7280;">Si ves este texto con estilos, los CSS se están cargando correctamente.</p>
        </div>
        
        <div style="background: #2f9f37; color: white; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
            <h3 style="margin: 0;">🎨 CSS Funcionando Correctamente</h3>
        </div>
        
        <div style="background: #2D3A73; color: white; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
            <h3 style="margin: 0;">🚀 Todos los Estilos Cargados</h3>
        </div>
        
        <a href="/" style="display: inline-block; background: #2f9f37; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; margin-top: 1rem;">
            ← Volver a Welcome
        </a>
    </div>
</body>
</html>
