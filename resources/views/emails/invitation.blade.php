<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitación al Sistema de Cursos</title>
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
        .code-container {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            border: 2px solid rgba(16, 185, 129, 0.3);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            backdrop-filter: blur(10px);
        }
        .code-label {
            font-size: 18px;
            font-weight: 600;
            color: #10b981;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .code {
            font-size: 36px;
            font-weight: 700;
            color: #10b981;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .invitation-button {
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
        .invitation-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.4), 0 10px 10px -5px rgba(16, 185, 129, 0.2);
        }
        .instructions {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-left: 4px solid #3b82f6;
            padding: 25px;
            margin: 25px 0;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        .instructions h3 {
            color: #3b82f6;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .instructions ol {
            color: #cbd5e1;
            font-size: 15px;
            line-height: 1.8;
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 8px;
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
            .invitation-button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
            .code {
                font-size: 28px;
                letter-spacing: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Diócesis de Apartadó</div>
            <div style="font-size: 16px; opacity: 0.9; margin-bottom: 15px;">Sistema de Cursos</div>
            <h1>Invitación al Sistema</h1>
        </div>
        
        <div class="content">
            <div class="greeting">¡Hola!</div>
            
            <div class="message">
                Has sido invitado a formar parte del <strong>Sistema de Cursos de la Diócesis de Apartadó</strong>. Este sistema te permitirá acceder a cursos y capacitaciones especializadas.
            </div>
            
            <div class="code-container">
                <div class="code-label">Tu código de invitación es:</div>
                <div class="code">{{ $invitationCode->code }}</div>
            </div>
            
            <div class="instructions">
                <h3>📋 Instrucciones para registrarte:</h3>
                <ol>
                    <li>Haz clic en el botón "Comenzar Registro"</li>
                    <li>Ingresa el código: <strong>{{ $invitationCode->code }}</strong></li>
                    <li>Completa tu información personal</li>
                    <li>¡Listo! Ya podrás acceder al sistema</li>
                </ol>
            </div>
            
            <div class="button-container">
                <a href="{{ url('/verify-invitation') }}" class="invitation-button">
                    Comenzar Registro
                </a>
            </div>
            
            <div class="expiry-notice">
                <span class="icon">⚠️</span>
                <span class="expiry-text">
                    <strong>Importante:</strong> Este código expira el {{ $invitationCode->expires_at->format('d/m/Y \a \l\a\s H:i') }} y solo puede usarse una vez.
                </span>
            </div>
            
            <div class="message" style="margin-top: 25px; font-size: 14px; color: #6b7280; line-height: 1.5;">
                Si tienes alguna pregunta o necesitas ayuda, no dudes en contactar al administrador del sistema.
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