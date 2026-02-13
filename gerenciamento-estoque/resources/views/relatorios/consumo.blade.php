@extends('layouts.app')

@section('title', 'Relatório de Consumo')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <!-- Cabeçalho -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight text-sms-green">Relatório de Consumo</h1>
                <p class="text-slate-500 font-medium">Análise de saída de materiais por unidade e período.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    Imprimir
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/40 border border-white p-8">
            <form method="GET" action="{{ route('relatorios.consumo') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-1">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Unidade</label>
                    <select name="unidade_id"
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-teal-500 transition-all">
                        <option value="">Todas as Unidades</option>
                        @foreach($unidades as $u)
                            <option value="{{ $u->id }}" {{ request('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Data
                        Início</label>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-teal-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Data
                        Fim</label>
                    <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-teal-500 transition-all">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 py-3.5 bg-sms-gradient text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg border border-teal-700 transition-all">
                        Filtrar
                    </button>
                    <a href="{{ route('relatorios.consumo') }}"
                        class="p-3.5 bg-slate-100 text-slate-500 rounded-2xl hover:bg-slate-200 transition-all">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabela de Resultados -->
        <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/40 border border-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unidade /
                                Destino</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Material /
                                Medicamento</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Quantidade Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($consumo as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        </div>
                                        <span
                                            class="font-bold text-slate-700">{{ $item->unidade->nome ?? 'Unidade Deletada' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <?php    $produto = \App\Models\Produto::find($item->produto_id); ?>
                                    <div class="text-sm font-bold text-slate-800">{{ $produto->nome ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium uppercase tracking-tight">Cód:
                                        {{ $produto->codigo ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-lg font-black">
                                        {{ number_format($item->total_quantidade, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="inbox" class="w-12 h-12 text-slate-200 mb-4"></i>
                                        <p class="text-slate-400 font-medium italic">Nenhum consumo registrado com os filtros
                                            selecionados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection