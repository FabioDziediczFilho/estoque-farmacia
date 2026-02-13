<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Lote;
use App\Models\Movimentacao;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();
        $limiteValidade = Carbon::today()->addDays(30);

        // Cards
        $totalItens = Produto::count();
        $saidasHoje = Movimentacao::whereDate('data_movimentacao', $hoje)
            ->where('tipo', 'saida')
            ->count();

        // Estoque Crítico: Itens com quantidade total <= 5 (apenas os que têm algum saldo)
        $itensCriticos = Produto::all()->filter(function ($p) {
            $total = $p->quantidade_total;
            return $total > 0 && $total <= 5;
        })->count();

        // A Vencer (30 dias)
        $lotesVencendo = Lote::where('data_validade', '<=', $limiteValidade)
            ->where('data_validade', '>=', $hoje)
            ->where('quantidade_atual', '>', 0)
            ->count();

        // Dados para Gráfico de Categorias (Formatado para JS)
        $catsLabels = [];
        $catsValues = [];
        $categoriasRaw = Produto::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->get();

        foreach ($categoriasRaw as $cat) {
            $catsLabels[] = ucfirst($cat->tipo);
            $catsValues[] = $cat->total;
        }

        // Situação para Gráfico de Barras
        $situacao = [
            'normal' => max(0, $totalItens - $itensCriticos),
            'critico' => $itensCriticos,
            'vencendo' => $lotesVencendo
        ];

        // Lotes Críticos para Tabela no Dashboard
        $lotesCriticos = Lote::with('produto')
            ->where('quantidade_atual', '>', 0)
            ->where('data_validade', '<=', Carbon::now()->addDays(60))
            ->orderBy('data_validade')
            ->take(5)
            ->get();

        return view('dashboard', [
            'totalItens' => $totalItens,
            'saidasHoje' => $saidasHoje,
            'itensCriticos' => $itensCriticos,
            'lotesVencendo' => $lotesVencendo,
            'catsLabels' => $catsLabels,
            'catsValues' => $catsValues,
            'situacao' => $situacao,
            'lotesCriticos' => $lotesCriticos
        ]);
    }
}
