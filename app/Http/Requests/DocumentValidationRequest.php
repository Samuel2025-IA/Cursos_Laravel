<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Validar documento colombiano según su tipo
     */
    public function validateColombianDocument(string $tipo, string $numero): bool
    {
        // Remover espacios y guiones
        $numero = preg_replace('/[\s\-]/', '', $numero);
        
        switch ($tipo) {
            case 'CC': // Cédula de Ciudadanía
                return $this->validateCedula($numero);
                
            case 'CE': // Cédula de Extranjería
                return $this->validateCedulaExtranjeria($numero);
                
            case 'TI': // Tarjeta de Identidad
                return $this->validateTarjetaIdentidad($numero);
                
            case 'PP': // Pasaporte
                return $this->validatePasaporte($numero);
                
            case 'NIT': // Número de Identificación Tributaria
                return $this->validateNIT($numero);
                
            default:
                return false;
        }
    }

    /**
     * Validar Cédula de Ciudadanía colombiana
     */
    private function validateCedula(string $numero): bool
    {
        // Debe tener entre 7 y 10 dígitos (más flexible)
        if (!preg_match('/^\d{7,10}$/', $numero)) {
            return false;
        }

        // Para cédulas colombianas, solo validar formato numérico
        // No se requiere validación de dígito de verificación
        return true;
    }

    /**
     * Validar Cédula de Extranjería
     */
    private function validateCedulaExtranjeria(string $numero): bool
    {
        // Debe tener entre 7 y 12 dígitos
        return preg_match('/^\d{7,12}$/', $numero);
    }

    /**
     * Validar Tarjeta de Identidad
     */
    private function validateTarjetaIdentidad(string $numero): bool
    {
        // Debe tener entre 6 y 10 dígitos (más flexible)
        return preg_match('/^\d{6,10}$/', $numero);
    }

    /**
     * Validar Pasaporte
     */
    private function validatePasaporte(string $numero): bool
    {
        // Debe tener entre 6 y 9 caracteres
        if (strlen($numero) < 6 || strlen($numero) > 9) {
            return false;
        }
        
        // Contar letras y números
        $letras = preg_match_all('/[A-Z]/i', $numero);
        $numeros = preg_match_all('/[0-9]/', $numero);
        
        // Máximo 3 letras
        if ($letras > 3) {
            return false;
        }
        
        // Debe tener al menos un número
        if ($numeros == 0) {
            return false;
        }
        
        // Solo se permiten letras y números
        return preg_match('/^[A-Z0-9]+$/i', $numero);
    }

    /**
     * Validar NIT
     */
    private function validateNIT(string $numero): bool
    {
        // Debe tener entre 9 y 10 dígitos
        if (!preg_match('/^\d{9,10}$/', $numero)) {
            return false;
        }

        // Validar dígito de verificación para NIT de 10 dígitos
        if (strlen($numero) == 10) {
            $sum = 0;
            $weights = [41, 37, 29, 23, 19, 17, 13, 7, 3];
            
            for ($i = 0; $i < 9; $i++) {
                $sum += intval($numero[$i]) * $weights[$i];
            }
            
            $remainder = $sum % 11;
            $checkDigit = $remainder > 1 ? 11 - $remainder : 0;
            
            return $checkDigit == intval($numero[9]);
        }
        
        return true; // NIT de 9 dígitos (sin dígito de verificación)
    }
}
