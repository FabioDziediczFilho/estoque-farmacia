@extends('layouts.app')

@section('title', 'Detalhes da Movimentação')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes da Movimentação</h1>
        <a href="{{ route('movimentacoes.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informações da Movimentação -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações da Movimentação</h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Tipo:</span>
                        <div class="mt-1">
                            <span class="px-3 inline-flex text-sm leading-5 font-semibold rounded-full 
                                {{ $movimentacao->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($movimentacao->tipo) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Quantidade:</span>
                        <p class="text-2xl font-bold {{ $movimentacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $movimentacao->tipo == 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }} unid.
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Data/Hora:</span>
                        <p class="text-gray-900">{{ $movimentacao->data_movimentacao->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Observação:</span>
                        <p class="text-gray-900">{{ $movimentacao->observacao ?? 'Nenhuma observação registrada' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informações do Lote e Produto -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Lote e Produto</h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Produto:</span>
                        <p class="text-gray-900 font-semibold">{{ $movimentacao->lote->produto->nome }}</p>
                        <p class="text-sm text-gray-600">{{ $movimentacao->lote->produto->codigo }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Número do Lote:</span>
                        <p class="text-gray-900">{{ $movimentacao->lote->numero_lote }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Quantidade no Lote:</span>
                        <p class="text-gray-900">
                            {{ $movimentacao->lote->quantidade_atual }} / {{ $movimentacao->lote->quantidade_inicial }} unidades
                        </p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ ($movimentacao->lote->quantidade_atual / $movimentacao->lote->quantidade_inicial) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Data de Entrada do Lote:</span>
                        <p class="text-gray-900">{{ $movimentacao->lote->data_entrada->format('d/m/Y') }}</p>
                    </div>
                    
                    @if($movimentacao->lote->data_validade)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Validade do Lote:</span>
                            <p class="text-gray-900">{{ $movimentacao->lote->data_validade->format('d/m/Y') }}</p>
                            @if($movimentacao->lote->estaVencido())
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Vencido
                                </span>
                            @elseif($movimentacao->lote->estaProximoDeVencer())
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $movimentacao->lote->dias_para_vencer }} dias
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    OK
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Linha do Tempo -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-center">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Registrado em {{ $movimentacao->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
