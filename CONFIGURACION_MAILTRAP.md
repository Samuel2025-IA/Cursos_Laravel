# Configuración con Mailtrap (Recomendado para Pruebas)

## 🎯 ¿Por qué Mailtrap?

Mailtrap es una herramienta gratuita que captura todos los correos en un entorno de pruebas, sin enviarlos realmente. Es perfecto para desarrollo y debugging.

## 🚀 Configuración Rápida

### 1. Crear Cuenta en Mailtrap

1. Ve a [mailtrap.io](https://mailtrap.io)
2. Regístrate gratis
3. Crea un nuevo proyecto
4. Ve a "Email Testing" → "Inboxes"
5. Crea un nuevo inbox

### 2. Configurar tu .env

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_de_mailtrap
MAIL_PASSWORD=tu_password_de_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="Sistema de Cursos"
```

### 3. Ejemplo de Configuración

```env
# Ejemplo real (reemplaza con tus credenciales)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=abc123def456
MAIL_PASSWORD=xyz789uvw012
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@cursos.com
MAIL_FROM_NAME="Sistema de Cursos"
```

## 🔧 Ventajas de Mailtrap

- ✅ **Gratis** para desarrollo
- ✅ **No spam** - todos los correos van a tu inbox de pruebas
- ✅ **Configuración simple** - solo copiar y pegar
- ✅ **Debugging fácil** - ves exactamente qué se está enviando
- ✅ **Sin límites** de envío en desarrollo

## 🧪 Probar con Mailtrap

1. Configura tu `.env` con las credenciales de Mailtrap
2. Ejecuta: `php artisan test:email tu-correo@ejemplo.com`
3. Ve a tu inbox de Mailtrap
4. ¡Verás el correo ahí!

## 📧 Configuración Alternativa: Gmail

Si prefieres seguir con Gmail, asegúrate de:

1. **Activar verificación en dos pasos**
2. **Generar contraseña de aplicación** (16 caracteres)
3. **Usar la contraseña de aplicación** en `MAIL_PASSWORD`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop  # Contraseña de aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-correo@gmail.com
MAIL_FROM_NAME="Sistema de Cursos"
```

## 🚨 Solución de Problemas Comunes

### Error: "Connection could not be established"
- Verifica que el host y puerto sean correctos
- Para Mailtrap: `sandbox.smtp.mailtrap.io:2525`
- Para Gmail: `smtp.gmail.com:587`

### Error: "Authentication failed"
- **Mailtrap**: Verifica username y password
- **Gmail**: Usa contraseña de aplicación, no tu contraseña normal

### No llegan los correos
- **Mailtrap**: Revisa tu inbox de Mailtrap
- **Gmail**: Revisa spam y configuración de filtros

## 🎯 Recomendación

**Para desarrollo**: Usa **Mailtrap** - es más simple y confiable
**Para producción**: Usa **Gmail** o servicios como **SendGrid**

---

**¡Con Mailtrap tendrás el sistema funcionando en 5 minutos! 🚀**
