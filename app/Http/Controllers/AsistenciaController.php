<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    
    public function vistaQR()
    {
        $asistencias = Asistencia::with('user')->get();
        return view('asistencias.qr', compact('asistencias'));
    }
    /**
     * Este método es el que recibe el código desde el JS del escáner
     */
    public function registrarPorEscaneo(Request $request)
    {
        // 1. Recibir el código (que es el documento del estudiante)
        $documento = $request->input('codigo_qr');

        if (!$documento) {
            return response()->json(['success' => false, 'message' => 'Código QR vacío']);
        }

        // 2. Buscar al estudiante en la tabla 'estudiantes'
        $estudiante = DB::table('estudiantes')
            ->where('numero_documento', $documento)
            ->first();

        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado en la base de datos']);
        }

        // 3. Obtener el usuario
        $user = User::find($estudiante->user_id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'El estudiante no tiene un usuario vinculado']);
        }

        $hoy = Carbon::today();
        
        // 4. Buscar si ya tiene registro hoy
        $asistencia = Asistencia::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();

        // --- LÓGICA DE REGISTRO ---

        // CASO A: Es su primera vez hoy (REGISTRAR ENTRADA)
        if (!$asistencia) {
            Asistencia::create([
                'user_id' => $user->id,
                'fecha'   => $hoy,
                'entrada' => now(),
            ]);

            return response()->json([
                'success' => true, 
                'message' => '¡Entrada registrada correctamente!'
            ]);
        }

        // CASO B: Ya entró pero no ha salido (REGISTRAR SALIDA)
        if ($asistencia->entrada && !$asistencia->salida) {
            $asistencia->update([
                'salida' => now()
            ]);

            return response()->json([
                'success' => true, 
                'message' => '¡Salida registrada correctamente!'
            ]);
        }

        // CASO C: Ya tiene entrada y salida hoy
        return response()->json([
            'success' => false, 
            'message' => 'Ya has completado tu asistencia de hoy.'
        ]);
    }
    public function personasQR()
    {
        return view('asistencias.personasQR');
    }
}