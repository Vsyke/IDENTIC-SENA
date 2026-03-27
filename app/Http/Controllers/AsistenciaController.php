<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    /**
     * Muestra la vista con el historial de asistencias (opcional)
     */
    public function vistaQR()
    {
        $asistencias = Asistencia::with('user')->orderBy('fecha', 'desc')->get();
        return view('asistencias.qr', compact('asistencias'));
    }

    /**
     * LÓGICA PRINCIPAL: Recibe el UUID del QR y registra entrada/salida
     * vinculando la jornada oficial de la ficha del aprendiz.
     */
    public function procesarEscaneo(Request $request)
    {
        $token = $request->input('codigo_qr'); 

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Código QR vacío']);
        }

        // 1. Buscamos al estudiante, su usuario y la JORNADA de su ficha
        // Usamos un Join para traer todo de un solo golpe (más eficiente)
        $datosEstudiante = DB::table('users')
            ->join('estudiantes', 'users.id', '=', 'estudiantes.user_id')
            ->join('fichas', 'estudiantes.ficha_id', '=', 'fichas.id')
            ->where('users.qr_token', $token) // Buscamos por el nuevo Token UUID
            ->select(
                'users.id as user_id', 
                'users.name', 
                'fichas.jornada as jornada_oficial',
                'fichas.codigo as ficha_codigo'
            )
            ->first();

        if (!$datosEstudiante) {
            return response()->json([
                'success' => false, 
                'message' => 'Código no válido o aprendiz no registrado.'
            ]);
        }

        $hoy = Carbon::today()->toDateString();
        
        // 2. Verificar si ya existe un registro de este usuario hoy
        $asistencia = Asistencia::where('user_id', $datosEstudiante->user_id)
            ->whereDate('fecha', $hoy)
            ->first();

        // --- CASO A: REGISTRAR ENTRADA ---
        if (!$asistencia) {
            Asistencia::create([
                'user_id' => $datosEstudiante->user_id,
                'fecha'   => $hoy,
                'entrada' => now()->toTimeString(),
                'jornada' => $datosEstudiante->jornada_oficial, // <--- Aquí evitamos el cruce de jornadas
                'estado'  => 'Presente'
            ]);

            return response()->json([
                'success' => true, 
                'message' => "ENTRADA: {$datosEstudiante->name} | Ficha: {$datosEstudiante->ficha_codigo} | Jornada: {$datosEstudiante->jornada_oficial}"
            ]);
        }

        // --- CASO B: REGISTRAR SALIDA ---
        if ($asistencia->salida == null) {
            $asistencia->update([
                'salida' => now()->toTimeString()
            ]);

            return response()->json([
                'success' => true, 
                'message' => " SALIDA: {$datosEstudiante->name} registrada correctamente."
            ]);
        }

        // --- CASO C: YA MARCÓ TODO ---
        return response()->json([
            'success' => false, 
            'message' => " {$datosEstudiante->name} ya completó su jornada hoy."
        ]);
    }

    /**
     * Vista para el scanner del vigilante
     */
    public function personasQR()
    {
        return view('asistencias.personasQR');
    }
}