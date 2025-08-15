# Script para iniciar el proyecto Cursos
# Autor: Asistente IA
# Fecha: $(Get-Date)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "INICIANDO PROYECTO CURSOS" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar si estamos en el directorio correcto
if (-not (Test-Path "package.json")) {
    Write-Host "❌ Error: No se encontró package.json" -ForegroundColor Red
    Write-Host "Asegúrate de estar en el directorio del proyecto cursos" -ForegroundColor Yellow
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Verificar si Node.js está instalado
try {
    $nodeVersion = node --version
    Write-Host "✅ Node.js encontrado: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Error: Node.js no está instalado" -ForegroundColor Red
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Verificar si npm está disponible
try {
    $npmVersion = npm --version
    Write-Host "✅ npm encontrado: $npmVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Error: npm no está disponible" -ForegroundColor Red
    Read-Host "Presiona Enter para salir"
    exit 1
}

Write-Host ""
Write-Host "🌐 URLs del proyecto:" -ForegroundColor Yellow
Write-Host "   Servidor Laravel: http://127.0.0.1:8000" -ForegroundColor White
Write-Host "   Servidor Vite: http://localhost:5173" -ForegroundColor White
Write-Host "   Página de prueba: http://127.0.0.1:8000/test-styles" -ForegroundColor White
Write-Host ""
Write-Host "⏹️  Para detener: Presiona Ctrl+C" -ForegroundColor Yellow
Write-Host ""

# Iniciar ambos servidores
Write-Host "🚀 Iniciando servidores..." -ForegroundColor Green
npm run serve


