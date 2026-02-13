@extends('layouts.app')

@section('title', 'Editar Lote')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Editar Lote: {{ $lote->numero_lote }}</h1>
                <p class="text-slate-500 mt-1">Atualize as informações do lote de material.</p>
            </div>
            <a href="{{ route('lotes.index') }}"
                class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Voltar para lista
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <form action="{{ route('lotes.update', $lote) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Coluna 1: Produto e Identificação -->
                    <div class="space-y-6">
                        <div>
                            <label for="produto_id"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Produto / Material <span class="text-rose-500">*</span>
                            </label>
                            <select id="produto_id" name="produto_id" required>
                                <option value="">Selecione um produto...</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}" {{ (old('produto_id', $lote->produto_id) == $produto->id) ? 'selected' : '' }}>
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
                            <input type="text" id="numero_lote" name="numero_lote"
                                value="{{ old('numero_lote', $lote->numero_lote) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                placeholder="Ex: LOTE-2024-X">
                            @error('numero_lote') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Coluna 2: Quantidade e Datas -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    Qtd. Inicial (Travada)
                                </label>
                                <input type="number" value="{{ $lote->quantidade_inicial }}" disabled
                                    class="w-full px-4 py-3 bg-slate-100 border-none rounded-xl text-sm text-slate-400 cursor-not-allowed">
                            </div>
                            <div>
                                <label for="data_entrada"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Entrada <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" id="data_entrada" name="data_entrada"
                                    value="{{ old('data_entrada', $lote->data_entrada->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="data_validade"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Data de Validade <span class="text-slate-400 font-normal normal-case">(Opcional)</span>
                            </label>
                            <input type="date" id="data_validade" name="data_validade"
                                value="{{ old('data_validade', $lote->data_validade ? $lote->data_validade->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
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
                        class="px-8 py-3 bg-slate-900 hover:bg-black text-white font-bold rounded-xl shadow-lg transition-all flex items-center">
                        <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
            new TomSelect("#produto_id", {
                create: false,
                maxItems: 1,
                placeholder: "Pesquisar produto..."
            });
        });
    </script>
@endsection