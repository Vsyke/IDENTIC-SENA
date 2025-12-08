<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller // o el nombre de tu controlador
{
    public function index()
    {
        // 1. Definir los roles que deseas medir
        $roles = ['estudiante', 'seguridad', 'instructor', 'visitante'];
        $datosAsistencia = [];

        foreach ($roles as $rol) {
            // A. Obtener el total de usuarios para el rol actual
            $totalRol = User::role($rol)->count();

            // B. Obtener las asistencias registradas hoy para ese rol
            // ¡Asegúrate de que tienes una relación 'user' en el modelo Asistencia!
            $asistieronHoy = Asistencia::whereDate('fecha', now())
                ->whereNotNull('entrada')
                ->whereHas('user', function ($query) use ($rol) {
                    $query->role($rol);
                })
                ->count();

            // C. Calcular el porcentaje
            $porcentaje = $totalRol > 0
                ? round(($asistieronHoy / $totalRol) * 100)
                : 0;

            // D. Almacenar los datos para la vista
            $datosAsistencia[$rol] = [
                'total' => $totalRol,
                'asistieron' => $asistieronHoy,
                'porcentaje' => $porcentaje,
            ];
        }

        // 2. Pasar la variable $datosAsistencia a la vista 'dashboard.index'
        return view('dashboard.index', [
            'datosAsistencia' => $datosAsistencia,
            // ... otras variables que necesites pasar
        ]);
    }
}