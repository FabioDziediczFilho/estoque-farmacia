<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Unidade;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function consumo(Request $request)
    {
        $unidades = Unidade::orderBy('nome')->get();

        $query = Movimentacao::with(['lote.produto', 'unidade'])
            ->where('tipo', 'saida');

        if ($request->unidade_id) {
            $query->where('unidade_id', $request->unidade_id);
        }

        if ($request->data_inicio) {
            $query->whereDate('data_movimentacao', '>=', $request->data_inicio);
        }

        if ($request->data_fim) {
            $query->whereDate('data_movimentacao', '<=', $request->data_fim);
        }

        // Agrupar por produto e unidade para o relatório consolidado
        $consumo = $query->select(
            'unidade_id',
            DB::raw('lotes.produto_id'),
            DB::raw('SUM(movimentacoes.quantidade) as total_quantidade')
        )
            ->join('lotes', 'movimentacoes.lote_id', '=', 'lotes.id')
            ->groupBy('unidade_id', 'lotes.produto_id')
            ->get();

        return view('relatorios.consumo', compact('unidades', 'consumo'));
    }
}
