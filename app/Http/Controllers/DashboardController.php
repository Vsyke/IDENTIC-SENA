<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia; // ⬅️ ¡IMPORTAR MODELO ASISTENCIA!
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    
    // --- 1. CÁLCULO DE ASISTENCIA POR ROL ---
    // Esta lógica DEBE permanecer dentro del bucle.
    $roles = ['estudiante', 'vigilante', 'maestro', 'invitado']; 
    $datosAsistencia = [];

    foreach ($roles as $rol) {
        $totalRol = User::role($rol)->count();

        $asistieronHoy = Asistencia::whereDate('fecha', now())
            ->whereNotNull('entrada')
            ->whereHas('user', function ($query) use ($rol) {
                $query->role($rol);
            })
            ->count();

        $porcentaje = $totalRol > 0 ? round(($asistieronHoy / $totalRol) * 100) : 0;

        $datosAsistencia[$rol] = compact('totalRol', 'asistieronHoy', 'porcentaje');
    }
    
    // --- 2. CÁLCULO DE REGISTROS RECIENTES ---
    // ESTO DEBE IR FUERA DEL BUCLE, se calcula solo una vez.
    $registrosRecientes = Asistencia::with('user')
        ->whereDate('fecha', now())
        ->whereNotNull('entrada')
        ->orderBy('entrada', 'desc')
        ->limit(10)
        ->get();
    
    // --- 3. LÓGICA DE REDIRECCIÓN Y VISTA FINAL ---
    // El return view DEBE ir al final de la función para devolver todos los datos.

    if ($user->hasRole('estudiante')) {
        // Redirige al dashboard de estudiantes.
        return view('estudiantes.dashboard');
    }

    // Devuelve el dashboard principal con AMBOS conjuntos de datos.
    return view('dashboard.index', compact('datosAsistencia', 'registrosRecientes'));
}
}