<?php

use App\Http\Controllers\Api\FornecedorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;   

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

Route::apiResource('fornecedores', FornecedorController::class);

Route::get('/fornecedores/show/{fornecedor}', [FornecedorController::class, 'show']);

Route::post('/fornecedores/criar', [FornecedorController::class, 'criarFornecedor']);

// Route::get('/fornecedores/buscar-cnpj', [FornecedorController::class, 'buscarPorCnpj']);