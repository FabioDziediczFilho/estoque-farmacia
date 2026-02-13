@extends('layouts.app')

@section('title', 'Movimentações')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Histórico de Fluxo</h1>
                <p class="text-slate-500 mt-1">Acompanhe todas as entradas e saídas do almoxarifado.</p>
            </div>
            <div class="flex gap-3">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.exportar.movimentacoes') }}"
                        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all flex items-center shadow-sm">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Exportar XLS
                    </a>
                @endif
                <a href="{{ route('movimentacoes.create') }}"
                    class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg shadow-teal-50 transition-all flex items-center">
                    <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                    Nova Movimentação
                </a>
            </div>
        </div>

        <!-- Professor Explica: 
                 Filtros em tempo real ajudam a encontrar agulha no palheiro.
                 Usamos Tom Select no filtro de produto para agilizar a busca. -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
            <form method="GET" action="{{ route('movimentacoes.index') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="produto_id"
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Filtrar por
                        Produto</label>
                    <select id="produto_id" name="produto_id" placeholder="Digite o nome do material...">
                        <option value="">Todos os produtos</option>
                        @foreach($produtos as $produto)
                            <option value="{{ $produto->id }}" {{ request('produto_id') == $produto->id ? 'selected' : '' }}>
                                {{ $produto->nome }} ({{ $produto->codigo }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tipo"
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tipo</label>
                    <select id="tipo" name="tipo"
                        class="w-full px-4 py-2 border-slate-200 rounded-xl text-sm focus:ring-teal-500">
                        <option value="">Ambos</option>
                        <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entradas (+)</option>
                        <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saídas (-)</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                        Filtrar
                    </button>
                    <a href="{{ route('movimentacoes.index') }}"
                        class="p-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all"
                        title="Limpar Filtros">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabela de Movimentações -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Data / Hora
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Operação
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Material /
                                Lote</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Qtd.</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Responsável
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($movimentacoes as $mov)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ $mov->data_movimentacao->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                        {{ $mov->data_movimentacao->format('H:i') }}
                                    </div>
                                    @if($mov->protocolo)
                                        <div
                                            class="mt-1 text-[9px] font-black text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded inline-block">
                                            {{ $mov->protocolo }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider
                                                {{ $mov->tipo == 'entrada' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        {{ $mov->tipo == 'entrada' ? 'Entrada (+)' : 'Saída (-)' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800">{{ $mov->lote->produto->nome }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">Lote: {{ $mov->lote->numero_lote }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-black {{ $mov->tipo == 'entrada' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $mov->tipo == 'entrada' ? '+' : '-' }}{{ $mov->quantidade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                    {{ $mov->responsavel ?? 'Sistema' }}
                                </td>
                                <td class="px-6 py-4 flex items-center gap-2">
                                    <a href="{{ route('movimentacoes.show', $mov) }}"
                                        class="p-2 text-slate-400 hover:text-[#006266] transition-colors inline-block"
                                        title="Detalhes">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if($mov->tipo == 'saida')
                                        <a href="{{ route('movimentacoes.recibo', $mov) }}" target="_blank"
                                            class="p-2 text-slate-400 hover:text-emerald-600 transition-colors inline-block"
                                            title="Imprimir Recibo">
                                            <i data-lucide="printer" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                    Nenhuma movimentação para exibir nos filtros selecionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação Professor: Mantivemos o estilo padrão mas dentro do grid layout -->
        <div class="mt-8">
            {{ $movimentacoes->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            // Professor: Buscador inteligente para filtros
            new TomSelect("#produto_id", {
                create: false,
                maxItems: 1,
                placeholder: "Todos os produtos"
            });
        });
    </script>
@endsection