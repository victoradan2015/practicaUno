<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MobileApp\APILoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/generate-token', function (Request $request) {
    $token = $request->user()->createToken('Token_de_prueba')->plainTextToken;

    //return response()->json(['token' => $token]);
});

Route::post('/login', [APILoginController::class, 'generateToken']);
Route::post('/set-code-mobile', [APILoginController::class, 'setCodeMobile']);