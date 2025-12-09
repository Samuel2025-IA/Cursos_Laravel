<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show(string $path)
    {
        // Permite rutas anidadas en 'public' disk
        $path = trim($path, '/');
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        // Intentar fallback absoluto
        $full = public_path('storage/' . $path);
        if (file_exists($full)) {
            return response()->file($full);
        }

        abort(404);
    }
}


