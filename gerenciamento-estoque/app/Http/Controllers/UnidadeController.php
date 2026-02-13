<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index()
    {
        $unidades = Unidade::orderBy('nome')->get();
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|unique:unidades,nome|max:255',
            'responsavel_padrao' => 'nullable|string|max:255',
        ]);

        Unidade::create($request->all());

        return redirect()->route('unidades.index')->with('success', 'Unidade cadastrada com sucesso!');
    }

    public function edit(Unidade $unidade)
    {
        return view('unidades.edit', compact('unidade'));
    }

    public function update(Request $request, Unidade $unidade)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:unidades,nome,' . $unidade->id,
            'responsavel_padrao' => 'nullable|string|max:255',
        ]);

        $unidade->update($request->all());

        return redirect()->route('unidades.index')->with('success', 'Unidade atualizada!');
    }

    public function destroy(Unidade $unidade)
    {
        // Verificar se existem movimentações vinculadas
        if ($unidade->movimentacoes()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma unidade que possui histórico de movimentações.');
        }

        $unidade->delete();

        return redirect()->route('unidades.index')->with('success', 'Unidade removida.');
    }
}
