<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SecondAuth\SecondAuthenticationController;
use App\Http\Controllers\Configuration\RoleController;
use App\Http\Controllers\Empresa\EmpleadoController;
use App\Http\Controllers\Empresa\DepartamentoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('/ingresa-codigo', function () {
    return view('secondAuth.code-second-auth');
})->middleware(['auth', 'verified'])->name('ingresa_codigo');;
*/
Route::get('/confirmacion/secondAuth', [SecondAuthenticationController::class, 'showViewSecondAuthCode'])->middleware(['checkFirstView'])->name('second_auth');
Route::post('/ingresa-codigo', [SecondAuthenticationController::class, 'setSecondAuthCode'])->name('set_second_auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'secondAuthentication'])->name('dashboard');

//Empresa
Route::get('/empresa/empleados', [EmpleadoController::class, 'viewListEmpleados'])->middleware(['checkFirstView'])->name('empleados');
Route::post('/empresa/empleados/create', [EmpleadoController::class, 'create'])->middleware(['checkFirstView'])->name('empleados.create');
Route::delete('/empresa/empleados/delete/{id}', [EmpleadoController::class, 'delete'])->middleware(['checkFirstView'])->name('empleados.delete');
Route::put('/empresa/empleados/update', [EmpleadoController::class, 'update'])->middleware(['checkFirstView'])->name('empleados.update');

Route::get('/empresa/departamentos', [DepartamentoController::class, 'viewListDepartamentos'])->middleware(['checkFirstView'])->name('departamentos');
Route::post('/empresa/departamentos/create', [DepartamentoController::class, 'create'])->middleware(['checkFirstView'])->name('departamentos.create');
Route::delete('/empresa/departamentos/delete/{id}', [DepartamentoController::class, 'delete'])->middleware(['checkFirstView'])->name('departamentos.delete');
Route::put('/empresa/departamentos/update', [DepartamentoController::class, 'update'])->middleware(['checkFirstView'])->name('departamentos.update');


//Roles y Permisos
Route::get('/configuracion/roles', [RoleController::class, 'viewListRoles'])->middleware(['checkFirstView'])->name('roles');
Route::put('/configuracion/roles/permissions/update', [RoleController::class, 'update'])->name('roles.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';