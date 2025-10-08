<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Standalone - Sin Layouts</title>
    
    <!-- Tailwind CSS compilado -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-CAjc0yiz.css') }}">
    
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f8fafc;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .error {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1 class="text-3xl font-bold text-green-600 mb-6">✅ Test Standalone</h1>
        
        <div class="success">
            <h2 class="text-xl font-semibold mb-2">¡Funcionando!</h2>
            <p>Si ves esta página con estilos, entonces el problema está en los layouts o en el helper @vite().</p>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-800 mb-2">Verificación de Estilos:</h3>
            <ul class="text-blue-700 space-y-1">
                <li>✅ Página carga sin errores</li>
                <li>✅ Estilos de Tailwind aplicados (texto verde, azul, etc.)</li>
                <li>✅ CSS compilado funcionando</li>
                <li>✅ Sin dependencias de layouts</li>
            </ul>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-yellow-800 mb-2">Próximos Pasos:</h3>
            <p class="text-yellow-700">
                Si esta página funciona, el problema está en el helper @vite() o en los layouts.
                Necesitamos reemplazar todas las llamadas @vite() por asset() directamente.
            </p>
        </div>
        
        <div class="flex space-x-4">
            <a href="/" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Volver al inicio
            </a>
            <a href="/test-dashboard" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Probar Dashboard
            </a>
        </div>
    </div>
</body>
</html>






