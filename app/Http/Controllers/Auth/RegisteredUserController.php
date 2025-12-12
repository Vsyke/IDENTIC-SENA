<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar campos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Obtener rol por defecto: estudiante
        $defaultRole = Role::where('name', 'estudiante')->first();

        if (!$defaultRole) {
            abort(500, 'Rol por defecto "estudiante" no encontrado. Ejecuta el seeder de roles.');
        }

        // Crear usuario con role_id asignado automáticamente
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $defaultRole->id,
        ]);

        // Disparar evento Registered
        event(new Registered($user));

        // Loguear usuario automáticamente
        Auth::login($user);

        // Redirigir al dashboard correspondiente según rol
        return redirect($this->redirectToByRole($user));
    }

    /**
     * Redirigir usuario según su rol.
     */
    private function redirectToByRole(User $user): string
    {
        return match ($user->role->name) {
            'admin' => '/admin/dashboard',
            'estudiante' => '/student/dashboard',
            'invitado' => '/guest/info',
            default => '/',
        };
    }
}