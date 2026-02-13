@extends('layouts.app')

@section('title', 'Detalhes: ' . $produto->nome)

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span
                        class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider">SKU:
                        {{ $produto->codigo }}</span>
                    <span
                        class="px-2 py-0.5 rounded {{ $produto->status_estoque['color'] }} text-[10px] font-bold uppercase tracking-wider">
                        {{ $produto->status_estoque['label'] }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $produto->nome }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('produtos.edit', $produto) }}"
                    class="px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all flex items-center shadow-lg shadow-slate-200">
                    <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                    Editar Item
                </a>
                <a href="{{ route('produtos.index') }}"
                    class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Esquerda: Ficha Técnica -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-widest flex items-center">
                            <i data-lucide="file-text" class="w-4 h-4 mr-2 text-teal-600"></i>
                            Ficha Técnica
                        </h2>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="group">
                                <span
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Fabricante</span>
                                <p class="text-slate-800 font-medium">{{ $produto->fabricante ?? 'Não informado' }}</p>
                            </div>
                            <div class="group">
                                <span
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Localização
                                    Física</span>
                                <div class="flex items-center text-teal-700 font-bold">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1.5 opacity-50"></i>
                                    {{ $produto->local_armazenamento ?? 'Não definido' }}
                                </div>
                            </div>
                            <div class="group">
                                <span
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Categoria</span>
                                <p class="text-slate-800 font-medium capitalize">{{ $produto->tipo }}</p>
                            </div>
                        </div>

                        <!-- Professor: Resumo visual do estoque -->
                        <div class="pt-6 border-t border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Total em
                                Estoque</span>
                            <div class="flex items-end gap-2">
                                <span
                                    class="text-4xl font-black text-slate-900 leading-none">{{ $produto->quantidade_total }}</span>
                                <span class="text-slate-500 font-bold text-sm pb-1">unidades</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Lotes e Fluxo -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-widest flex items-center">
                            <i data-lucide="box" class="w-4 h-4 mr-2 text-teal-600"></i>
                            Lotes Ativos
                        </h2>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($produto->lotes as $lote)
                            <div class="p-6 hover:bg-slate-50 transition-colors">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="font-bold text-slate-800 text-lg">{{ $lote->numero_lote }}</h3>
                                            @if($lote->data_validade)
                                                @if($lote->estaVencido())
                                                    <span
                                                        class="px-2 py-0.5 rounded bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider border border-rose-100">Vencido</span>
                                                @elseif($lote->estaProximoDeVencer())
                                                    <span
                                                        class="px-2 py-0.5 rounded bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-100">Vence
                                                        em {{ $lote->dias_para_vencer }} dias</span>
                                                @else
                                                    <span
                                                        class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider border border-emerald-100">Válido</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-4 text-xs text-slate-400">
                                            <span class="flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                                Entrada: {{ $lote->data_entrada->format('d/m/Y') }}</span>
                                            @if($lote->data_validade)
                                                <span class="flex items-center"><i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                    Validade: {{ $lote->data_validade->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-slate-700">Quantidade</div>
                                        <div class="text-2xl font-black text-slate-900">{{ $lote->quantidade_atual }} <span
                                                class="text-xs font-medium text-slate-400">/
                                                {{ $lote->quantidade_inicial }}</span></div>
                                    </div>
                                </div>

                                <!-- Professor: Mini Timeline de Movimentações -->
                                @if($lote->movimentacoes->count() > 0)
                                    <div class="mt-6 pl-4 border-l-2 border-slate-100 space-y-3">
                                        @foreach($lote->movimentacoes->take(2) as $mov)
                                            <div class="flex items-center justify-between text-xs">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-2 h-2 rounded-full {{ $mov->tipo == 'entrada' ? 'bg-emerald-400' : 'bg-rose-400' }}">
                                                    </div>
                                                    <span class="font-bold text-slate-600 uppercase">{{ $mov->tipo }}</span>
                                                    <span class="text-slate-400">{{ $mov->quantidade }} unidades</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-slate-500">{{ $mov->responsavel ?? 'Sistema' }}</span>
                                                    <span class="text-slate-300">|</span>
                                                    <span
                                                        class="text-slate-400 italic">{{ $mov->data_movimentacao->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="alert-circle" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Nenhum lote registrado para este item.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection