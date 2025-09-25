<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Show the invitation code verification form
     */
    public function show()
    {
        // Si el usuario ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.verify-invitation');
    }

    /**
     * Verify the invitation code
     */
    public function verify(Request $request)
    {
        // Validación básica
        if (empty($request->code)) {
            return back()->with('error', 'El código de invitación es requerido.');
        }

        if (strlen($request->code) !== 8) {
            return back()->with('error', 'El código debe tener exactamente 8 caracteres.');
        }

        $code = strtoupper($request->code);
        $invitationCode = InvitationCode::where('code', $code)->first();

        if (!$invitationCode) {
            return back()->with('error', 'El código de invitación no es válido.');
        }

        if (!$invitationCode->isValid()) {
            if ($invitationCode->used) {
                return back()->with('error', 'Este código de invitación ya ha sido utilizado.');
            } else {
                return back()->with('error', 'Este código de invitación ha expirado.');
            }
        }

        // Store the verified email in session for registration
        session([
            'invitation_email' => $invitationCode->email,
            'invitation_code_id' => $invitationCode->id,
            'invitation_verified_at' => now()->toDateTimeString(),
            'invitation_status' => 'verified'
        ]);

        return redirect()->route('register')->with('info', 'Código verificado correctamente. Al continuar con el registro, aceptas que recopilaremos y procesaremos tus datos personales de acuerdo con nuestra política de privacidad.');
    }
}
