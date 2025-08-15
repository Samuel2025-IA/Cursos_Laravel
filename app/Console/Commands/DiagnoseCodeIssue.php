<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordResetCode;
use Carbon\Carbon;

class DiagnoseCodeIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose:code-issue {email} {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostica por qué no funciona la verificación del código';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $code = $this->argument('code');

        $this->info("🔍 Diagnosticando problema con código para: {$email}");
        $this->info("📝 Código a verificar: {$code}");

        // 1. Buscar el código en la base de datos
        $resetCode = PasswordResetCode::where('email', $email)
            ->where('code', $code)
            ->first();

        if (!$resetCode) {
            $this->error("❌ Código NO encontrado en la base de datos");
            $this->info("🔍 Buscando códigos para este email...");
            
            $allCodes = PasswordResetCode::where('email', $email)->get();
            if ($allCodes->count() > 0) {
                $this->info("📋 Códigos encontrados para {$email}:");
                foreach ($allCodes as $codeRecord) {
                    $this->info("   - Código: {$codeRecord->code} | Usado: " . ($codeRecord->used ? 'SÍ' : 'NO') . " | Expira: {$codeRecord->expires_at}");
                }
            } else {
                $this->error("   No hay códigos para este email");
            }
            return 1;
        }

        $this->info("✅ Código encontrado en la base de datos");
        $this->info("📊 Detalles del código:");
        $this->info("   - ID: {$resetCode->id}");
        $this->info("   - Código: {$resetCode->code}");
        $this->info("   - Usado: " . ($resetCode->used ? 'SÍ' : 'NO'));
        $this->info("   - Expira: {$resetCode->expires_at}");
        $this->info("   - Creado: {$resetCode->created_at}");

        // 2. Verificar si ha expirado
        $now = Carbon::now();
        $expiresAt = Carbon::parse($resetCode->expires_at);
        
        if ($now->gt($expiresAt)) {
            $this->error("❌ Código HA EXPIRADO");
            $this->info("   - Ahora: {$now->format('Y-m-d H:i:s')}");
            $this->info("   - Expiraba: {$expiresAt->format('Y-m-d H:i:s')}");
            return 1;
        }

        $this->info("✅ Código NO ha expirado");

        // 3. Verificar si ya fue usado
        if ($resetCode->used) {
            $this->error("❌ Código YA FUE USADO");
            return 1;
        }

        $this->info("✅ Código NO ha sido usado");

        // 4. Probar el método findValidCode
        $validCode = PasswordResetCode::findValidCode($email, $code);
        
        if ($validCode) {
            $this->info("✅ findValidCode() encuentra el código correctamente");
        } else {
            $this->error("❌ findValidCode() NO encuentra el código");
            $this->info("🔍 Esto explica por qué falla la verificación");
        }

        // 5. Verificar la tabla
        $this->info("\n📊 Verificando estructura de la tabla:");
        $columns = \DB::select("SHOW COLUMNS FROM password_reset_codes LIKE 'code'");
        if (!empty($columns)) {
            $this->info("   - Campo 'code': " . $columns[0]->Type);
        }

        return 0;
    }
}
