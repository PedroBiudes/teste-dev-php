<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FornecedorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fornecedores/cadastrar/cnpj', [FornecedorController::class, 'createFornecedorCNPJ'])->name('create_cnpj');
Route::get('/fornecedores/cadastrar/cpf', [FornecedorController::class, 'createFornecedorCpf'])->name('create_cpf');
Route::post('/fornecedores', [FornecedorController::class, 'store'])->name('store');

Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('index');;
Route::get('/fornecedores/{id}', [FornecedorController::class, 'showBlade'])->name('show');

Route::get('/fornecedores/{fornecedor}/editar', [FornecedorController::class, 'editFornecedor'])->name('edit');
Route::put('/fornecedores/{fornecedor}', [FornecedorController::class, 'update'])->name('update');

Route::delete('/fornecedores/{fornecedor}', [FornecedorController::class, 'destroy'])->name('destroy');

Route::get('/api/cnpj/{cnpj}', [FornecedorController::class, 'buscarDadosCnpj'])->name('api.cnpj.buscar');
Route::get('/api/cep/{cep}', [FornecedorController::class, 'buscarDadosCep'])->name('api.cep.buscar');