<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use App\Models\Equipo; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importante para las consultas directas

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // --- 1. LÓGICA ESPECÍFICA PARA ESTUDIANTES ---
        if ($user->hasRole('estudiante')) {
    $estudiante = DB::table('estudiantes')
        ->join('fichas', 'estudiantes.ficha_id', '=', 'fichas.id')
        ->where('estudiantes.user_id', $user->id)
        ->select('fichas.codigo', 'fichas.programa') // Traemos código (2670687) y programa (ADSO)
        ->first();

    $ficha = $estudiante ? $estudiante->codigo . " - " . $estudiante->programa : "Ficha no asignada";

    return view('estudiantes.dashboard', compact('ficha'));
}

        // --- 2. CÁLCULO DE MÉTRICAS GLOBALES (ADMIN/VIGILANTE) ---
        
        // Total de Personas (Todos los usuarios)
        $totalPersonas = User::count(); 

        // Presentes Hoy (Usuarios únicos con ENTRADA registrada hoy)
        $presentesHoy = Asistencia::whereDate('fecha', now())
            ->whereNotNull('entrada')
            ->distinct('user_id') 
            ->count('user_id');

        // Ausentes Hoy
        $ausentesHoy = $totalPersonas - $presentesHoy;

        // --- RANGOS DE TIEMPO (Configuración) ---
        $periodos = [
            'Mañana' => ['inicio' => '06:00:00', 'fin' => '13:00:00', 'cruce' => false],
            'Tarde' => ['inicio' => '12:00:00', 'fin' => '18:00:00', 'cruce' => false],
            'Noche' => ['inicio' => '18:00:00', 'fin' => '22:00:00', 'cruce' => false],
            'Madrugada' => ['inicio' => '22:00:00', 'fin' => '06:00:00', 'cruce' => true],
        ];

        $periodoSolicitado = $request->input('periodo', 'Mañana');
        $rango = $periodos[$periodoSolicitado] ?? $periodos['Mañana']; 

        // --- 3. CÁLCULO DE ASISTENCIA POR ROL FILTRADA POR PERÍODO ---
        $roles = ['estudiante', 'vigilante', 'maestro', 'invitado'];
        $datosAsistencia = [];
        $fechaHoy = now()->toDateString();
        $fechaManana = now()->addDay()->toDateString();

        foreach ($roles as $rol) {
            $totalRol = User::role($rol)->count();

            $queryAsistencias = Asistencia::whereNotNull('entrada')
                ->whereHas('user', function ($query) use ($rol) {
                    $query->role($rol);
                });

            if ($rango['cruce'] === true) {
                $queryAsistencias->where(function ($query) use ($rango, $fechaHoy, $fechaManana) {
                    $query->whereBetween('entrada', [
                        $fechaHoy . ' ' . $rango['inicio'],
                        $fechaHoy . ' 23:59:59'
                    ])
                    ->orWhereBetween('entrada', [
                        $fechaManana . ' 00:00:00',
                        $fechaManana . ' ' . $rango['fin']
                    ]);
                });
            } else {
                $queryAsistencias->whereBetween('entrada', [
                    $fechaHoy . ' ' . $rango['inicio'],
                    $fechaHoy . ' ' . $rango['fin']
                ]);
            }

            $asistieronHoy = $queryAsistencias->count();
            $porcentaje = $totalRol > 0 ? round(($asistieronHoy / $totalRol) * 100) : 0;
            $datosAsistencia[$rol] = compact('totalRol', 'asistieronHoy', 'porcentaje');
        }

        // --- 4. REGISTROS RECIENTES Y TOTALES ---
        $registrosRecientes = Asistencia::with('user')
            ->whereDate('fecha', now())
            ->whereNotNull('entrada')
            ->orderBy('entrada', 'desc')
            ->limit(10)
            ->get();

        $registrosTotales = Asistencia::with('user')
            ->whereDate('fecha', now())
            ->orderBy('entrada', 'asc')
            ->get();

        $equiposRegistrados = Equipo::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Vista para el Administrador/Vigilante
        return view('dashboard.index', compact(
            'totalPersonas', 
            'presentesHoy', 
            'ausentesHoy', 
            'datosAsistencia', 
            'registrosRecientes', 
            'registrosTotales', 
            'periodoSolicitado', 
            'equiposRegistrados'
        ));
    }
}