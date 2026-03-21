<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
     public function __construct(){
        $this->middleware('can:users_list')->only(['index']);
        $this->middleware('can:users_create')->only(['store']);
        $this->middleware('can:users_edit')->only(['show', 'update']);
        $this->middleware('can:users_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('id', 'name', 'email','activo');

            return DataTables::of($data)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->map(function ($role) {
                        return '<span class="badge bg-primary">' . $role . '</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($user) {
                    $editButton ='';                    
                    if(auth()->user()->can('users_edit')){
                        $editButton = view('components.button-edit', ['id' => $user->id])->render();
                    }
                    $deleteButton = '';
                    if(auth()->user()->can('users_delete')){
                        $deleteButton = view('components.button-delete', ['id' => $user->id])->render();
                    }
                    return $editButton . $deleteButton;
                })
                ->addColumn('activo', function ($user) {
                    return $user->activo
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-danger">Inactivo</span>';
                })
                ->rawColumns(['roles', 'action', 'activo'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['password'] = Hash::make($data['password']);

        return DB::transaction(function () use ($request) {
        // 1. Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activo' => $request->activo,
        ]);

        // 2. Asignar roles (Spatie)
        $user->assignRole($request->roles);

        // 3. ¡ESTA ES LA PARTE CLAVE!
        // Si el rol "estudiante" fue seleccionado, guardamos en la tabla estudiantes
        if (in_array('estudiante', $request->roles)) {
            DB::table('estudiantes')->insert([
                'user_id' => $user->id,
                'ficha_id' => $request->ficha_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Usuario creado con éxito']);
    });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $registro = User::with('roles')->findOrFail($id);
            return response()->json($registro);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    return DB::transaction(function () use ($request, $user) {
        // 1. Actualizar datos básicos
        $user->update($request->only('name', 'email', 'activo'));
        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // 2. Sincronizar roles
        $user->syncRoles($request->roles);

        // 3. Manejar la ficha del estudiante
        if (in_array('estudiante', $request->roles)) {
            // updateOrInsert busca si existe por user_id, si no, lo crea
            DB::table('estudiantes')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'ficha_id' => $request->ficha_id,
                    'updated_at' => now()
                ]
            );
        } else {
            // Si le quitamos el rol de estudiante, opcionalmente borramos su registro de ficha
            DB::table('estudiantes')->where('user_id', $user->id)->delete();
        }

        return response()->json(['message' => 'Usuario actualizado con éxito']);
    });
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el registro'
            ], 500);
        }
    }

    protected function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'password' => $id ? 'nullable|min:6' : 'required|min:6',
            'activo' => 'required|boolean'
        ]);
    }
}
