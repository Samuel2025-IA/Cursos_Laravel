# Sistema de Recuperación de Contraseña con Códigos

## 🎯 Descripción

Este sistema implementa la recuperación de contraseña mediante códigos de verificación enviados por correo electrónico, reemplazando el sistema tradicional de enlaces de Laravel.

## ✨ Características

- **Códigos de 6 dígitos** generados automáticamente
- **Expiración automática** en 15 minutos
- **Validación de correo** existente en la base de datos
- **Plantilla de correo personalizada** y responsive
- **Interfaz moderna** con Tailwind CSS
- **Seguridad mejorada** con códigos únicos y expiración

## 🚀 Instalación y Configuración

### 1. Configurar el Archivo .env

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-correo@gmail.com
MAIL_FROM_NAME="Tu Aplicación"
```

### 2. Obtener Contraseña de Aplicación de Gmail

1. Ve a [myaccount.google.com](https://myaccount.google.com/)
2. Activa la **Verificación en dos pasos**
3. Ve a **Seguridad** → **Contraseñas de aplicación**
4. Selecciona "Correo" y "Windows Computer"
5. Copia la contraseña de 16 caracteres

### 3. Ejecutar Migraciones

```bash
php artisan migrate
```

## 📁 Estructura del Sistema

### Archivos Creados

```
app/
├── Http/Controllers/Auth/
│   └── PasswordResetCodeController.php    # Controlador principal
├── Models/
│   └── PasswordResetCode.php             # Modelo para códigos
└── Console/Commands/
    ├── CreatePasswordResetCodesTable.php  # Comando para crear tabla
    └── TestPasswordResetCode.php         # Comando de prueba

resources/views/
├── auth/
│   ├── forgot-password-code.blade.php    # Solicitar código
│   ├── enter-code.blade.php              # Ingresar código
│   └── reset-password-with-code.blade.php # Cambiar contraseña
└── emails/
    └── password-reset-code.blade.php     # Plantilla de correo

routes/
└── auth.php                              # Rutas del sistema
```

### Base de Datos

```sql
CREATE TABLE password_reset_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX (email)
);
```

## 🔄 Flujo de Usuario

### 1. Solicitar Recuperación
- Usuario va a `/forgot-password`
- Ingresa su correo electrónico
- Sistema valida que el correo existe
- Se genera un código de 6 dígitos
- Se envía el código por correo

### 2. Verificar Código
- Usuario recibe el correo con el código
- Va a `/enter-code` (automáticamente)
- Ingresa el código de 6 dígitos
- Sistema valida el código y expiración

### 3. Cambiar Contraseña
- Código verificado correctamente
- Usuario va a `/reset-password-with-code`
- Ingresa nueva contraseña y confirmación
- Contraseña actualizada en la base de datos

## 🛠️ Comandos Artisan Disponibles

### Crear Tabla
```bash
php artisan table:create-password-reset-codes
```

### Probar Sistema
```bash
php artisan test:password-reset-code test@example.com
```

## 📧 Plantilla de Correo

La plantilla incluye:
- **Diseño responsive** para móviles y escritorio
- **Código destacado** en formato grande y legible
- **Información de expiración** clara
- **Advertencias de seguridad**
- **Estilos CSS inline** para compatibilidad

## 🔒 Seguridad

- **Códigos únicos** para cada solicitud
- **Expiración automática** en 15 minutos
- **Validación de correo** existente
- **Limpieza automática** de códigos expirados
- **Sesiones seguras** para el proceso de cambio

## 🧪 Pruebas

### Probar Envío de Correos
1. Configura tu archivo `.env` con Gmail
2. Ve a `/forgot-password`
3. Ingresa un correo válido de tu base de datos
4. Verifica que llegue el correo con el código

### Probar sin Enviar Correos (Desarrollo)
```env
MAIL_MAILER=log
```
Los correos se guardarán en `storage/logs/laravel.log`

## 🐛 Solución de Problemas

### Error: "Connection could not be established"
- Verifica `MAIL_HOST = smtp.gmail.com`
- Verifica `MAIL_PORT = 587`
- Verifica `MAIL_ENCRYPTION = tls`

### Error: "Authentication failed"
- Usa **contraseña de aplicación**, no tu contraseña normal
- Activa la verificación en dos pasos en Google
- Verifica que el correo esté correcto

### Error: "Could not find driver"
- Habilita la extensión OpenSSL en PHP
- En XAMPP: `php.ini` → `extension=openssl`

## 📱 Personalización

### Cambiar Tiempo de Expiración
En `PasswordResetCodeController.php`:
```php
$expiresAt = Carbon::now()->addMinutes(30); // Cambiar a 30 minutos
```

### Cambiar Longitud del Código
En `PasswordResetCode.php`:
```php
public static function generateCode(): string
{
    return str_pad(rand(0, 999999), 8, '0', STR_PAD_LEFT); // Cambiar a 8 dígitos
}
```

### Personalizar Plantilla de Correo
Edita `resources/views/emails/password-reset-code.blade.php`

## 🚀 Producción

Para producción, considera:
- **Mailgun** o **SendGrid** en lugar de Gmail
- **Colas de trabajo** para envío asíncrono
- **Rate limiting** para prevenir spam
- **Logs de auditoría** para seguimiento
- **Monitoreo** de entregas de correo

## 📞 Soporte

Si tienes problemas:
1. Verifica la configuración del `.env`
2. Revisa los logs en `storage/logs/laravel.log`
3. Ejecuta `php artisan test:password-reset-code test@example.com`
4. Verifica que la tabla `password_reset_codes` existe y tiene la estructura correcta

---

**¡El sistema está listo para usar! 🎉**
