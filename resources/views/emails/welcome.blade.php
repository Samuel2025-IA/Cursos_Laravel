<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a los Cursos de la Diócesis</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            line-height: 1.6;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: #0D1117;
            padding: 30px 40px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 400;
            opacity: 0.95;
        }
        .content {
            padding: 35px 40px;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 25px;
        }
        .welcome-message h2 {
            color: #374151;
            font-size: 14px;
            margin: 0 0 12px 0;
            font-weight: 500;
        }
        .welcome-message p {
            color: #6b7280;
            font-size: 11px;
            margin: 0;
            line-height: 1.5;
        }
        .cta-section {
            text-align: right;
            margin: 25px 0;
        }
        .cta-button {
            display: inline-block;
            background: #0D1117;
            color: white !important;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(13, 17, 23, 0.3);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 17, 23, 0.4);
        }
        .disclaimer {
            background-color: #f0f0f0;
            border-left: 4px solid #0D1117;
            border-radius: 4px;
            padding: 25px 30px;
            margin: 30px 0;
        }
        .disclaimer h3 {
            color: #0D1117;
            font-size: 14px;
            margin: 0 0 10px 0;
            font-weight: 600;
        }
        .disclaimer p {
            color: #0D1117;
            font-size: 13px;
            margin: 0;
            line-height: 1.4;
        }
        .footer {
            background-color: #f0f0f0;
            padding: 25px 40px;
            text-align: center;
            border-top: 1px solid #0D1117;
        }
        .footer p {
            color: #0D1117;
            font-size: 13px;
            margin: 0 0 8px 0;
        }
        .footer .diocese-info {
            color: #0D1117;
            font-weight: 600;
            font-size: 16px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 25px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
            .welcome-message h2 {
                font-size: 24px;
            }
            .banner-overlay {
                position: static;
                margin: 20px;
                max-width: none;
            }
            .cta-button {
                padding: 15px 30px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>DIÓCESIS DE APARTADÓ</h1>
            <h2>¡Bienvenido a los Cursos!</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <h2>Hola, {{ strtoupper($user->primer_nombre) }}</h2>
                <p>Te hemos habilitado un acceso para ingresar a los servicios de cursos de la Diócesis de Apartadó. Desde ya puedes comenzar tu formación espiritual y académica en nuestra plataforma.</p>
            </div>

            <!-- CTA Section -->
            <div class="cta-section">
                <a href="{{ route('login') }}" class="cta-button">
                    Comenzar Ahora
                </a>
            </div>


            <!-- Disclaimer -->
            <div class="disclaimer">
                <h3>IMPORTANTE:</h3>
                <p>Es responsabilidad de cada persona y empresa el uso que se haga de la clave de acceso. DIÓCESIS DE APARTADÓ queda exonerada por el mal uso que pueda hacerse de la clave que se asigna, la cual es de carácter personal, confidencial, indelegable e intransferible.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="diocese-info">Diócesis de Apartadó</p>
            <p>Sistema de Cursos y Capacitaciones</p>
            <p>Si tienes alguna pregunta, no dudes en contactarnos</p>
        </div>
    </div>
</body>
</html>
