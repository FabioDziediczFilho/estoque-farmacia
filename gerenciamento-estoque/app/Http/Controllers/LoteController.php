<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\Movimentacao;

class LoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Lote::with('produto')
            ->orderBy('data_entrada', 'desc');

        // Aplicar busca se houver termo de pesquisa
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(numero_lote) like ?', ["%{$search}%"])
                  ->orWhereHas('produto', function($pq) use ($search) {
                      $pq->whereRaw('LOWER(nome) like ?', ["%{$search}%"]);
                  });
            });
        }

        $lotes = $query->paginate(15);
        
        return view('lotes.index', compact('lotes'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();
        return view('lotes.create', compact('produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'numero_lote' => 'required|string|max:255',
            'data_validade' => 'nullable|date',
            'quantidade_inicial' => 'required|integer|min:1',
            'data_entrada' => 'required|date'
        ]);

        $lote = Lote::create([
            'produto_id' => $request->produto_id,
            'numero_lote' => $request->numero_lote,
            'data_validade' => $request->data_validade,
            'quantidade_inicial' => $request->quantidade_inicial,
            'quantidade_atual' => $request->quantidade_inicial,
            'data_entrada' => $request->data_entrada
        ]);

        // Criar movimentação de entrada automática
        Movimentacao::create([
            'lote_id' => $lote->id,
            'tipo' => 'entrada',
            'quantidade' => $request->quantidade_inicial,
            'data_movimentacao' => now(),
            'observacao' => 'Entrada inicial do lote'
        ]);

        return redirect()->route('lotes.index')
            ->with('success', 'Lote criado com sucesso!');
    }

    public function show(Lote $lote)
    {
        $lote->load('produto', 'movimentacoes');
        return view('lotes.show', compact('lote'));
    }

    public function edit(Lote $lote)
    {
        $produtos = Produto::orderBy('nome')->get();
        return view('lotes.edit', compact('lote', 'produtos'));
    }

    public function update(Request $request, Lote $lote)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'numero_lote' => 'required|string|max:255',
            'data_validade' => 'nullable|date',
            'data_entrada' => 'required|date'
        ]);

        $lote->update($request->all());
        
        return redirect()->route('lotes.index')
            ->with('success', 'Lote atualizado com sucesso!');
    }

    public function destroy(Lote $lote)
    {
        if ($lote->quantidade_atual > 0) {
            return redirect()->route('lotes.index')
                ->with('error', 'Não é possível excluir um lote que ainda possui quantidade em estoque!');
        }

        $lote->delete();
        
        return redirect()->route('lotes.index')
            ->with('success', 'Lote excluído com sucesso!');
    }

    public function createSaida(Lote $lote)
    {
        return view('lotes.saida', compact('lote'));
    }

    public function storeSaida(Request $request, Lote $lote)
    {
        $request->validate([
            'quantidade' => 'required|integer|min:1|max:' . $lote->quantidade_atual,
            'observacao' => 'nullable|string|max:500'
        ]);

        Movimentacao::create([
            'lote_id' => $lote->id,
            'tipo' => 'saida',
            'quantidade' => $request->quantidade,
            'data_movimentacao' => now(),
            'observacao' => $request->observacao
        ]);

        return redirect()->route('lotes.show', $lote)
            ->with('success', 'Saída registrada com sucesso!');
    }
}
