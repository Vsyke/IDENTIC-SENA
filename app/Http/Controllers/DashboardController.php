<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use App\Models\Equipo; // El modelo Equipo ya está siendo utilizado
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // --- CÁLCULO DE MÉTRICAS GLOBALES (INFO BOXES) ---
        
        // 1. Total de Personas (Todos los usuarios)
        $totalPersonas = User::count(); 

        // 2. Presentes Hoy (Usuarios únicos con ENTRADA registrada hoy)
        $presentesHoy = Asistencia::whereDate('fecha', now())
            ->whereNotNull('entrada')
            ->distinct('user_id') // Contamos solo usuarios únicos
            ->count('user_id');

        // 3. Ausentes Hoy
        $ausentesHoy = $totalPersonas - $presentesHoy;

        // --- RANGOS DE TIEMPO (Configuración) ---
        // Manteniendo la configuración de periodos tal cual
        $periodos = [
            'Mañana' => ['inicio' => '06:00:00', 'fin' => '13:00:00', 'cruce' => false],
            'Tarde' => ['inicio' => '12:00:00', 'fin' => '18:00:00', 'cruce' => false],
            'Noche' => ['inicio' => '18:00:00', 'fin' => '22:00:00', 'cruce' => false],
            // La madrugada cruza el día
            'Madrugada' => ['inicio' => '22:00:00', 'fin' => '06:00:00', 'cruce' => true],
        ];

        // --- DETECCIÓN DEL PERÍODO ACTUAL O SOLICITADO ---
        $periodoSolicitado = $request->input('periodo', 'Mañana');
        $rango = $periodos[$periodoSolicitado] ?? $periodos['Mañana']; 

        // --- 1. CÁLCULO DE ASISTENCIA POR ROL FILTRADA POR PERÍODO ---
        $roles = ['estudiante', 'vigilante', 'maestro', 'invitado'];
        $datosAsistencia = [];
        $fechaHoy = now()->toDateString();
        $fechaManana = now()->addDay()->toDateString(); // Necesario para Madrugada

        foreach ($roles as $rol) {
            $totalRol = User::role($rol)->count();

            // Construir la consulta base para el conteo de asistencias del día
            $queryAsistencias = Asistencia::whereNotNull('entrada')
                ->whereHas('user', function ($query) use ($rol) {
                    $query->role($rol);
                });

            // Aplicar el filtro de rango de tiempo
            if ($rango['cruce'] === true) {
                // Lógica para Madrugada: Hoy (22:00 a 23:59:59) O Mañana (00:00:00 a 06:00:00)
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
                // Lógica para rangos normales (Mañana, Tarde, Noche): Solo el día de hoy
                $queryAsistencias->whereBetween('entrada', [
                    $fechaHoy . ' ' . $rango['inicio'],
                    $fechaHoy . ' ' . $rango['fin']
                ]);
            }

            $asistieronHoy = $queryAsistencias->count();

            $porcentaje = $totalRol > 0 ? round(($asistieronHoy / $totalRol) * 100) : 0;
            $datosAsistencia[$rol] = compact('totalRol', 'asistieronHoy', 'porcentaje');
        }

        // --- 2. CÁLCULO DE REGISTROS RECIENTES (sin filtro de período) ---
        $registrosRecientes = Asistencia::with('user')
            ->whereDate('fecha', now())
            ->whereNotNull('entrada')
            ->orderBy('entrada', 'desc')
            ->limit(10)
            ->get();

        // --- 3. CÁLCULO DE REGISTROS TOTALES (sin filtro de período) ---
        $registrosTotales = Asistencia::with('user')
            ->whereDate('fecha', now())
            ->orderBy('entrada', 'asc')
            ->get();

        // --- 4. CÁLCULO DE EQUIPOS (COMPUTADORES) REGISTRADOS ---
        $equiposRegistrados = Equipo::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // --- 5. LÓGICA DE REDIRECCIÓN Y VISTA FINAL (Bloque único) ---
        if ($user->hasRole('estudiante')) {
            return view('estudiantes.dashboard');
        }

        // Se pasan TODAS las variables, incluyendo las nuevas métricas globales.
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