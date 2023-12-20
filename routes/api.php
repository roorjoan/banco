<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Endpoint para Crear una Nueva Cuenta Bancaria
Route::post('/accounts', [AccountController::class, 'store']);

//Endpoint para Realizar un Depósito
Route::post('/transactions/deposit', [AccountController::class, 'deposit']);

//Endpoint para Realizar un Retiro
Route::post('/transactions/withdrawal', [AccountController::class, 'withdrawal']);

//Endpoint para Consultar el Saldo de una Cuenta
Route::get('/accounts/{id}/balance', [AccountController::class, 'balance']);

//Endpoint para Consultar el Historial de Transacciones
Route::get('/accounts/{id}/transactions', [AccountController::class, 'transactions']);
