@extends('layouts.app')

@section('title', $produto->nome)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $produto->nome }}</h1>
        <div class="space-x-3">
            <a href="{{ route('produtos.edit', $produto) }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Editar
            </a>
            <a href="{{ route('produtos.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações do Produto</h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Código:</span>
                        <p class="text-gray-900">{{ $produto->codigo }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Nome:</span>
                        <p class="text-gray-900">{{ $produto->nome }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Tipo:</span>
                        <p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $produto->tipo == 'medicamento' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($produto->tipo) }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Fabricante:</span>
                        <p class="text-gray-900">{{ $produto->fabricante ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Local de Armazenamento:</span>
                        <p class="text-gray-900">{{ $produto->local_armazenamento ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Quantidade Total:</span>
                        <p class="text-2xl font-bold {{ $produto->quantidade_total <= 5 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $produto->quantidade_total }} unid.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Lotes e Movimentações</h2>
                </div>
                <div class="p-6">
                    @if($produto->lotes->count() > 0)
                        <div class="space-y-4">
                            @foreach($produto->lotes as $lote)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Lote: {{ $lote->numero_lote }}</h3>
                                            <p class="text-sm text-gray-600">
                                                Entrada: {{ $lote->data_entrada->format('d/m/Y') }}
                                                @if($lote->data_validade)
                                                    | Validade: {{ $lote->data_validade->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-800">
                                                {{ $lote->quantidade_atual }} / {{ $lote->quantidade_inicial }}
                                            </p>
                                            @if($lote->data_validade)
                                                @if($lote->estaVencido())
                                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Vencido</span>
                                                @elseif($lote->estaProximoDeVencer())
                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                                        {{ $lote->dias_para_vencer }} dias
                                                    </span>
                                                @else
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">OK</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($lote->movimentacoes->count() > 0)
                                        <div class="mt-3">
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Movimentações Recentes:</h4>
                                            <div class="space-y-1">
                                                @foreach($lote->movimentacoes->take(3) as $movimentacao)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="{{ $movimentacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ ucfirst($movimentacao->tipo) }}: {{ $movimentacao->quantidade }} unid.
                                                        </span>
                                                        <span class="text-gray-500">
                                                            {{ $movimentacao->data_movimentacao->format('d/m/Y H:i') }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            Este produto não possui lotes cadastrados.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
