@extends('layouts.app')

@section('title', 'Nova Movimentação')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nova Movimentação</h1>
        <a href="{{ route('movimentacoes.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('movimentacoes.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="lote_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Lote <span class="text-red-500">*</span>
                </label>
                <select id="lote_id" 
                        name="lote_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Selecione um lote...</option>
                    @foreach($lotes as $lote)
                        <option value="{{ $lote->id }}" 
                                data-quantidade="{{ $lote->quantidade_atual }}"
                                {{ old('lote_id') == $lote->id ? 'selected' : '' }}>
                            {{ $lote->produto->nome }} - Lote: {{ $lote->numero_lote }} 
                            (Disponível: {{ $lote->quantidade_atual }})
                        </option>
                    @endforeach
                </select>
                @error('lote_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Movimentação <span class="text-red-500">*</span>
                </label>
                <select id="tipo" 
                        name="tipo" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Selecione...</option>
                    <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                </select>
                @error('tipo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantidade <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="quantidade" 
                           name="quantidade" 
                           value="{{ old('quantidade') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1"
                           required>
                    @error('quantidade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_movimentacao" class="block text-sm font-medium text-gray-700 mb-2">
                        Data/Hora <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" 
                           id="data_movimentacao" 
                           name="data_movimentacao" 
                           value="{{ old('data_movimentacao', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data_movimentacao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">
                    Observação
                </label>
                <textarea id="observacao" 
                          name="observacao" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Motivo da movimentação, destino, etc...">{{ old('observacao') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Informações adicionais sobre esta movimentação</p>
                @error('observacao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6" id="aviso-saida" style="display: none;">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Atenção!</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Esta saída irá reduzir permanentemente o estoque do lote selecionado.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('movimentacoes.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Registrar Movimentação
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const loteSelect = document.getElementById('lote_id');
    const avisoSaida = document.getElementById('aviso-saida');
    
    function toggleAvisoSaida() {
        if (tipoSelect.value === 'saida') {
            avisoSaida.style.display = 'block';
        } else {
            avisoSaida.style.display = 'none';
        }
    }
    
    function updateQuantidadeMax() {
        const selectedOption = loteSelect.options[loteSelect.selectedIndex];
        const quantidadeMax = selectedOption ? selectedOption.dataset.quantidade : null;
        
        if (quantidadeMax && tipoSelect.value === 'saida') {
            document.getElementById('quantidade').max = quantidadeMax;
        } else {
            document.getElementById('quantidade').removeAttribute('max');
        }
    }
    
    tipoSelect.addEventListener('change', function() {
        toggleAvisoSaida();
        updateQuantidadeMax();
    });
    
    loteSelect.addEventListener('change', updateQuantidadeMax);
    
    // Inicializar
    toggleAvisoSaida();
    updateQuantidadeMax();
});
</script>
@endsection
