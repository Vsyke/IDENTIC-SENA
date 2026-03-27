    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\RoleController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\PerfilController;
    use App\Http\Controllers\DocumentoTipoController;
    use App\Http\Controllers\Auth\RegistroEstudianteController;
    use App\Http\Controllers\AulaController;
    use App\Http\Controllers\FichaController;
    use Maatwebsite\Excel\Facades\Excel;
    use Illuminate\Http\Request;
    use App\Models\Asistencia;
    use App\Http\Controllers\DashboardController;

    /*
    |--------------------------------------------------------------------------
    | Registro de estudiantes (solo invitados)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function () {

        Route::get('/registro', [RegistroEstudianteController::class, 'index'])
            ->name('estudiantes.form');

        Route::post('/registro', [RegistroEstudianteController::class, 'store'])
            ->name('estudiantes.registro');
    });



    /*
    |--------------------------------------------------------------------------
    | Login (solo invitados)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function () {

        Route::get('/', function () {
            return view('autenticacion.login');
        });

        Route::get('/login', function () {
            return view('autenticacion.login');
        })->name('login');

        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });



    /*
    |--------------------------------------------------------------------------
    | Dashboard (según rol)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth')->group(function () {
        // La ruta llama al nuevo controlador. La lógica de rol está ahora dentro del controlador.
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */
        Route::post('/logout', function () {
            Auth::logout();
            return redirect('/login');
        })->name('logout');


        /*
        |--------------------------------------------------------------------------
        | CRUDs del sistema original
        |--------------------------------------------------------------------------
        */

        Route::get('/permisos/select', [RoleController::class, 'permisos'])->name('permisos.select');
        Route::get('/roles/select', [RoleController::class, 'roles'])->name('roles.select');
        Route::resource('roles', RoleController::class)->except(['create','edit']);

        Route::resource('usuarios', UserController::class)->except(['create','edit']);

        Route::get('/documento-tipos/select', [DocumentoTipoController::class, 'select'])->name('documento-tipos.select');
        Route::resource('documento-tipos', DocumentoTipoController::class)->except(['create','edit']);

        Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

        

        /*
        |--------------------------------------------------------------------------
        | Aulas (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['auth'])->group(function () {
        Route::resource('aulas', AulaController::class);
    });
        /*
    |--------------------------------------------------------------------------
    | Fichas (CRUD)
    |--------------------------------------------------------------------------
    */
    // 1. LA RUTA ESPECÍFICA SIEMPRE VA PRIMERO
    Route::get('fichas/select', [FichaController::class, 'select'])->name('fichas.select');

    // 2. EL RESOURCE VA DESPUÉS
    Route::resource('fichas', FichaController::class);
    });
    use App\Http\Controllers\AsistenciaController;

    Route::middleware('web')->group(function () {
        Route::get('/asistencias/qr', [AsistenciaController::class, 'vistaQR'])
            ->name('vigilantes.qr');

        Route::post('/asistencias/qr', [AsistenciaController::class, 'generarQR'])
            ->name('asistencias.qr.generar');

        Route::get('/asistencias/scan/{documento}', [AsistenciaController::class, 'scanQR'])
            ->name('asistencias.qr.scan');

        Route::post('/asistencia/escanear', [AsistenciaController::class, 'procesarEscaneo'])->name('asistencia.escanear');

        Route::get('/asistencias/personasQR', [AsistenciaController::class, 'personasQR'])->name('asistencias.personasQR');
    });
