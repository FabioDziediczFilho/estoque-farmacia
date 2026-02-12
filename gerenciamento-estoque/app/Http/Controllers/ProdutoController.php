<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Lote;
use App\Models\Movimentacao;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('lotes');

        // Aplicar busca se houver termo de pesquisa
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(codigo) like ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(nome) like ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(fabricante) like ?', ["%{$search}%"]);
            });
        }

        $produtos = $query->paginate(10);
        
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'codigo' => 'required|string|unique:produtos,codigo',
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:item,medicamento',
            'fabricante' => 'nullable|string|max:255',
            'local_armazenamento' => 'nullable|string|max:255'
        ];

        // Regras adicionais se for criar lote
        if ($request->has('criar_lote') && $request->criar_lote) {
            $rules['numero_lote'] = 'required|string|max:255';
            $rules['quantidade_inicial'] = 'required|integer|min:1';
            $rules['data_validade'] = 'nullable|date';
            $rules['data_entrada'] = 'required|date';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Criar produto
            $produto = Produto::create($request->only([
                'codigo', 'nome', 'tipo', 'fabricante', 'local_armazenamento'
            ]));

            // Criar lote se solicitado
            if ($request->has('criar_lote') && $request->criar_lote) {
                $lote = Lote::create([
                    'produto_id' => $produto->id,
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
                    'data_movimentacao' => $request->data_entrada,
                    'observacao' => 'Entrada inicial do lote'
                ]);
            }

            DB::commit();

            $message = $request->has('criar_lote') && $request->criar_lote 
                ? 'Produto e lote criados com sucesso!' 
                : 'Produto criado com sucesso!';

            return redirect()->route('produtos.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Produto $produto)
    {
        $produto->load('lotes.movimentacoes');
        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'codigo' => 'required|string|unique:produtos,codigo,' . $produto->id,
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:item,medicamento',
            'fabricante' => 'nullable|string|max:255',
            'local_armazenamento' => 'nullable|string|max:255'
        ]);

        $produto->update($request->all());
        
        return redirect()->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        if ($produto->lotes()->count() > 0) {
            return redirect()->route('produtos.index')
                ->with('error', 'Não é possível excluir um produto que possui lotes cadastrados!');
        }

        $produto->delete();
        
        return redirect()->route('produtos.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}
