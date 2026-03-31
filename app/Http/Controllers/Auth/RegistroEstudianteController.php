<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistroEstudianteController extends Controller
{
    public function index()
    {
        return view('autenticacion.registro');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento'   => 'required|string',
            'numero_documento' => 'required|unique:estudiantes,numero_documento',
            'primer_nombre'    => 'required|string',
            'primer_apellido'  => 'required|string',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:6|confirmed'
        ]);

        DB::beginTransaction();
        try {

            $user = User::create([
                'name'     => $request->primer_nombre . ' ' . $request->primer_apellido,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'activo'   => true,
                'rol_id'   => 2, // ID del rol "estudiante"
            ]);

            $user->assignRole('estudiante');

            Estudiante::create([
                'user_id'          => $user->id,
                'tipo_documento'   => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'primer_nombre'    => $request->primer_nombre,
                'segundo_nombre'   => $request->segundo_nombre,
                'primer_apellido'  => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'email'            => $request->email,
                'telefono'         => $request->telefono,
            ]);

            DB::commit();

            return redirect('/login')->with('success', 'Cuenta creada correctamente.');

            } catch (\Exception $e) {
    DB::rollBack();
    // Temporal para debug - quitar después
    dd([
        'mensaje' => $e->getMessage(),
        'archivo' => $e->getFile(),
        'linea'   => $e->getLine(),
        'request' => $request->all(),
    ]);
}
        }
    }

