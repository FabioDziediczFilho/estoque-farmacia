<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\ImportacaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produtos: Consulta para todos, gestão para admin
    Route::get('produtos', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::get('produtos/{produto}', [ProdutoController::class, 'show'])->name('produtos.show');

    // Unidades: Consulta para todos, gestão para admin
    Route::get('unidades', [UnidadeController::class, 'index'])->name('unidades.index');
    Route::get('unidades/{unidade}', [UnidadeController::class, 'show'])->name('unidades.show');

    // Lotes: Consulta e Saída para todos, gestão para admin
    Route::get('lotes', [LoteController::class, 'index'])->name('lotes.index');
    Route::get('lotes/{lote}', [LoteController::class, 'show'])->name('lotes.show');
    Route::get('/lotes/{lote}/saida', [LoteController::class, 'createSaida'])->name('lotes.saida');
    Route::post('/lotes/{lote}/saida', [LoteController::class, 'storeSaida'])->name('lotes.storeSaida');

    // Movimentações: Consulta e Novas para todos, edição para admin
    Route::get('movimentacoes', [MovimentacaoController::class, 'index'])->name('movimentacoes.index');
    Route::get('movimentacoes/create', [MovimentacaoController::class, 'create'])->name('movimentacoes.create');
    Route::post('movimentacoes', [MovimentacaoController::class, 'store'])->name('movimentacoes.store');
    Route::get('movimentacoes/{movimentacao}', [MovimentacaoController::class, 'show'])->name('movimentacoes.show');
    Route::get('/movimentacoes/{movimentacao}/recibo', [MovimentacaoController::class, 'recibo'])->name('movimentacoes.recibo');

    // Relatórios: Consulta básica
    Route::get('/relatorios/consumo', [RelatorioController::class, 'consumo'])->name('relatorios.consumo');

    // Rotas Administrativas (Gestão, Importação, Exportação e Auditoria)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('auditorias', [AuditController::class, 'index'])->name('audits.index');

        // Gestão de Produtos e Unidades
        Route::resource('produtos', ProdutoController::class)->except(['index', 'show']);
        Route::resource('unidades', UnidadeController::class)->except(['index', 'show']);
        Route::resource('lotes', LoteController::class)->except(['index', 'show']);
        Route::resource('movimentacoes', MovimentacaoController::class)->only(['edit', 'update', 'destroy']);

        // Ferramentas
        Route::get('/importacao', [ImportacaoController::class, 'index'])->name('importacao.index');
        Route::post('/importacao', [ImportacaoController::class, 'import'])->name('importacao.import');
        Route::get('/exportar/lotes', [ExportController::class, 'lotes'])->name('exportar.lotes');
        Route::get('/exportar/movimentacoes', [ExportController::class, 'movimentacoes'])->name('exportar.movimentacoes');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
