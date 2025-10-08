# Frontend Dashboard - Estructura y Documentación

## Descripción General

Se ha implementado una nueva estructura de frontend para el dashboard del sistema de cursos de la Diócesis de Apartadó. Esta implementación incluye un layout principal reutilizable, un sidebar dinámico y estilos centralizados.

## Estructura de Archivos

### Layouts
```
resources/views/layouts/
├── dashboard.blade.php          # Layout principal del dashboard
└── partials/
    ├── sidebar.blade.php        # Sidebar reutilizable
    └── header.blade.php         # Header con navegación y menús
```

### Vistas
```
resources/views/
├── dashboard-new.blade.php      # Dashboard principal (usuarios y admin)
└── admin/
    └── panel-new.blade.php      # Panel de administración
```

### Estilos y Scripts
```
public/
├── css/
│   └── dashboard.css           # Estilos centralizados del dashboard
└── js/
    └── dashboard.js            # Funcionalidades JavaScript del dashboard
```

## Características Implementadas

### 1. Layout Principal (`dashboard.blade.php`)

- **Responsive Design**: Adaptable a dispositivos móviles y desktop
- **Estructura Flexible**: Sidebar colapsible + contenido principal
- **Gestión de Assets**: CSS y JS centralizados
- **Mensajes Flash**: Sistema de alertas integrado
- **Meta Tags**: Configuración SEO y CSRF

### 2. Sidebar Reutilizable (`partials/sidebar.blade.php`)

- **Navegación Dinámica**: Menú basado en roles de usuario
- **Estado Colapsible**: Sidebar que se puede contraer/expandir
- **Información de Usuario**: Avatar y datos del usuario logueado
- **Iconografía Consistente**: SVG icons para cada sección
- **Persistencia**: Estado del sidebar guardado en localStorage

### 3. Header (`partials/header.blade.php`)

- **Breadcrumbs Dinámicos**: Navegación contextual
- **Sistema de Notificaciones**: Dropdown con notificaciones
- **Menú de Usuario**: Dropdown con opciones de perfil y logout
- **Título Dinámico**: Cambia según la página actual

### 4. Estilos Centralizados (`dashboard.css`)

- **Variables CSS**: Colores y medidas consistentes
- **Componentes Reutilizables**: Cards, botones, tablas, formularios
- **Responsive Design**: Media queries para móviles
- **Animaciones**: Transiciones suaves y efectos visuales
- **Estados**: Hover, focus, active, disabled
- **Utilidades**: Clases helper para espaciado, colores, etc.

### 5. JavaScript (`dashboard.js`)

- **Inicialización Automática**: Setup al cargar la página
- **Gestión del Sidebar**: Toggle, persistencia, responsive
- **Sistema de Notificaciones**: Dropdown y badge dinámico
- **Menú de Usuario**: Dropdown interactivo
- **Alertas Flash**: Auto-ocultación y personalización
- **Utilidades**: Funciones helper para AJAX, validación, etc.

## Navegación por Roles

### Usuario Regular
- Dashboard
- Cursos
- Perfil
- Cerrar Sesión

### Administrador
- Dashboard
- Cursos
- Panel Admin
- Perfil
- Cerrar Sesión

## Componentes Reutilizables

### Cards
```html
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h3 class="dashboard-card-title">Título</h3>
    </div>
    <div class="dashboard-card-content">
        <!-- Contenido -->
    </div>
</div>
```

### Estadísticas
```html
<div class="stat-card">
    <div class="stat-icon primary">
        <!-- Icono -->
    </div>
    <h3 class="stat-value">123</h3>
    <p class="stat-label">Etiqueta</p>
</div>
```

### Botones
```html
<button class="btn btn-primary">Botón Primario</button>
<button class="btn btn-secondary">Botón Secundario</button>
<button class="btn btn-success">Botón Éxito</button>
```

### Tablas
```html
<table class="dashboard-table">
    <thead>
        <tr>
            <th>Columna</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Dato</td>
        </tr>
    </tbody>
</table>
```

## Funcionalidades JavaScript

### Utilidades Disponibles
```javascript
// Mostrar alerta
dashboardUtils.showAlert('Mensaje', 'success');

// Alternar sidebar
dashboardUtils.toggleSidebar();

// Loading en botón
dashboardUtils.setButtonLoading(button, true);

// Petición AJAX
dashboardUtils.makeAjaxRequest(url, options);

// Confirmar acción
dashboardUtils.confirmAction('¿Estás seguro?', callback);

// Copiar al portapapeles
dashboardUtils.copyToClipboard(text);
```

## Responsive Design

### Breakpoints
- **Mobile**: < 768px - Sidebar oculto, menú hamburguesa
- **Tablet**: 768px - 1024px - Sidebar colapsible
- **Desktop**: > 1024px - Sidebar completo

### Adaptaciones Móviles
- Sidebar como overlay
- Tablas con scroll horizontal
- Botones más grandes para touch
- Dropdowns adaptados al viewport

## Personalización

### Colores Principales
```css
:root {
    --primary-color: #2f9f37;      /* Verde principal */
    --primary-hover: #2f9f37e6;    /* Verde hover */
    --secondary-color: #6b7280;    /* Gris secundario */
    --success-color: #10b981;      /* Verde éxito */
    --warning-color: #f59e0b;      /* Amarillo advertencia */
    --error-color: #ef4444;        /* Rojo error */
}
```

### Añadir Nueva Sección al Sidebar
1. Editar `partials/sidebar.blade.php`
2. Agregar item al array `$navigation`
3. Definir icono SVG, ruta y roles permitidos

### Añadir Nueva Vista
1. Crear vista en `resources/views/`
2. Extender `layouts.dashboard`
3. Definir título con `@section('title')`
4. Agregar contenido con `@section('content')`

## Mejores Prácticas Implementadas

### Blade
- ✅ Uso de `@extends` para layouts
- ✅ Uso de `@include` para componentes
- ✅ Uso de `@section` para contenido dinámico
- ✅ Uso de `asset()` para recursos
- ✅ Uso de `route()` para enlaces

### CSS
- ✅ Variables CSS para consistencia
- ✅ Componentes reutilizables
- ✅ Responsive design
- ✅ Estructura modular

### JavaScript
- ✅ Funciones modulares
- ✅ Event delegation
- ✅ Manejo de errores
- ✅ Utilidades reutilizables

### Seguridad
- ✅ Tokens CSRF en formularios
- ✅ Validación de roles
- ✅ Sanitización de datos
- ✅ Rutas protegidas

## Rutas Configuradas

### Dashboard Principal
- `GET /dashboard` → Vista: `dashboard-new.blade.php`

### Panel de Administración
- `GET /admin/panel` → Vista: `admin.panel-new.blade.php`

## Próximos Pasos

1. **Migrar vistas existentes** al nuevo layout
2. **Añadir más componentes** reutilizables
3. **Implementar temas** (claro/oscuro)
4. **Añadir animaciones** avanzadas
5. **Optimizar rendimiento** de CSS/JS

## Soporte

Para dudas o problemas con la implementación, revisar:
- Logs de Laravel en `storage/logs/`
- Console del navegador para errores JS
- Network tab para problemas de assets

---

**Desarrollado para la Diócesis de Apartadó - Sistema de Cursos**
