<?php

use App\Http\Controllers\ReunionController;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\UsuarioDashboard;
use App\Livewire\Dashboard\Visitas;
use App\Livewire\Dashboard\AdministradorDashboard;
use App\Livewire\Dashboard\AdministradorSolicitudes;
use App\Livewire\Dashboard\AdministradoUsuarios;
use App\Livewire\Dashboard\SuperAdminDashboard;
use App\Livewire\Dashboard\SolicitudCompleta;
use App\Livewire\Dashboard\SuperAdminReportes;
use App\Livewire\Dashboard\SuperAdminSolicitudes;
use App\Livewire\Dashboard\SuperadminUsuarios;
use App\Livewire\Dashboard\SuperAdminVisitas;
use App\Livewire\Dashboard\UsuarioSolicitudes;
use App\Livewire\Dashboard\SuperAdminSeguridad;
use App\Livewire\Dashboard\SuperAdminInfogeneral;
use App\Livewire\Dashboard\SuperAdminBd;
use App\Livewire\Dashboard\SuperAdminCargo;
use App\Livewire\Dashboard\SuperAdminCategorias;
use App\Livewire\Dashboard\SuperAdminComunidades;
use App\Livewire\Dashboard\SuperAdminConcejales;
use App\Livewire\Dashboard\SuperAdminInstituciones;
use App\Livewire\Dashboard\Trabajadores\Index;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/CMBEY', function () {
    return view('home'); 
});;

Route::get('/login', LoginForm::class)->name('login')->middleware('guest');

Route::get('/registro', RegisterForm::class)->name('registro')->middleware('guest');

Route::get('/recuperar-contraseña', \App\Livewire\Auth\PasswordRecoveryForm::class)->name('password.recovery')->middleware('guest');

//RUTAS DEL SIDEBAR
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        switch ($user->role) {
            case 1: // SuperAdministrador
                return redirect()->route('dashboard.superadmin');
            case 2: // Administrador
                return redirect()->route('dashboard.admin');
            case 3: // Usuario
                return redirect()->route('dashboard.usuario');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    // Role-specific dashboard routes

    // general
        Route::get('/dashboard/infoGeneral', SuperAdminInfogeneral::class)
        ->name('dashboard.infoGeneral')
        ->middleware('role:3,2,1');

        Route::get('/dashboard/seguridad', SuperAdminSeguridad::class)
        ->name('dashboard.seguridad')
        ->middleware('role:3,2,1');

        
        Route::get('/dashboard/visitas', Visitas::class)
            ->name('dashboard.visitas')
            ->middleware('role:2,3');
    
            //usuario routes

        Route::get('/dashboard/usuario', UsuarioDashboard::class)
            ->name('dashboard.usuario')
            ->middleware('role:3');

        Route::get('/dashboard/usuario/solicitud/crear', UsuarioSolicitudes::class)
            ->name('dashboard.usuario.solicitud.crear')
            ->middleware('role:3');

        Route::get('/dashboard/usuario/solicitud', UsuarioSolicitudes::class)
            ->name('dashboard.usuario.solicitud')
            ->middleware('role:3');


        //administrador routes

        Route::get('/dashboard/administrador', AdministradorDashboard::class)
            ->name('dashboard.admin')
            ->middleware('role:2');

        Route::get('/dashboard/administrador/solicitudes', AdministradorSolicitudes::class)
            ->name('dashboard.admin.solicitudes')
            ->middleware('role:2');


        //superdmin routes
        Route::get('/dashboard/reuniones', [ReunionController::class, 'index'])
            ->name('dashboard.reuniones.index')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/crear', [ReunionController::class, 'create'])
            ->name('dashboard.reuniones.create')
            ->middleware('role:1,2');

        Route::post('/dashboard/reuniones', [ReunionController::class, 'store'])
            ->name('dashboard.reuniones.store')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/{reunion}', [ReunionController::class, 'show'])
            ->name('dashboard.reuniones.show')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/{reunion}/editar', [ReunionController::class, 'edit'])
            ->name('dashboard.reuniones.edit')
            ->middleware('role:1,2');

        Route::put('/dashboard/reuniones/{reunion}', [ReunionController::class, 'update'])
            ->name('dashboard.reuniones.update')
            ->middleware('role:1,2');

        Route::delete('/dashboard/reuniones/{reunion}', [ReunionController::class, 'destroy'])
            ->name('dashboard.reuniones.destroy')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/{reunion}/finalizar', [ReunionController::class, 'finalize'])
            ->name('dashboard.reuniones.finalize')
            ->middleware('role:1,2');

        Route::post('/dashboard/reuniones/{reunion}/finalizar', [ReunionController::class, 'storeFinalization'])
            ->name('dashboard.reuniones.finalization.store')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/export/pdf', [ReunionController::class, 'exportPdf'])
            ->name('dashboard.reuniones.export.pdf')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/export/excel', [ReunionController::class, 'exportExcel'])
            ->name('dashboard.reuniones.export.excel')
            ->middleware('role:1,2');

        Route::get('/dashboard/reuniones/{reunion}/export/acta', [ReunionController::class, 'exportActaPdf'])
            ->name('dashboard.reuniones.export.acta')
            ->middleware('role:1,2');

        Route::get('/dashboard/superadmin/concejales', SuperAdminConcejales::class)
            ->name('dashboard.superadmin.concejales')
            ->middleware('role:1');

        Route::get('/dashboard/superadmin/instituciones', SuperAdminInstituciones::class)
            ->name('dashboard.superadmin.instituciones')
            ->middleware('role:1');

        Route::get('/dashboard/superadmin', SuperAdminDashboard::class)
            ->name('dashboard.superadmin')
            ->middleware('role:1');

        Route::get('/dashboard/superadmin/visitas', SuperAdminVisitas::class)
            ->name('dashboard.superadmin.visitas')
            ->middleware('role:1');

        Route::get('/dashboard/superadmin/trabajadores', Index::class)
            ->name('dashboard.superadmin.trabajadores')
            ->middleware('role:1');
/* 
        Route::get('/trabajadores/exportar-todos', [TrabajadorController::class, 'exportarTodosPdf'])
            ->name('trabajadores.exportarTodosPdf')
            ->middleware('role:1');

        Route::get('/trabajadores/{persona_cedula}/pdf', [TrabajadorController::class, 'exportPdf'])
            ->name('trabajadores.exportPdf')
            ->middleware('role:1');

        Route::get('/trabajadores/exportar', [TrabajadorController::class, 'exportExcel'])
            ->name('trabajadores.export')
            ->middleware('role:1');

        Route::resource('trabajadores', TrabajadorController::class)
            ->parameters(['trabajadores' => 'persona_cedula' ])
            ->middleware('role:1');

        Route::post('trabajadores/buscar', [TrabajadorController::class, 'buscarPersona'])
            ->name('trabajadores.buscar')
            ->middleware('role:1'); */

        Route::get('/dashboard/admin/usuarios', AdministradoUsuarios::class)
            ->name('dashboard.admin.usuarios')
            ->middleware('role:2');

        Route::get('/dashboard/superadmin/usuarios', SuperadminUsuarios::class)
            ->name('dashboard.superadmin.usuarios')
            ->middleware('role:1');

        Route::get('/dashboard/superadmin/base_datos', SuperAdminBd::class)
            ->name('dashboard.superadmin.basedatos')
            ->middleware('role:1');

        Route::get('/dashboard/superadmin/cargos', SuperAdminCargo::class)
            ->name('dashboard.superadmin.cargos')
            ->middleware('role:1');    

        Route::get('/dashboard/superadmin/comunidad', SuperAdminComunidades::class)
            ->name('dashboard.superadmin.comunidad')
            ->middleware('role:1');    

        Route::get('/dashboard/superadmin/categorias', SuperAdminCategorias::class)
            ->name('dashboard.superadmin.categorias')
            ->middleware('role:1');  

        Route::get('/dashboard/superadmin/reportes', SuperAdminReportes::class)
            ->name('dashboard.superadmin.reportes')
            ->middleware('role:1,2');


        Route::get('/dashboard/superadmin/solicitudes', SuperAdminSolicitudes::class)
            ->name('dashboard.superadmin.solicitudes')
            ->middleware('role:1');
});
