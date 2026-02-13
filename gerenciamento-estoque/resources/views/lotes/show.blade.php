@extends('layouts.app')

@php /** @var \App\Models\Lote $lote */ @endphp

@section('title', 'Lote ' . $lote->numero_lote)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Cabeçalho -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Detalhes do Lote</h1>
            <p class="text-slate-500 mt-1">Rastreamento completo do material: <span class="font-bold text-teal-600">{{ $lote->produto->nome }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            @if($lote->quantidade_atual > 0)
                <a href="{{ route('lotes.saida', $lote) }}" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-rose-50 transition-all flex items-center">
                    <i data-lucide="minus-circle" class="w-4 h-4 mr-1.5"></i>
                    Retirar Estoque
                </a>
            @endif
            <a href="{{ route('lotes.edit', $lote) }}" class="px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-bold rounded-xl transition-all flex items-center">
                <i data-lucide="edit-3" class="w-4 h-4 mr-1.5"></i>
                Editar
            </a>
            <a href="{{ route('lotes.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors flex items-center ml-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Coluna da Esquerda: Ficha Técnica -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 pb-3">Ficha do Lote</h3>
                
                <div class="space-y-6">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Identificação</span>
                        <div class="text-lg font-black text-slate-800 mt-1">{{ $lote->numero_lote }}</div>
                        <div class="text-xs text-slate-400 font-medium tracking-tight">SKU: {{ $lote->produto->codigo }}</div>
                    </div>

                    <div class="pt-6 border-t border-slate-50">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Estoque Atual</span>
                        <div class="flex items-end gap-2 mt-1">
                            <span class="text-3xl font-black text-slate-900">{{ $lote->quantidade_atual }}</span>
                            <span class="text-xs font-bold text-slate-400 mb-1.5">/ {{ $lote->quantidade_inicial }} unidades</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-3 overflow-hidden">
                            <div class="h-full {{ $lote->quantidade_atual > ($lote->quantidade_inicial * 0.2) ? 'bg-teal-500' : 'bg-rose-500' }}" 
                                 style="width: {{ ($lote->quantidade_atual / $lote->quantidade_inicial) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-50">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Entrada</span>
                            <div class="text-sm font-bold text-slate-700 mt-1">{{ $lote->data_entrada->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Validade</span>
                            <div class="text-sm font-bold {{ $lote->estaVencido() ? 'text-rose-600' : 'text-slate-700' }} mt-1">
                                {{ $lote->data_validade ? $lote->data_validade->format('d/m/Y') : 'Indeterminada' }}
                            </div>
                        </div>
                    </div>

                    @if($lote->data_validade)
                    <div class="pt-6 border-t border-slate-50 text-center">
                        @if($lote->estaVencido())
                            <span class="inline-flex px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 font-black text-[10px] uppercase tracking-wider border border-rose-100">
                                <i data-lucide="alert-triangle" class="w-3 h-3 mr-1.5"></i>
                                Vencido há {{ abs($lote->dias_para_vencer) }} dias
                            </span>
                        @elseif($lote->estaProximoDeVencer())
                            <span class="inline-flex px-4 py-1.5 rounded-full bg-yellow-50 text-yellow-600 font-black text-[10px] uppercase tracking-wider border border-yellow-100">
                                <i data-lucide="clock" class="w-3 h-3 mr-1.5"></i>
                                Expira em {{ $lote->dias_para_vencer }} dias
                            </span>
                        @else
                            <span class="inline-flex px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 font-black text-[10px] uppercase tracking-wider border border-emerald-100">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1.5"></i>
                                Validade OK
                            </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-teal-50 rounded-2xl p-6 border border-teal-100 shadow-sm italic text-[11px] text-teal-700 leading-relaxed">
                Acompanhe a barra de progresso do lote. Lotes com menos de 20% (cor vermelha) devem ser repostos urgentemente no almoxarifado.
            </div>
        </div>

        <!-- Coluna da Direita: Histórico -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800">Linha do Tempo de Movimentações</h3>
                    <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded-md">Total: {{ $lote->movimentacoes->count() }} oper.</span>
                </div>
                
                <div class="p-8">
                    @forelse($lote->movimentacoes->sortByDesc('data_movimentacao') as $mov)
                    <div class="relative pl-10 pb-10 last:pb-0 group">
                        <!-- Linha da Timeline -->
                        @if(!$loop->last)
                        <div class="absolute left-[15px] top-8 bottom-0 w-[2px] bg-slate-100 group-hover:bg-teal-100 transition-colors"></div>
                        @endif
                        
                        <!-- Ícone de Status -->
                        <div class="absolute left-0 top-0 w-8 h-8 rounded-full flex items-center justify-center z-10 
                             {{ $mov->tipo == 'entrada' ? 'bg-emerald-50 text-emerald-600 shadow-emerald-50' : 'bg-rose-50 text-rose-600 shadow-rose-50' }} shadow-sm">
                            <i data-lucide="{{ $mov->tipo == 'entrada' ? 'plus' : 'minus' }}" class="w-4 h-4"></i>
                        </div>

                        <!-- Conteúdo do Registro -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl group-hover:bg-slate-50 transition-all border border-transparent group-hover:border-slate-100">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-black text-slate-800">
                                            {{ $mov->tipo == 'entrada' ? 'Entrada' : 'Saída' }}: {{ $mov->quantidade }} unid.
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium">| por {{ $mov->responsavel ?? 'Administrador' }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed italic">
                                        {{ $mov->observacao ?? 'Sem observações adicionais.' }}
                                    </p>
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">
                                    {{ $mov->data_movimentacao ? $mov->data_movimentacao->format('d/m/Y') : 'N/A' }}<br>
                                    <span class="text-teal-600 opacity-60">{{ $mov->data_movimentacao ? $mov->data_movimentacao->format('H:i') : '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <div class="p-4 bg-slate-50 rounded-full inline-block mb-4 text-slate-300">
                            <i data-lucide="ghost" class="w-10 h-10"></i>
                        </div>
                        <p class="text-slate-400 italic">Estranho... Nenhuma movimentação registrada para este lote ainda.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
