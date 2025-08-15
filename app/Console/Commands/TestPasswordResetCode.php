<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordResetCode;
use Carbon\Carbon;

class TestPasswordResetCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:password-reset-code {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el sistema de códigos de recuperación de contraseña';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Probando sistema de códigos para: {$email}");

        // Genera un código de prueba
        $code = PasswordResetCode::generateCode();
        $expiresAt = Carbon::now()->addMinutes(15);

        $this->info("Código generado: {$code}");
        $this->info("Expira en: {$expiresAt->format('H:i:s')}");

        // Crea el código en la base de datos
        $resetCode = PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        $this->info("Código guardado en la base de datos con ID: {$resetCode->id}");

        // Verifica que se puede encontrar
        $foundCode = PasswordResetCode::findValidCode($email, $code);
        
        if ($foundCode) {
            $this->info("✅ Código encontrado y válido");
        } else {
            $this->error("❌ Error: Código no encontrado o inválido");
        }

        // Limpia códigos expirados
        PasswordResetCode::cleanExpiredCodes();
        $this->info("Códigos expirados limpiados");

        return 0;
    }
}
