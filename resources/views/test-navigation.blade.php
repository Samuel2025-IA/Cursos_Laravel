<!DOCTYPE html>
<html>
<head>
    <title>Test Navigation Loading</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Test Navigation Loading</h1>
        
        <div class="space-y-4">
            <a href="{{ route('admin.panel') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded text-center">
                Ir a Panel Admin
            </a>
            
            <a href="{{ route('profile.edit') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded text-center">
                Ir a Perfil
            </a>
            
            <button onclick="testLoading()" class="block w-full bg-purple-600 text-white py-2 px-4 rounded text-center">
                Test Loading Manual
            </button>
        </div>
    </div>

    <!-- Componente de Loading para Navegación -->
    <x-navigation-loading />

    <script>
        function testLoading() {
            if (window.showNavigationLoading) {
                window.showNavigationLoading('Test Manual', 'Probando el sistema de loading...');
                setTimeout(() => {
                    window.hideNavigationLoading();
                }, 3000);
            } else {
                alert('showNavigationLoading no está disponible');
            }
        }
    </script>
</body>
</html>
