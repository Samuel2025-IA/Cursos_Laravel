<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #e2e8f0;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
        .email-container {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid #475569;
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            pointer-events: none;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 35px;
            background: rgba(30, 41, 59, 0.5);
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #f1f5f9;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.8;
            color: #cbd5e1;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white !important;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3), 0 4px 6px -2px rgba(16, 185, 129, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .reset-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.4), 0 10px 10px -5px rgba(16, 185, 129, 0.2);
        }
        .expiry-notice {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
            border: 1px solid rgba(251, 191, 36, 0.3);
            border-left: 4px solid #fbbf24;
            padding: 20px;
            margin: 25px 0;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        .expiry-notice .icon {
            color: #fbbf24;
            font-size: 20px;
            margin-right: 10px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
        .expiry-text {
            color: #fde68a;
            font-size: 15px;
            font-weight: 600;
        }
        .alternative-link {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alternative-link h3 {
            color: #374151;
            font-size: 16px;
            margin: 0 0 10px 0;
        }
        .alternative-link p {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0;
            word-break: break-all;
        }
        .footer {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #475569;
        }
        .footer p {
            margin: 8px 0;
            font-size: 14px;
            color: #94a3b8;
        }
        .footer p:first-child {
            color: #e2e8f0;
            font-weight: 600;
            font-size: 16px;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
            .reset-button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Diócesis de Apartadó</div>
            <div style="font-size: 16px; opacity: 0.9; margin-bottom: 15px;">Sistema de Cursos</div>
            <h1>Recuperación de Contraseña</h1>
        </div>
        
        <div class="content">
            <div class="greeting">¡Hola {{ $userName ?? 'Usuario' }}!</div>
            
            <div class="message">
                Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Para continuar con el proceso, haz clic en el botón de abajo.
            </div>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                     Restablecer Contraseña
                </a>
            </div>
            
            <div class="expiry-notice">
                <span class="icon">⚠️</span>
                <span class="expiry-text">
                    <strong>Importante:</strong> Este enlace expira a las {{ $expiresAt }} y solo puede usarse una vez.
                </span>
            </div>
            
            <div class="message" style="margin-top: 25px; font-size: 14px; color: #6b7280; line-height: 1.5;">
                Si no solicitaste este cambio, puedes ignorar este mensaje. Tu cuenta permanecerá segura.
            </div>
            
            <div class="message" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <strong>Equipo de la Diócesis de Apartadó</strong><br>
                <span style="color: #6b7280; font-size: 14px;">Sistema de Cursos</span>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Diócesis de Apartadó</strong></p>
            <p>Sistema de Gestión de Cursos</p>
            <p style="color: #9ca3af; font-size: 12px;">
                Este es un mensaje automático, por favor no respondas a este correo.
            </p>
        </div>
    </div>
</body>
</html>
