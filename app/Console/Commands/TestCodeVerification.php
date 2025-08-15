<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestCodeVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:code-verification {email} {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba la verificación del código paso a paso';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $code = $this->argument('code');

        $this->info("🧪 Probando verificación de código paso a paso");
        $this->info("📧 Email: {$email}");
        $this->info("🔢 Código: {$code}");

        // 1. Crear una request simulada
        $request = new Request();
        $request->merge([
            'email' => $email,
            'code' => $code
        ]);

        // 2. Validar la request
        $this->info("\n📋 Paso 1: Validando request...");
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:8'],
        ]);

        if ($validator->fails()) {
            $this->error("❌ Validación falló:");
            foreach ($validator->errors()->all() as $error) {
                $this->error("   - {$error}");
            }
            return 1;
        }

        $this->info("✅ Validación exitosa");

        // 3. Probar el método findValidCode directamente
        $this->info("\n📋 Paso 2: Probando findValidCode...");
        $resetCode = \App\Models\PasswordResetCode::findValidCode($email, $code);
        
        if ($resetCode) {
            $this->info("✅ findValidCode encontró el código");
            $this->info("   - ID: {$resetCode->id}");
            $this->info("   - Usado: " . ($resetCode->used ? 'SÍ' : 'NO'));
            $this->info("   - Expira: {$resetCode->expires_at}");
        } else {
            $this->error("❌ findValidCode NO encontró el código");
            return 1;
        }

        // 4. Simular la verificación completa
        $this->info("\n📋 Paso 3: Simulando verificación completa...");
        
        try {
            $controller = new \App\Http\Controllers\Auth\PasswordResetCodeController();
            $response = $controller->verifyCode($request);
            
            $this->info("✅ Verificación exitosa");
            $this->info("   - Response: " . get_class($response));
            
        } catch (\Exception $e) {
            $this->error("❌ Error en verificación:");
            $this->error("   - " . $e->getMessage());
            $this->error("   - Archivo: " . $e->getFile() . ":" . $e->getLine());
            return 1;
        }

        $this->info("\n🎉 ¡Todo funcionó correctamente!");
        return 0;
    }
}
