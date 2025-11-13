<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractPhoneNumbersFromImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:phones-from-image {image : Ruta de la imagen}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extrae números de teléfono de una imagen usando OCR';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $imagePath = $this->argument('image');
        
        // Verificar si la imagen existe
        if (!File::exists($imagePath)) {
            $this->error("❌ La imagen no existe en la ruta: {$imagePath}");
            return 1;
        }

        $this->info("📷 Procesando imagen: {$imagePath}");
        
        // Verificar si Tesseract está instalado
        $tesseractPath = $this->findTesseract();
        
        if (!$tesseractPath) {
            $this->error("❌ Tesseract OCR no está instalado.");
            $this->warn("Por favor instala Tesseract OCR:");
            $this->line("  Ubuntu/Debian: sudo apt-get install tesseract-ocr tesseract-ocr-spa");
            $this->line("  macOS: brew install tesseract");
            $this->line("  Windows: Descarga desde https://github.com/UB-Mannheim/tesseract/wiki");
            return 1;
        }

        $this->info("✅ Tesseract encontrado en: {$tesseractPath}");
        
        // Ejecutar OCR
        $this->info("🔍 Extrayendo texto de la imagen...");
        
        $command = escapeshellarg($tesseractPath) . ' ' . escapeshellarg($imagePath) . ' stdout -l spa+eng 2>&1';
        $output = shell_exec($command);
        
        if (empty($output)) {
            $this->error("❌ No se pudo extraer texto de la imagen.");
            return 1;
        }

        $this->info("📝 Texto extraído:");
        $this->line("---");
        $this->line($output);
        $this->line("---");
        
        // Extraer números de teléfono
        $phoneNumbers = $this->extractPhoneNumbers($output);
        
        if (empty($phoneNumbers)) {
            $this->warn("⚠️  No se encontraron números de teléfono en la imagen.");
        } else {
            $this->info("📞 Números de teléfono encontrados:");
            foreach ($phoneNumbers as $index => $phone) {
                $this->line("  " . ($index + 1) . ". " . $phone);
            }
        }
        
        return 0;
    }

    /**
     * Busca la ruta de Tesseract OCR
     */
    private function findTesseract(): ?string
    {
        $possiblePaths = [
            '/usr/bin/tesseract',
            '/usr/local/bin/tesseract',
            '/opt/homebrew/bin/tesseract',
            'tesseract', // Si está en PATH
        ];

        foreach ($possiblePaths as $path) {
            if ($path === 'tesseract') {
                // Verificar si está en PATH
                $result = shell_exec('which tesseract 2>/dev/null');
                if (!empty($result)) {
                    return trim($result);
                }
            } elseif (File::exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Extrae números de teléfono del texto usando expresiones regulares
     */
    private function extractPhoneNumbers(string $text): array
    {
        $phones = [];
        
        // Patrones comunes para números de teléfono colombianos y otros formatos
        $patterns = [
            // Formato colombiano: 3XX XXX XXXX o 3XX-XXX-XXXX
            '/\b3\d{2}[\s.-]?\d{3}[\s.-]?\d{4}\b/',
            // Formato con código de país: +57 3XX XXX XXXX
            '/\+57[\s.-]?3\d{2}[\s.-]?\d{3}[\s.-]?\d{4}\b/',
            // Formato genérico: XXX-XXX-XXXX o XXX.XXX.XXXX
            '/\b\d{3}[\s.-]?\d{3}[\s.-]?\d{4}\b/',
            // Formato con espacios: XXX XXX XXXX
            '/\b\d{3}\s+\d{3}\s+\d{4}\b/',
            // Formato sin separadores: 10 dígitos seguidos
            '/\b\d{10}\b/',
        ];

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $text, $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $match) {
                    // Limpiar y normalizar el número
                    $cleaned = preg_replace('/[\s.-]/', '', $match);
                    // Solo agregar si tiene 10 dígitos (formato colombiano) o más
                    if (strlen($cleaned) >= 10 && strlen($cleaned) <= 13) {
                        // Formatear como 3XX XXX XXXX
                        if (strlen($cleaned) === 10 && preg_match('/^3\d{9}$/', $cleaned)) {
                            $formatted = substr($cleaned, 0, 3) . ' ' . substr($cleaned, 3, 3) . ' ' . substr($cleaned, 6);
                            if (!in_array($formatted, $phones)) {
                                $phones[] = $formatted;
                            }
                        } elseif (!in_array($match, $phones)) {
                            $phones[] = trim($match);
                        }
                    }
                }
            }
        }

        return array_unique($phones);
    }
}
