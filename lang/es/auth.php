<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña proporcionada es incorrecta.',
    'throttle' => 'Demasiados intentos de acceso. Por favor intente nuevamente en :seconds segundos.',

    /*
    |--------------------------------------------------------------------------
    | Custom Authentication Messages
    |--------------------------------------------------------------------------
    |
    | Here you can add custom authentication messages for your application.
    |
    */

    'login' => [
        'title' => 'Iniciar Sesión',
        'subtitle' => 'Accede a tu cuenta',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'remember' => 'Recordarme',
        'forgot' => '¿Olvidaste tu contraseña?',
        'submit' => 'Iniciar Sesión',
        'no_account' => '¿No tienes una cuenta?',
        'register' => 'Regístrate aquí',
    ],

    'register' => [
        'title' => 'Registrarse',
        'subtitle' => 'Crea tu cuenta',
        'name' => 'Nombre',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'confirm_password' => 'Confirmar Contraseña',
        'submit' => 'Registrarse',
        'has_account' => '¿Ya tienes una cuenta?',
        'login' => 'Inicia sesión aquí',
    ],

    'password' => [
        'reset' => [
            'title' => 'Restablecer Contraseña',
            'email' => 'Correo Electrónico',
            'submit' => 'Enviar Enlace de Restablecimiento',
            'back_to_login' => 'Volver al inicio de sesión',
        ],
        'confirm' => [
            'title' => 'Confirmar Contraseña',
            'message' => 'Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.',
            'password' => 'Contraseña',
            'submit' => 'Confirmar',
        ],
    ],

    'verification' => [
        'title' => 'Verificar Correo Electrónico',
        'message' => 'Gracias por registrarte. Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarte? Si no recibiste el correo, con gusto te enviaremos otro.',
        'resend' => 'Reenviar correo de verificación',
        'logout' => 'Cerrar Sesión',
    ],

    'profile' => [
        'title' => 'Perfil',
        'information' => 'Información del Perfil',
        'information_description' => 'Actualiza la información del perfil de tu cuenta y dirección de correo electrónico.',
        'password' => 'Actualizar Contraseña',
        'password_description' => 'Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantener la seguridad.',
        'delete' => 'Eliminar Cuenta',
        'delete_description' => 'Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente.',
        'delete_confirm' => '¿Estás seguro de que quieres eliminar tu cuenta?',
        'delete_warning' => 'Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.',
    ],

    'messages' => [
        'login_success' => 'Has iniciado sesión correctamente.',
        'login_failed' => 'Las credenciales proporcionadas son incorrectas.',
        'logout_success' => 'Has cerrado sesión correctamente.',
        'register_success' => 'Tu cuenta ha sido creada exitosamente.',
        'password_reset_sent' => 'Hemos enviado por correo electrónico el enlace para restablecer tu contraseña.',
        'password_reset_success' => 'Tu contraseña ha sido restablecida exitosamente.',
        'email_verification_sent' => 'Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.',
        'email_verification_success' => 'Tu correo electrónico ha sido verificado exitosamente.',
        'profile_updated' => 'Tu perfil ha sido actualizado exitosamente.',
        'password_updated' => 'Tu contraseña ha sido actualizada exitosamente.',
        'account_deleted' => 'Tu cuenta ha sido eliminada exitosamente.',
    ],

    'errors' => [
        'invalid_credentials' => 'Las credenciales proporcionadas son incorrectas.',
        'account_not_found' => 'No se encontró una cuenta con ese correo electrónico.',
        'password_incorrect' => 'La contraseña proporcionada es incorrecta.',
        'email_not_verified' => 'Tu correo electrónico no ha sido verificado.',
        'account_disabled' => 'Tu cuenta ha sido deshabilitada.',
        'too_many_attempts' => 'Demasiados intentos de acceso. Por favor intente nuevamente más tarde.',
        'session_expired' => 'Tu sesión ha expirado. Por favor inicia sesión nuevamente.',
    ],

];


