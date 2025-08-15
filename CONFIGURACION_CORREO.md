# Configuración de Correo Electrónico para Recuperación de Contraseña

## Configuración para Gmail

Para que funcione el envío de códigos de recuperación de contraseña, necesitas configurar tu archivo `.env` con las siguientes variables:

### 1. Configuración de Correo

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

**IMPORTANTE:** No uses tu contraseña normal de Gmail. Necesitas generar una "Contraseña de Aplicación":

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Activa la **Verificación en dos pasos** si no la tienes activada
3. Ve a **Seguridad** → **Contraseñas de aplicación**
4. Selecciona "Correo" y "Windows Computer"
5. Copia la contraseña generada (16 caracteres)
6. Usa esa contraseña en `MAIL_PASSWORD`

### 3. Ejemplo Completo del .env

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:tu-clave-aqui
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cursos
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=miempresa@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=miempresa@gmail.com
MAIL_FROM_NAME="Sistema de Cursos"
```

### 4. Verificar Configuración

Después de configurar, puedes probar el envío de correos:

1. Ejecuta las migraciones: `php artisan migrate`
2. Ve a `/forgot-password`
3. Ingresa un correo que exista en tu base de datos
4. Verifica que llegue el correo con el código

### 5. Solución de Problemas

**Error: "Connection could not be established"**
- Verifica que `MAIL_HOST` sea `smtp.gmail.com`
- Verifica que `MAIL_PORT` sea `587`
- Verifica que `MAIL_ENCRYPTION` sea `tls`

**Error: "Authentication failed"**
- Verifica que `MAIL_USERNAME` sea tu correo completo
- Verifica que `MAIL_PASSWORD` sea la contraseña de aplicación (no tu contraseña normal)
- Asegúrate de tener la verificación en dos pasos activada

**Error: "Could not find driver"**
- Instala la extensión PHP OpenSSL: `sudo apt-get install php-openssl` (Linux) o habilítala en XAMPP

### 6. Configuración Alternativa para Desarrollo

Si quieres probar sin enviar correos reales, puedes usar:

```env
MAIL_MAILER=log
```

Esto guardará los correos en `storage/logs/laravel.log` para que puedas ver el contenido sin enviarlos.

### 7. Seguridad

- Nunca subas tu archivo `.env` a Git
- Cambia las contraseñas de aplicación regularmente
- Usa correos corporativos cuando sea posible
- Considera usar servicios como Mailgun o SendGrid para producción
