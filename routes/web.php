<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SecondAuth\SecondAuthenticationController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';