<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EspecialistaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CitaCancelacionController; // 🆕
use App\Http\Controllers\ListaEsperaController;
use App\Http\Middleware\SoloAdmin;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (auth()->check()) {
        $role = auth()->user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'especialista') {
            return redirect()->route('especialista.dashboard');
        }

        return redirect()->route('cliente.dashboard');
    }

    $galeria = \App\Models\Galeria::with('servicio')->get();
    $servicios = \App\Models\Servicio::where('activo', true)->get();

    return view('welcome', compact('galeria', 'servicios'));
});

/*
|--------------------------------------------------------------------------
| 🔥 RUTA PÚBLICA (SIN LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/galeria', function () {
    $imagenes = \App\Models\Galeria::with('servicio')->get();
    $servicios = \App\Models\Servicio::whereHas('galeria')->get();

    return view('galeria', compact('imagenes', 'servicios'));
})->name('galeria.publica');

/*
|--------------------------------------------------------------------------
| 🆕 CANCELAR CITA DESDE EL CORREO (enlace firmado, sin login)
|--------------------------------------------------------------------------
*/
Route::get('/citas/{cita}/cancelar-correo', [CitaCancelacionController::class, 'confirmar'])
    ->name('citas.cancelar.firmada')
    ->middleware('signed');

Route::post('/citas/{cita}/cancelar-correo', [CitaCancelacionController::class, 'cancelar'])
    ->middleware('signed');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (CON LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Ruta genérica que usan las pantallas de Breeze (verificar correo, confirmar contraseña):
    // envía a cada usuario a su panel según su rol.
    Route::get('/dashboard', fn () => redirect('/'))->name('dashboard');

    // =========================
    // Rutas del cliente
    // =========================
    Route::get('/cliente/dashboard', [ClienteController::class, 'dashboard'])->name('cliente.dashboard');

    Route::get('/cliente/agendar', [CitaController::class, 'create'])->name('citas.create');
    Route::post('/cliente/agendar', [CitaController::class, 'store'])->name('citas.store');

    Route::get('/cliente/mis-citas', [CitaController::class, 'misCitas'])->name('citas.mias');

    Route::get('/cliente/citas/{cita}/reagendar', [CitaController::class, 'edit'])->name('citas.edit');
    Route::put('/cliente/citas/{cita}/reagendar', [CitaController::class, 'update'])->name('citas.update');

    Route::patch('/cliente/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');

    Route::get('/api/horas-ocupadas', [CitaController::class, 'horasOcupadas'])->name('horas.ocupadas');

    // ⏳ Lista de espera inteligente
    Route::get('/cliente/lista-espera', [ListaEsperaController::class, 'index'])->name('lista-espera.index');
    Route::post('/cliente/lista-espera', [ListaEsperaController::class, 'store'])->name('lista-espera.store');
    Route::delete('/cliente/lista-espera/{listaEspera}', [ListaEsperaController::class, 'destroy'])->name('lista-espera.destroy');
    Route::get('/cupo/{listaEspera}', [ListaEsperaController::class, 'cupo'])->name('lista-espera.cupo');


    // =========================
    // Rutas del especialista
    // =========================
    Route::get('/especialista/dashboard', [EspecialistaController::class, 'dashboard'])->name('especialista.dashboard');

    Route::put('/citas/{cita}/completar', [CitaController::class, 'completar'])->name('citas.completar');


    // =========================
    // Rutas del admin (SOLO ADMINISTRADOR)
    // =========================
    Route::middleware([SoloAdmin::class])->group(function () {

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/admin/servicios', [AdminController::class, 'servicios'])->name('admin.servicios');
        Route::post('/admin/servicios', [AdminController::class, 'storeServicio'])->name('admin.servicios.store');
        Route::put('/admin/servicios/{servicio}', [AdminController::class, 'updateServicio'])->name('admin.servicios.update');
        Route::delete('/admin/servicios/{servicio}', [AdminController::class, 'destroyServicio'])->name('admin.servicios.destroy');

        Route::get('/admin/especialistas', [AdminController::class, 'especialistas'])->name('admin.especialistas');
        Route::post('/admin/especialistas', [AdminController::class, 'storeEspecialista'])->name('admin.especialistas.store');
        Route::put('/admin/especialistas/{especialista}', [AdminController::class, 'updateEspecialista'])->name('admin.especialistas.update');
        Route::delete('/admin/especialistas/{especialista}', [AdminController::class, 'destroyEspecialista'])->name('admin.especialistas.destroy');

        Route::get('/admin/galeria', [AdminController::class, 'galeria'])->name('admin.galeria');
        Route::post('/admin/galeria', [AdminController::class, 'storeGaleria'])->name('admin.galeria.store');
        Route::put('/admin/galeria/{galeria}', [AdminController::class, 'updateGaleria'])->name('admin.galeria.update');
        Route::delete('/admin/galeria/{galeria}', [AdminController::class, 'destroyGaleria'])->name('admin.galeria.destroy');

        Route::get('/admin/citas', [AdminController::class, 'citas'])->name('admin.citas');
        Route::patch('/admin/citas/{cita}/estado', [AdminController::class, 'updateEstadoCita'])->name('admin.citas.estado');
        Route::delete('/admin/citas/{cita}', [AdminController::class, 'destroyCita'])->name('admin.citas.destroy');

        // 🆕 Clientes (listado para la administradora)
        Route::get('/admin/clientes', [AdminController::class, 'clientes'])->name('admin.clientes');
    });
});

Route::get('/servicio/{slug}', function ($slug) {
    return view('servicio', ['slug' => $slug]);
})->name('servicio');

/*
|--------------------------------------------------------------------------
| Auth (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';