@extends('layouts.app')

@section('title', 'Lote ' . $lote->numero_lote)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Lote: {{ $lote->numero_lote }}</h1>
        <div class="space-x-3">
            @if($lote->quantidade_atual > 0)
                <a href="{{ route('lotes.saida', $lote) }}" 
                   class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                    Registrar Saída
                </a>
            @endif
            <a href="{{ route('lotes.edit', $lote) }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Editar
            </a>
            <a href="{{ route('lotes.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações do Lote</h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Produto:</span>
                        <p class="text-gray-900 font-semibold">{{ $lote->produto->nome }}</p>
                        <p class="text-sm text-gray-600">{{ $lote->produto->codigo }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Número do Lote:</span>
                        <p class="text-gray-900">{{ $lote->numero_lote }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Data de Entrada:</span>
                        <p class="text-gray-900">{{ $lote->data_entrada->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Data de Validade:</span>
                        <p class="text-gray-900">
                            @if($lote->data_validade)
                                {{ $lote->data_validade->format('d/m/Y') }}
                            @else
                                Indeterminada
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Quantidade:</span>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $lote->quantidade_atual }} / {{ $lote->quantidade_inicial }}
                        </p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ ($lote->quantidade_atual / $lote->quantidade_inicial) * 100 }}%"></div>
                        </div>
                    </div>

                    @if($lote->data_validade)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Status da Validade:</span>
                            @if($lote->estaVencido())
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Vencido há {{ abs($lote->dias_para_vencer) }} dias
                                </span>
                            @elseif($lote->estaProximoDeVencer())
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Vence em {{ $lote->dias_para_vencer }} dias
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Validade OK
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Histórico de Movimentações</h2>
                </div>
                <div class="p-6">
                    @if($lote->movimentacoes->count() > 0)
                        <div class="space-y-3">
                            @foreach($lote->movimentacoes as $movimentacao)
                                <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                                            {{ $movimentacao->tipo == 'entrada' ? 'bg-green-100' : 'bg-red-100' }}">
                                            <svg class="w-5 h-5 {{ $movimentacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($movimentacao->tipo == 'entrada')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ ucfirst($movimentacao->tipo) }}: {{ $movimentacao->quantidade }} unid.
                                            </p>
                                            @if($movimentacao->observacao)
                                                <p class="text-sm text-gray-600">{{ $movimentacao->observacao }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">
                                            {{ $movimentacao->data_movimentacao->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            Nenhuma movimentação registrada para este lote.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
