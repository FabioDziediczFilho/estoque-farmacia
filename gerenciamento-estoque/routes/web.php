<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\ImportacaoController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('produtos', ProdutoController::class);
Route::resource('lotes', LoteController::class);
Route::get('/lotes/{lote}/saida', [LoteController::class, 'createSaida'])->name('lotes.saida');
Route::post('/lotes/{lote}/saida', [LoteController::class, 'storeSaida'])->name('lotes.storeSaida');
Route::resource('movimentacoes', MovimentacaoController::class);

Route::get('/importacao', [ImportacaoController::class, 'index'])->name('importacao.index');
Route::post('/importacao', [ImportacaoController::class, 'import'])->name('importacao.import');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
