# Sistema de Cursos - Diócesis de Apartadó

Sistema web desarrollado en Laravel para la gestión de cursos y capacitaciones de la Diócesis de Apartadó.

## Características Principales

- **Sistema de Autenticación Completo**
  - Login/Logout de usuarios
  - Recuperación de contraseñas
  - Gestión de perfiles
  - Validación de seguridad para cuentas eliminadas
  - **Sistema de Verificación de Email Mejorado** con tokens de un solo uso

- **Gestión de Usuarios**
  - Creación y edición de perfiles
  - Sistema de roles y permisos
  - Perfiles de solo lectura

- **Gestión de Cursos**
  - Registro de cursos
  - Sistema de códigos de verificación
  - Dashboard administrativo

- **Interfaz Moderna**
  - Diseño responsive con TailwindCSS
  - Componentes elegantes y profesionales
  - Alertas interactivas con SweetAlert2

## Tecnologías Utilizadas

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + TailwindCSS
- **Base de Datos**: SQLite (configurable para MySQL/PostgreSQL)
- **JavaScript**: Vanilla JS + SweetAlert2
- **CSS**: TailwindCSS + CSS Personalizado

## Requisitos del Sistema

- PHP >= 8.1
- Composer
- Node.js & NPM
- XAMPP/WAMP/LAMP (para desarrollo local)

## Instalación

### 1. Clonar el Repositorio
```bash
git clone [URL_DEL_REPOSITORIO]
cd cursos
```

### 2. Instalar Dependencias PHP
```bash
composer install
```

### 3. Instalar Dependencias Node.js
```bash
npm install
```

### 4. Configuración del Entorno
```bash
# Copiar archivo de configuración
cp env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 5. Configuración de Base de Datos
```bash
# Crear base de datos SQLite
touch database/database.sqlite

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (opcional)
php artisan db:seed
```

### 6. Compilar Assets
```bash
npm run dev
# o para producción
npm run build
```

### 7. Iniciar Servidor
```bash
php artisan serve
```

El sistema estará disponible en: `http://localhost:8000`

## Configuración Adicional

### Configuración de Correo
Para el sistema de recuperación de contraseñas y verificación de email, configurar en `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=tu_servidor_smtp
MAIL_PORT=587
MAIL_USERNAME=tu_email
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls

# Configuración específica para Gmail (opcional)
GMAIL_USERNAME=tu_email@gmail.com
GMAIL_PASSWORD=tu_app_password
GMAIL_FROM_ADDRESS=tu_email@gmail.com
GMAIL_FROM_NAME="Sistema de Cursos"
```

### Configuración de Base de Datos
Para cambiar a MySQL/PostgreSQL, modificar en `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=password
```

## Estructura del Proyecto

```
cursos/
├── app/                    # Lógica de la aplicación
│   ├── Http/Controllers/  # Controladores
│   ├── Models/            # Modelos Eloquent
│   ├── Notifications/     # Notificaciones de email
│   └── Providers/         # Proveedores de servicios
├── config/                # Archivos de configuración
├── database/              # Migraciones y seeders
├── public/                # Archivos públicos
├── resources/             # Vistas y assets
│   ├── views/            # Templates Blade
│   ├── css/              # Estilos CSS
│   └── js/               # JavaScript
├── routes/                # Definición de rutas
└── storage/               # Archivos de almacenamiento
```

## Seguridad

- **Variables de Entorno**: Todas las configuraciones sensibles están en `.env`
- **Validación**: Sistema robusto de validación en formularios
- **Autenticación**: Sistema de autenticación nativo de Laravel
- **CSRF Protection**: Protección automática contra ataques CSRF
- **SQL Injection**: Protegido con Eloquent ORM
- **Verificación de Email**: Sistema de tokens seguros con expiración temporal

## Documentación Adicional

- [Sistema de Verificación de Email](VERIFICATION_SYSTEM.md)
- [Sistema de Códigos de Verificación](README_SISTEMA_CODIGOS.md)
- [Configuración de Correo](CONFIGURACION_CORREO.md)

## Características Avanzadas

### Sistema de Verificación de Email
- Tokens de un solo uso con expiración configurable
- Validación de seguridad contra reutilización
- Limpieza automática de tokens expirados
- Comandos Artisan para mantenimiento

### Sistema de Códigos de Verificación
- Generación automática de códigos únicos
- Validación de códigos en tiempo real
- Sistema de auditoría completo

## Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto es desarrollado para la Diócesis de Apartadó. Todos los derechos reservados.

## Contacto

Para soporte técnico o consultas sobre el proyecto, contactar al equipo de desarrollo.

---

**Desarrollado con amor para la Diócesis de Apartadó**
