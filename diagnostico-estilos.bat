@echo off
echo ========================================
echo DIAGNOSTICO DE ESTILOS - PROYECTO CURSOS
echo ========================================
echo.

echo 1. Verificando si Node.js esta instalado...
node --version
if %errorlevel% neq 0 (
    echo ERROR: Node.js no esta instalado o no esta en el PATH
    pause
    exit /b 1
)

echo.
echo 2. Verificando si npm esta disponible...
npm --version
if %errorlevel% neq 0 (
    echo ERROR: npm no esta disponible
    pause
    exit /b 1
)

echo.
echo 3. Instalando dependencias...
npm install

echo.
echo 4. Compilando assets para produccion...
npm run build

echo.
echo 5. Verificando archivos compilados...
if exist "public\build\manifest.json" (
    echo ✓ Manifest.json encontrado
) else (
    echo ✗ Manifest.json NO encontrado
)

if exist "public\build\assets\*.css" (
    echo ✓ Archivos CSS encontrados
) else (
    echo ✗ Archivos CSS NO encontrados
)

if exist "public\build\assets\*.js" (
    echo ✓ Archivos JS encontrados
) else (
    echo ✗ Archivos JS NO encontrados
)

echo.
echo 6. Iniciando servidores...
echo Presiona Ctrl+C para detener los servidores
echo.
echo Servidor Laravel: http://127.0.0.1:8000
echo Servidor Vite: http://localhost:5173
echo.
echo Pagina de prueba: http://127.0.0.1:8000/test-styles
echo.

concurrently "php artisan serve" "npm run dev"


