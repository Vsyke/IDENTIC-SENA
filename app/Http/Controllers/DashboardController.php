<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // --- 1. LÓGICA PARA ESTUDIANTES ---
        if ($user->hasRole('estudiante')) {
            $estudiante = DB::table('estudiantes')
                ->join('fichas', 'estudiantes.ficha_id', '=', 'fichas.id')
                ->where('estudiantes.user_id', $user->id)
                ->select('fichas.codigo', 'fichas.programa', 'fichas.jornada')
                ->first();

            $ficha = $estudiante ? $estudiante->codigo . " - " . $estudiante->programa : "Ficha no asignada";
            $jornada = $estudiante ? $estudiante->jornada : "N/A";

            return view('estudiantes.dashboard', compact('ficha', 'user', 'jornada'));
        }

        // --- 2. LÓGICA PARA VIGILANTES ---
        if ($user->hasRole('vigilante')) {
            return view('vigilantes.dashboard', compact('user'));
        }

        // --- 3. LÓGICA PARA ADMIN (Métricas Globales) ---
        if ($user->hasRole('admin')) {
            $totalPersonas = User::count();

            // Conteo de aprendices por jornada según su FICHA
$reporteJornadas = DB::table('fichas')
    ->leftJoin('estudiantes', 'fichas.id', '=', 'estudiantes.ficha_id')
    ->leftJoin('asistencias', function($join) {
        $join->on('estudiantes.user_id', '=', 'asistencias.user_id')
             ->whereDate('asistencias.fecha', now()->toDateString());
    })
    ->select('fichas.jornada', 
             DB::raw('count(distinct estudiantes.id) as total_estudiantes'),
             DB::raw('count(distinct asistencias.user_id) as presentes_hoy'))
    ->groupBy('fichas.jornada')
    ->get();

            // Presentes Hoy
            $presentesHoy = Asistencia::whereDate('fecha', now())
                ->whereNotNull('entrada')
                ->distinct('user_id')
                ->count('user_id');

            $ausentesHoy = $totalPersonas - $presentesHoy;

            // --- Configuración de Períodos para Filtros ---
            $periodos = [
                'Mañana' => ['inicio' => '06:00:00', 'fin' => '13:00:00', 'cruce' => false],
                'Tarde' => ['inicio' => '12:00:00', 'fin' => '18:00:00', 'cruce' => false],
                'Noche' => ['inicio' => '18:00:00', 'fin' => '22:00:00', 'cruce' => false],
                'Madrugada' => ['inicio' => '22:00:00', 'fin' => '06:00:00', 'cruce' => true],
            ];

            $periodoSolicitado = $request->input('periodo', 'Mañana');
            $rango = $periodos[$periodoSolicitado] ?? $periodos['Mañana'];

            // Cálculo por Roles
            $roles = ['estudiante', 'vigilante', 'maestro', 'invitado'];
            $datosAsistencia = [];
            $fechaHoy = now()->toDateString();
            $fechaManana = now()->addDay()->toDateString();

            foreach ($roles as $rol) {
    // 1. Contar total de usuarios con este rol (usando Spatie o tu sistema de roles)
    $totalRol = User::role($rol)->count();

    // 2. Usar la query que ya tiene los filtros de HORARIO (Mañana, Tarde, etc.)
    $queryAsistencias = Asistencia::whereNotNull('entrada')
        ->whereHas('user', function ($query) use ($rol) {
            $query->role($rol);
        });

    // Aplicar los rangos de tiempo (esta parte ya la tenías bien)
    if ($rango['cruce']) {
        $queryAsistencias->where(function ($q) use ($rango, $fechaHoy, $fechaManana) {
            $q->whereBetween('entrada', [$fechaHoy . ' ' . $rango['inicio'], $fechaHoy . ' 23:59:59'])
              ->orWhereBetween('entrada', [$fechaManana . ' 00:00:00', $fechaManana . ' ' . $rango['fin']]);
        });
    } else {
        $queryAsistencias->whereBetween('entrada', [
            $fechaHoy . ' ' . $rango['inicio'],
            $fechaHoy . ' ' . $rango['fin']
        ]);
    }

    // 3. EJECUTAR EL CONTEO ÚNICO SOBRE LA QUERY FILTRADA
    // Usamos el 'user_id' que es el estándar de tu tabla
    $asistieronHoy = $queryAsistencias->distinct('user_id')->count('user_id');

    // 4. Calcular porcentaje
    $porcentaje = $totalRol > 0 ? round(($asistieronHoy / $totalRol) * 100) : 0;

    $datosAsistencia[$rol] = [
        'totalRol' => $totalRol,
        'asistieronHoy' => $asistieronHoy,
        'porcentaje' => $porcentaje
    ];
}

            // Registros para las tablas
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

            return view('dashboard.index', compact(
                'totalPersonas', 'presentesHoy', 'ausentesHoy', 'datosAsistencia',
                'registrosRecientes', 'registrosTotales', 'periodoSolicitado', 'equiposRegistrados', 'reporteJornadas'
            ));
        }

        return abort(403, 'No tienes un rol asignado.');
    }
}