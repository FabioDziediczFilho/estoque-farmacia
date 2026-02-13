@extends('layouts.app')

@section('title', 'Novo Lote')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Cadastrar Novo Lote</h1>
                <p class="text-slate-500 mt-1">Vincule materiais a novos lotes de entrada no estoque.</p>
            </div>
            <a href="{{ route('lotes.index') }}"
                class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Voltar para lista
            </a>
        </div>

        <!-- Professor Explica: 
                 Ao cadastrar um lote, a busca rápida do produto é vital.
                 Imagine procurar 'Papel A4' em meio a 500 itens? O Tom Select resolve isso. -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <form action="{{ route('lotes.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Coluna 1: Produto e Identificação -->
                    <div class="space-y-6">
                        <div>
                            <label for="produto_id"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Produto / Material <span class="text-rose-500">*</span>
                            </label>
                            <select id="produto_id" name="produto_id" required
                                placeholder="Comece a digitar o nome do produto...">
                                <option value="">Selecione um produto...</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                                        {{ $produto->nome }} ({{ $produto->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('produto_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="numero_lote"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Identificação do Lote <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="numero_lote" name="numero_lote" value="{{ old('numero_lote') }}" required
                                class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                placeholder="Ex: LOTE-2024-X">
                            @error('numero_lote') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Coluna 2: Quantidade e Datas -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="quantidade_inicial"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Qtd. Inicial <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" id="quantidade_inicial" name="quantidade_inicial"
                                    value="{{ old('quantidade_inicial') }}" required min="1"
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                    placeholder="0">
                            </div>
                            <div>
                                <label for="data_entrada"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Entrada <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" id="data_entrada" name="data_entrada"
                                    value="{{ old('data_entrada', now()->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="data_validade"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Data de Validade <span class="text-slate-400 font-normal normal-case">(Opcional)</span>
                            </label>
                            <input type="date" id="data_validade" name="data_validade" value="{{ old('data_validade') }}"
                                class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                            <p class="mt-2 text-[10px] text-slate-400 italic">Deixe em branco se o material não tiver prazo
                                de validade rígido.</p>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="mt-10 pt-8 border-t border-slate-50 flex items-center justify-end gap-3">
                    <a href="{{ route('lotes.index') }}"
                        class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg shadow-teal-50 transition-all flex items-center">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Cadastrar Lote
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            // Professor: Aplicando a busca inteligente no produto
            new TomSelect("#produto_id", {
                create: false,
                maxItems: 1,
                placeholder: "Pesquisar produto por nome ou código..."
            });
        });
    </script>
@endsection