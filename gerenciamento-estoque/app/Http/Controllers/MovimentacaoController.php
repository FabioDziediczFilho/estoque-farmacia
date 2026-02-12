<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimentacao;
use App\Models\Lote;

class MovimentacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimentacao::with('lote.produto')
            ->orderBy('data_movimentacao', 'desc');

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('produto_id')) {
            $query->whereHas('lote', function($q) use ($request) {
                $q->where('produto_id', $request->produto_id);
            });
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_movimentacao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_movimentacao', '<=', $request->data_fim . ' 23:59:59');
        }

        $movimentacoes = $query->paginate(20);
        $produtos = \App\Models\Produto::orderBy('nome')->get();

        return view('movimentacoes.index', compact('movimentacoes', 'produtos'));
    }

    public function create()
    {
        $lotes = Lote::with('produto')
            ->where('quantidade_atual', '>', 0)
            ->get();
        
        return view('movimentacoes.create', compact('lotes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'data_movimentacao' => 'required|date',
            'observacao' => 'nullable|string|max:500'
        ]);

        $lote = Lote::find($request->lote_id);

        // Validação para saídas
        if ($request->tipo === 'saida' && $request->quantidade > $lote->quantidade_atual) {
            return redirect()->back()
                ->with('error', 'Quantidade de saída maior que o disponível em estoque!')
                ->withInput();
        }

        Movimentacao::create($request->all());

        return redirect()->route('movimentacoes.index')
            ->with('success', 'Movimentação registrada com sucesso!');
    }

    public function show(Movimentacao $movimentacao)
    {
        $movimentacao->load('lote.produto');
        return view('movimentacoes.show', compact('movimentacao'));
    }

    public function edit(Movimentacao $movimentacao)
    {
        // Não permitir edição de movimentações (para manter integridade)
        return redirect()->route('movimentacoes.index')
            ->with('error', 'Movimentações não podem ser editadas. Crie uma nova movimentação se necessário.');
    }

    public function update(Request $request, string $id)
    {
        // Não permitir edição
        return redirect()->route('movimentacoes.index')
            ->with('error', 'Movimentações não podem ser editadas.');
    }

    public function destroy(Movimentacao $movimentacao)
    {
        // Não permitir exclusão para manter integridade do histórico
        return redirect()->route('movimentacoes.index')
            ->with('error', 'Movimentações não podem ser excluídas para manter o histórico.');
    }
}
