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
        return view('asistencias.qr');
    }

    public function generarQR(Request $request)
    {
        $request->validate([
            'documento' => 'required'
        ]);

        // 🔥 El documento está en la tabla estudiantes
        $estudiante = DB::table('estudiantes')
            ->where('numero_documento', $request->documento)
            ->first();

        if (!$estudiante) {
            return back()->with('error', 'Documento no encontrado');
        }

        // Relación estudiante → usuario
        $user = User::find($estudiante->user_id);

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        return view('asistencias.qr', [
            'documento' => $request->documento
        ]);
    }

    public function scanQR($documento)
    {
        $estudiante = DB::table('estudiantes')
            ->where('numero_documento', $documento)
            ->first();

        if (!$estudiante) {
            return response('QR inválido', 404);
        }

        $user = User::find($estudiante->user_id);

        if (!$user) {
            return response('Usuario no válido', 404);
        }

        $hoy = Carbon::today();

        $asistencia = Asistencia::where('user_id', $user->id)
            ->whereDate('fecha', $hoy)
            ->first();

        // Entrada
        if (!$asistencia) {
            Asistencia::create([
                'user_id' => $user->id,
                'fecha'   => $hoy,
                'entrada' => now(),
            ]);

            return view('asistencias.resultado', [
                'mensaje' => 'Entrada registrada correctamente'
            ]);
        }

        // Salida
        if ($asistencia->entrada && !$asistencia->salida) {
            $asistencia->update([
                'salida' => now()
            ]);

            return view('asistencias.resultado', [
                'mensaje' => 'Salida registrada correctamente'
            ]);
        }

        return view('asistencias.resultado', [
            'mensaje' => 'La asistencia de hoy ya fue completada'
        ]);
    }
}
