@extends('layouts.app')

@section('title', 'Detalhes da Movimentação')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Detalhes do Registro</h1>
                <p class="text-slate-500 mt-1">Dados técnicos da operação realizada no sistema.</p>
            </div>
            <div class="flex items-center gap-4">
                @if($movimentacao->tipo == 'saida')
                    <a href="{{ route('movimentacoes.recibo', $movimentacao) }}" target="_blank"
                        class="flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                        <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                        Imprimir Recibo
                    </a>
                @endif
                <a href="{{ route('movimentacoes.index') }}"
                    class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Voltar ao histórico
                </a>
            </div>
        </div>

        <!-- Cards de Informação -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Coluna da Esquerda: Dados da Operação (Glassmorphism Effect) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <h2
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center">
                                <i data-lucide="activity" class="w-3 h-3 mr-2"></i>
                                Dados da Operação
                            </h2>
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider
                                            {{ $movimentacao->tipo == 'entrada' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    {{ $movimentacao->tipo == 'entrada' ? 'Entrada (+)' : 'Saída (-)' }}
                                </span>
                                <span class="text-4xl font-black text-slate-900">
                                    {{ $movimentacao->quantidade }} <span
                                        class="text-lg font-medium text-slate-400">unid.</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Responsável
                                pela Ação</label>
                            <div class="flex items-center p-4 bg-slate-50 rounded-xl">
                                <div
                                    class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center mr-3 font-bold text-xs">
                                    {{ strtoupper(substr($movimentacao->responsavel ?? 'S', 0, 1)) }}
                                </div>
                                <span
                                    class="text-sm font-bold text-slate-700">{{ $movimentacao->responsavel ?? 'Sistema (Automático)' }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Horário
                                do Registro</label>
                            <div class="flex items-center p-4 bg-slate-50 rounded-xl">
                                <i data-lucide="calendar" class="w-4 h-4 text-slate-400 mr-3"></i>
                                <span
                                    class="text-sm font-bold text-slate-700">{{ $movimentacao->data_movimentacao ? $movimentacao->data_movimentacao->format('d/m/Y - H:i:s') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Observações
                            Técnicas</label>
                        <div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-600 italic leading-relaxed min-h-[100px]">
                            {{ $movimentacao->observacao ?? 'Nenhuma observação detalhada para este registro.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita: Card do Lote (Sticky) -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-xl">
                    <h3 class="text-[10px] font-bold text-teal-400 uppercase tracking-widest mb-4 flex items-center">
                        <i data-lucide="package" class="w-3 h-3 mr-2"></i>
                        Material Vinculado
                    </h3>

                    <div class="mb-6">
                        <div class="text-xl font-bold leading-tight">{{ $movimentacao->lote->produto->nome ?? 'Material Indisponível' }}</div>
                        <div class="text-teal-400/60 text-xs font-medium mt-1">SKU:
                            {{ $movimentacao->lote->produto->codigo ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-white/10">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 font-medium tracking-tight">Identificador Lote:</span>
                            <span class="text-sm font-bold tracking-tight">{{ $movimentacao->lote->numero_lote ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 font-medium tracking-tight">Estoque no Lote:</span>
                            <span class="text-sm font-bold tracking-tight">{{ $movimentacao->lote->quantidade_atual ?? 0 }}
                                unid.</span>
                        </div>
                        @if($movimentacao->lote && $movimentacao->lote->data_validade)
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-medium tracking-tight">Expiração:</span>
                                <span
                                    class="text-sm font-bold tracking-tight {{ $movimentacao->lote->estaVencido() ? 'text-rose-400' : '' }}">
                                    {{ $movimentacao->lote->data_validade->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        @if($movimentacao->lote)
                            <a href="{{ route('lotes.show', $movimentacao->lote) }}"
                                class="w-full py-3 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center">
                                Ver detalhes do lote
                                <i data-lucide="external-link" class="w-3 h-3 ml-2"></i>
                            </a>
                        @else
                            <button disabled class="w-full py-3 bg-white/5 text-white/30 text-xs font-bold rounded-xl cursor-not-allowed">
                                Lote não disponível
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Professor Explica: 
                             Transparência é tudo. Saber QUEM e QUANDO fez é segurança. -->
                <div
                    class="p-6 bg-teal-50 rounded-2xl border border-teal-100 italic text-[11px] text-teal-700 leading-relaxed shadow-sm">
                    <strong>Dica do Professor:</strong> Registros detalhados evitam o "quem foi?" no futuro. Sempre insira
                    observações claras para facilitar auditorias posteriores.
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