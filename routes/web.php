<?php

use App\Http\Controllers\Admin\AdministradorController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmpresaController as AdminEmpresaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cliente\CompraController;
use App\Http\Controllers\Empresa\OfertaController;
use App\Http\Middleware\EmpresaAprobada;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterClienteController;
use App\Http\Controllers\Auth\RegisterEmpresaController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboardController;
use App\Http\Controllers\Empresa\DashboardController as EmpresaDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Zona pública
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Login (uno por tipo de usuario) y cierre de sesión
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'mostrar'])->defaults('rol', 'cliente')->name('login');
Route::post('/login', [LoginController::class, 'autenticar'])->defaults('rol', 'cliente')->middleware('throttle:10,1');

Route::get('/empresa/login', [LoginController::class, 'mostrar'])->defaults('rol', 'empresa')->name('empresa.login');
Route::post('/empresa/login', [LoginController::class, 'autenticar'])->defaults('rol', 'empresa')->middleware('throttle:10,1');

Route::get('/admin/login', [LoginController::class, 'mostrar'])->defaults('rol', 'admin')->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'autenticar'])->defaults('rol', 'admin')->middleware('throttle:10,1');

Route::post('/logout', [LoginController::class, 'salir'])->name('logout');

/*
|--------------------------------------------------------------------------
| Registro de clientes y de empresas
|--------------------------------------------------------------------------
*/
Route::get('/registro', [RegisterClienteController::class, 'create'])->name('cliente.registro');
Route::post('/registro', [RegisterClienteController::class, 'store']);

Route::get('/empresa/registro', [RegisterEmpresaController::class, 'create'])->name('empresa.registro');
Route::post('/empresa/registro', [RegisterEmpresaController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Recuperar contraseña (clientes, empresas y administradores)
|--------------------------------------------------------------------------
*/
Route::get('/olvide-mi-contrasena', [PasswordResetController::class, 'solicitud'])->name('password.request');
Route::post('/olvide-mi-contrasena', [PasswordResetController::class, 'enviarEnlace'])
    ->middleware('throttle:5,1')->name('password.email');
Route::get('/restablecer-contrasena/{token}', [PasswordResetController::class, 'formulario'])->name('password.reset');
Route::post('/restablecer-contrasena', [PasswordResetController::class, 'actualizar'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Paneles por rol
|--------------------------------------------------------------------------
*/
Route::get('/cliente', ClienteDashboardController::class)
    ->middleware(RoleMiddleware::class.':cliente')->name('cliente.dashboard');

Route::get('/empresa', EmpresaDashboardController::class)
    ->middleware(RoleMiddleware::class.':empresa')->name('empresa.dashboard');

/*
|--------------------------------------------------------------------------
| Empresa aprobada: publicación y edición de ofertas
|--------------------------------------------------------------------------
*/
Route::middleware([RoleMiddleware::class.':empresa', EmpresaAprobada::class])
    ->prefix('empresa/ofertas')->name('empresa.ofertas.')->group(function () {
        Route::get('/', [OfertaController::class, 'index'])->name('index');
        Route::get('/crear', [OfertaController::class, 'create'])->name('create');
        Route::post('/', [OfertaController::class, 'store'])->name('store');
        Route::get('/{oferta}/editar', [OfertaController::class, 'edit'])->whereNumber('oferta')->name('edit');
        Route::put('/{oferta}', [OfertaController::class, 'update'])->whereNumber('oferta')->name('update');
    });

/*
|--------------------------------------------------------------------------
| Cliente: compra de cupones, historial y facturas
|--------------------------------------------------------------------------
*/
Route::middleware(RoleMiddleware::class.':cliente')->group(function () {
    Route::get('/mis-cupones', [CompraController::class, 'index'])->name('compras.index');
    Route::get('/cupones/{oferta}/comprar', [CompraController::class, 'create'])->whereNumber('oferta')->name('compras.create');
    Route::post('/cupones/{oferta}/comprar', [CompraController::class, 'store'])->whereNumber('oferta')
        ->middleware('throttle:20,1')->name('compras.store');
    Route::get('/facturas/{compra}', [CompraController::class, 'factura'])->whereNumber('compra')->name('compras.factura');
});

Route::middleware(RoleMiddleware::class.':admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::get('/reportes', ReporteController::class)->name('reportes');

    Route::get('/empresas', [AdminEmpresaController::class, 'index'])->name('empresas.index');
    Route::post('/empresas/{empresa}/aprobar', [AdminEmpresaController::class, 'aprobar'])->name('empresas.aprobar');
    Route::post('/empresas/{empresa}/rechazar', [AdminEmpresaController::class, 'rechazar'])->name('empresas.rechazar');

    Route::get('/administradores', [AdministradorController::class, 'index'])->name('administradores.index');
    Route::get('/administradores/crear', [AdministradorController::class, 'create'])->name('administradores.create');
    Route::post('/administradores', [AdministradorController::class, 'store'])->name('administradores.store');
});
