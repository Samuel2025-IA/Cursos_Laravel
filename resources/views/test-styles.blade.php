<x-guest-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-blue-600 mb-4">Prueba de Estilos</h1>
        
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <p>Si ves este mensaje con fondo azul y texto azul, los estilos están funcionando correctamente.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Tarjeta 1</h2>
                <p class="text-gray-600">Esta es una tarjeta de prueba con estilos de Tailwind.</p>
                <button class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Botón de Prueba
                </button>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Tarjeta 2</h2>
                <p class="text-gray-600">Otra tarjeta para verificar que los estilos funcionan.</p>
                <button class="mt-3 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Botón Verde
                </button>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 underline">
                Volver al registro
            </a>
        </div>
    </div>
</x-guest-layout>


