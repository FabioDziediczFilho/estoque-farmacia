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
            $query->whereHas('lote', function ($q) use ($request) {
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

        $unidades = \App\Models\Unidade::orderBy('nome')->get();

        return view('movimentacoes.create', compact('lotes', 'unidades'));
    }

    public function store(Request $request)
    {
        // Se for um envio múltiplo, processamos em lote
        if ($request->has('multiplo') && $request->multiplo == '1' && $request->has('itens')) {
            $itens = json_decode($request->itens, true);

            if (empty($itens)) {
                return redirect()->back()->with('error', 'Nenhum item adicionado à lista!')->withInput();
            }

            try {
                \Illuminate\Support\Facades\DB::beginTransaction();

                // Protocolo baseado em timestamp + aleatório para agrupar estes itens
                $protocolo = 'PRT' . now()->format('YmdHi') . rand(10, 99);

                foreach ($itens as $item) {
                    $lote = Lote::findOrFail($item['lote_id']);

                    // Validação de estoque para saídas
                    if ($request->tipo === 'saida' && $item['quantidade'] > $lote->quantidade_atual) {
                        throw new \Exception("Estoque insuficiente para o item: {$lote->produto->nome}");
                    }

                    Movimentacao::create([
                        'protocolo' => $protocolo,
                        'lote_id' => $item['lote_id'],
                        'tipo' => $request->tipo,
                        'quantidade' => $item['quantidade'],
                        'data_movimentacao' => $request->data_movimentacao,
                        'responsavel' => $request->responsavel,
                        'unidade_id' => $request->unidade_id,
                        'observacao' => $request->observacao
                    ]);
                }

                \Illuminate\Support\Facades\DB::commit();

                return redirect()->route('movimentacoes.index')
                    ->with('success', 'Dispensação múltipla registrada com sucesso! Protocolo: ' . $protocolo);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollback();
                return redirect()->back()
                    ->with('error', 'Erro ao processar dispensação: ' . $e->getMessage())
                    ->withInput();
            }
        }

        // Fluxo padrão (Simples)
        $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'data_movimentacao' => 'required|date',
            'responsavel' => 'required|string|max:255'
        ]);

        $lote = Lote::find($request->lote_id);

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

    /**
     * Gerar visualização de Recibo/Comprovante (Estilo Folha Papel)
     */
    public function recibo(Movimentacao $movimentacao)
    {
        $movimentacao->load(['lote.produto', 'unidade']);

        // Se houver protocolo, buscamos todos os itens desse grupo
        $itens = collect([$movimentacao]);
        if ($movimentacao->protocolo) {
            $itens = Movimentacao::with(['lote.produto', 'unidade'])
                ->where('protocolo', $movimentacao->protocolo)
                ->get();
        }

        return view('movimentacoes.recibo', compact('movimentacao', 'itens'));
    }
}
