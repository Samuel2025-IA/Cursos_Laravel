# Solución de Problemas de Estilos - Proyecto Cursos

## Problema
Los estilos de Tailwind CSS no se están cargando correctamente en el proyecto.

## Causa del Problema
El proyecto usa **Vite** para compilar CSS y JavaScript. Para que los estilos funcionen correctamente, necesitas ejecutar **DOS servidores simultáneamente**:

1. **Servidor de Laravel** (PHP) - Maneja las rutas y vistas
2. **Servidor de Vite** (Node.js) - Compila y sirve los assets en tiempo real

## Solución Definitiva

### 🚀 **Opción 1: Script Simple (Recomendado)**
```bash
cd cursos
.\iniciar-proyecto.bat
```

### 🖥️ **Opción 2: Acceso Directo en Escritorio**
```bash
cd cursos
.\crear-acceso-directo.bat
```
Luego haz doble clic en "Cursos" en tu escritorio

### ⚡ **Opción 3: Script de PowerShell**
```bash
cd cursos
powershell -ExecutionPolicy Bypass -File "Iniciar-Cursos.ps1"
```

### 🔧 **Opción 4: Comando Directo**
```bash
cd cursos
npm run serve
```

### 📋 **Opción 5: Manual (Dos Terminales)**

#### Terminal 1 - Servidor Laravel:
```bash
cd cursos
php artisan serve
```

#### Terminal 2 - Servidor Vite:
```bash
cd cursos
npm run dev
```

### 🛠️ **Opción 6: Script de Diagnóstico Completo**
```bash
cd cursos
.\diagnostico-estilos.bat
```

## Verificación

1. **Servidor Laravel**: http://127.0.0.1:8000
2. **Servidor Vite**: http://localhost:5173
3. **Página de prueba**: http://127.0.0.1:8000/test-styles

## Archivos Importantes

- `resources/css/app.css` - Contiene las directivas de Tailwind
- `resources/views/layouts/guest.blade.php` - Layout que incluye los estilos
- `vite.config.js` - Configuración de Vite
- `tailwind.config.js` - Configuración de Tailwind

## Comandos Útiles

```bash
# Compilar assets para producción
npm run build

# Instalar dependencias
npm install

# Ejecutar servidor de desarrollo
npm run dev

# Ejecutar ambos servidores
npm run serve
```

## Troubleshooting

### Si los estilos siguen sin cargar:

1. **Verificar que ambos servidores estén corriendo**
2. **Limpiar caché del navegador** (Ctrl+F5)
3. **Verificar la consola del navegador** para errores
4. **Recompilar assets**: `npm run build`

### Errores comunes:

- **"Cannot find module"**: Ejecutar `npm install`
- **"Port already in use"**: Cambiar puerto o matar proceso
- **"Policy execution"**: Cambiar política de PowerShell

## Estructura de Archivos Compilados

```
public/build/
├── manifest.json
└── assets/
    ├── app-[hash].css
    └── app-[hash].js
```

## Notas Importantes

- **NUNCA** ejecutes solo `php artisan serve` sin el servidor de Vite
- Los estilos se compilan en tiempo real durante el desarrollo
- Para producción, usa `npm run build` para crear archivos estáticos
