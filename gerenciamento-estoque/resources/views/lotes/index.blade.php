@extends('layouts.app')

@section('title', 'Gestão de Lotes')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Controle de Lotes</h1>
                <p class="text-slate-500 mt-1">Rastreabilidade completa por validade e quantidade.</p>
            </div>
            <div class="flex gap-3">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.exportar.lotes') }}"
                        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all flex items-center shadow-sm">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Exportar Planilha
                    </a>
                    <a href="{{ route('admin.lotes.create') }}"
                        class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg shadow-teal-50 transition-all flex items-center">
                        <i data-lucide="plus-circle" class="w-5 h-4 mr-2"></i>
                        Novo Lote
                    </a>
                @endif
            </div>
        </div>

        <!-- Filtros de Busca -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
            <form method="GET" action="{{ route('lotes.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por nome do produto ou número do lote..."
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                        Filtrar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('lotes.index') }}"
                            class="p-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all"
                            title="Limpar Filtros">
                            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabela de Lotes -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Material /
                                Identificação</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                                Disponibilidade</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Datas</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($lotes as $lote)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800">{{ $lote->produto->nome }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span
                                            class="text-[10px] font-bold px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded-md">SKU:
                                            {{ $lote->produto->codigo }}</span>
                                        <span class="text-[10px] font-medium text-slate-400">Lote:
                                            {{ $lote->numero_lote }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center">
                                        <div class="text-sm font-black text-slate-900">{{ $lote->quantidade_atual }} <span
                                                class="text-slate-400 font-medium">/ {{ $lote->quantidade_inicial }}</span>
                                        </div>
                                        <div class="w-24 bg-slate-100 h-1.5 rounded-full mt-1.5 overflow-hidden">
                                            <div class="h-full {{ $lote->quantidade_atual > ($lote->quantidade_inicial * 0.2) ? 'bg-teal-500' : 'bg-rose-500' }}"
                                                style="width: {{ ($lote->quantidade_atual / $lote->quantidade_inicial) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="flex items-center text-[10px] font-medium text-slate-500">
                                            <i data-lucide="log-in" class="w-3 h-3 mr-1.5 text-slate-400"></i>
                                            Entrada: {{ $lote->data_entrada->format('d/m/Y') }}
                                        </span>
                                        <span
                                            class="flex items-center text-[10px] font-bold {{ $lote->estaVencido() ? 'text-rose-600' : 'text-slate-500' }}">
                                            <i data-lucide="clock" class="w-3 h-3 mr-1.5 opacity-50"></i>
                                            Validade:
                                            {{ $lote->data_validade ? $lote->data_validade->format('d/m/Y') : 'Indeterminada' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                        $statusText = 'Disponível';
                                        $dotClass = 'bg-emerald-500';
                                        $pulse = false;

                                        if ($lote->quantidade_atual == 0) {
                                            $statusClass = 'bg-slate-50 text-slate-400 border-slate-100';
                                            $statusText = 'Esgotado';
                                            $dotClass = 'bg-slate-300';
                                        } elseif ($lote->data_validade && $lote->estaVencido()) {
                                            $statusClass = 'bg-rose-50 text-rose-600 border-rose-100';
                                            $statusText = 'Vencido';
                                            $dotClass = 'bg-rose-500';
                                            $pulse = true;
                                        } elseif ($lote->data_validade && $lote->estaProximoDeVencer(60)) {
                                            $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                            $statusText = 'Crítico (' . $lote->dias_para_vencer . 'd)';
                                            $dotClass = 'bg-amber-500';
                                        }
                                    @endphp

                                    <div class="flex items-center gap-2">
                                        <div class="relative flex h-2.5 w-2.5">
                                            @if($pulse)
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotClass }} opacity-75"></span>
                                            @endif
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $dotClass }}"></span>
                                        </div>
                                        <span
                                            class="px-2.5 py-1 rounded-lg {{ $statusClass }} text-[10px] font-black uppercase tracking-wider border">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('lotes.show', $lote) }}"
                                            class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all"
                                            title="Ver Detalhes">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>

                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.lotes.edit', $lote) }}"
                                                class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                                title="Editar">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>
                                        @endif

                                        @if($lote->quantidade_atual > 0)
                                            <a href="{{ route('lotes.saida', $lote) }}"
                                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                title="Registrar Saída">
                                                <i data-lucide="minus-square" class="w-4 h-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-slate-50 rounded-full mb-3 text-slate-300">
                                            <i data-lucide="box" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-slate-400 font-medium">Nenhum lote encontrado para esta busca.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <div class="mt-8">
            {{ $lotes->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection