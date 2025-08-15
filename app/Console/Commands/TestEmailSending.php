<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmailSending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el envío de correos electrónicos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("🔍 Probando envío de correo a: {$email}");
        $this->info("📧 Configuración actual de correo:");
        $this->info("   MAIL_MAILER: " . config('mail.default'));
        $this->info("   MAIL_HOST: " . config('mail.mailers.smtp.host'));
        $this->info("   MAIL_PORT: " . config('mail.mailers.smtp.port'));
        $this->info("   MAIL_USERNAME: " . config('mail.mailers.smtp.username'));
        $this->info("   MAIL_ENCRYPTION: " . config('mail.mailers.smtp.scheme'));

        try {
            // Envía un correo de prueba simple
            Mail::raw('Este es un correo de prueba desde Laravel - ' . now(), function ($message) use ($email) {
                $message->to($email)
                        ->subject('Prueba de Correo - Sistema de Cursos');
            });

            $this->info("✅ Correo enviado exitosamente!");
            $this->info("📬 Revisa tu bandeja de entrada y spam");
            
        } catch (\Exception $e) {
            $this->error("❌ Error al enviar correo:");
            $this->error("   " . $e->getMessage());
            
            // Log del error para debugging
            Log::error('Error en envío de correo: ' . $e->getMessage());
            
            $this->info("\n🔧 Soluciones posibles:");
            $this->info("   1. Verifica tu archivo .env");
            $this->info("   2. Usa contraseña de aplicación, no tu contraseña normal");
            $this->info("   3. Activa verificación en dos pasos en Google");
            $this->info("   4. Verifica que el correo esté correcto");
        }

        return 0;
    }
}
