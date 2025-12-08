<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Credenciales
        $credentials = $request->only('email', 'password');

        // Intentar login
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // Verificar si está activo
            if (!$user->activo) {
                Auth::logout();
                return back()->with('error', 'La cuenta está inactiva.');
            }

            // LISTO: al dashboard (redirección automática por rol)
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }
}
