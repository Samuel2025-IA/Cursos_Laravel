# Scripts de Layouts

Este directorio contiene todos los scripts JavaScript organizados por layout del sistema.

## Estructura de Archivos

### JavaScript
- `app.js` - Scripts para el layout principal (app.blade.php)
- `guest.js` - Scripts para el layout de invitados (guest.blade.php)
- `navigation.js` - Scripts para la navegación (navigation.blade.php)
- `app-simple.js` - Scripts para el layout simple (app-simple.blade.php)

### CSS
- `../css/views/layouts/layouts.css` - Estilos comunes para todos los layouts

## Funcionalidades por Layout

### app.js
- ✅ Configuración de Tailwind CSS
- ✅ Funciones globales de alertas (error, warning, info, success)
- ✅ Manejo de alertas de sesión de Laravel
- ✅ Manejo de errores de validación específicos
- ✅ Exportación de funciones para uso en otros scripts

### guest.js
- ✅ Configuración de SweetAlert2 con Toast
- ✅ Funciones de alertas específicas para invitados
- ✅ Manejo de alertas de sesión (una sola a la vez)
- ✅ Aplicación de estilos de registro
- ✅ Exportación de funciones para uso en otros scripts

### navigation.js
- ✅ Función de confirmación de logout con SweetAlert2
- ✅ Manejo del menú móvil
- ✅ Event listeners para navegación
- ✅ Funciones de utilidad para rutas y usuario
- ✅ Exportación de funciones para uso en otros scripts

### app-simple.js
- ✅ Configuración de Tailwind CSS
- ✅ Funciones básicas de alertas
- ✅ Manejo de alertas de sesión
- ✅ Aplicación de estilos específicos
- ✅ Exportación de funciones para uso en otros scripts

## Uso en las Vistas

### En app.blade.php
```html
<script src="{{ asset('js/views/layouts/app.js') }}"></script>
```

### En guest.blade.php
```html
<script src="{{ asset('js/views/layouts/guest.js') }}"></script>
```

### En navigation.blade.php
```html
<script src="{{ asset('js/views/layouts/navigation.js') }}"></script>
```

### En app-simple.blade.php
```html
<script src="{{ asset('js/views/layouts/app-simple.js') }}"></script>
```

## Pasar Datos de Laravel a JavaScript

### Alertas de Sesión
```php
@if(session('error'))
    <script>
        window.sessionError = '{{ session('error') }}';
    </script>
@endif
```

### Errores de Validación
```php
@if($errors->updatePassword->any())
    <script>
        window.passwordErrors = [
            @foreach($errors->updatePassword->all() as $error)
                '{{ $error }}',
            @endforeach
        ];
    </script>
@endif
```

## Funciones Exportadas

### AppAlerts (desde app.js)
- `showError(message)`
- `showWarning(message)`
- `showInfo(message)`
- `showSuccess(message)`

### GuestAlerts (desde guest.js)
- `showInfo(message)`
- `showError(message)`
- `showSuccess(message)`
- `showErrorAlert(message)`

### Navigation (desde navigation.js)
- `confirmLogout()`
- `toggleMobileMenu()`
- `closeMobileMenu()`
- `isCurrentRoute(routeName)`
- `getCurrentUser()`

### AppSimple (desde app-simple.js)
- `showError(message)`
- `showWarning(message)`
- `showInfo(message)`
- `showSuccess(message)`

## Beneficios de esta Organización

1. **Separación de Responsabilidades**: Cada layout tiene su propio archivo JS
2. **Mantenibilidad**: Fácil localizar y modificar funcionalidades específicas
3. **Reutilización**: Funciones exportadas pueden ser usadas en otros scripts
4. **Limpieza**: Los archivos Blade están más limpios y organizados
5. **Escalabilidad**: Fácil agregar nuevas funcionalidades sin afectar otros layouts
6. **Debugging**: Más fácil identificar problemas en funcionalidades específicas

## Convenciones

- ✅ Todos los archivos usan JSDoc para documentación
- ✅ Funciones globales se exportan en objetos con nombres descriptivos
- ✅ Event listeners se configuran en `DOMContentLoaded`
- ✅ Console.log para debugging con emojis identificativos
- ✅ Manejo de errores con fallbacks
- ✅ Código organizado en secciones claras con comentarios









