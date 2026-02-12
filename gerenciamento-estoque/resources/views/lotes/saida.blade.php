@extends('layouts.app')

@section('title', 'Registrar Saída')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Registrar Saída</h1>
        <a href="{{ route('lotes.show', $lote) }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações do Lote</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-sm font-medium text-gray-500">Produto:</span>
                <p class="text-gray-900 font-semibold">{{ $lote->produto->nome }}</p>
                <p class="text-sm text-gray-600">{{ $lote->produto->codigo }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">Lote:</span>
                <p class="text-gray-900 font-semibold">{{ $lote->numero_lote }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">Quantidade Disponível:</span>
                <p class="text-2xl font-bold text-blue-600">{{ $lote->quantidade_atual }} unid.</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">Validade:</span>
                <p class="text-gray-900">
                    @if($lote->data_validade)
                        {{ $lote->data_validade->format('d/m/Y') }}
                    @else
                        Indeterminada
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Dados da Saída</h2>
        
        <form action="{{ route('lotes.storeSaida', $lote) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-2">
                    Quantidade para Saída <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="quantidade" 
                       name="quantidade" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       min="1" 
                       max="{{ $lote->quantidade_atual }}"
                       required>
                <p class="mt-1 text-sm text-gray-500">
                    Máximo disponível: {{ $lote->quantidade_atual }} unidades
                </p>
                @error('quantidade')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">
                    Observação
                </label>
                <textarea id="observacao" 
                          name="observacao" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Motivo da saída, destino, etc..."></textarea>
                <p class="mt-1 text-sm text-gray-500">Informações adicionais sobre esta movimentação</p>
                @error('observacao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Atenção!</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Esta operação irá reduzir permanentemente o estoque do lote. 
                            A quantidade disponível após a saída será: 
                            <span class="font-semibold">{{ $lote->quantidade_atual }} - [quantidade informada] = {{ $lote->quantidade_atual }} unidades restantes</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('lotes.show', $lote) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700"
                        onclick="return confirm('Tem certeza que deseja registrar esta saída?')">
                    Confirmar Saída
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
