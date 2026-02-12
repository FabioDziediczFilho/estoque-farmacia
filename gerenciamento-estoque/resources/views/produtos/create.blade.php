@extends('layouts.app')

@section('title', 'Novo Produto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Produto</h1>
        <a href="{{ route('produtos.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('produtos.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Dados do Produto -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        Dados do Produto
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ old('codigo') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('codigo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome do Produto <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('nome')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo" 
                                    name="tipo" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Selecione...</option>
                                <option value="item" {{ old('tipo') == 'item' ? 'selected' : '' }}>Item</option>
                                <option value="medicamento" {{ old('tipo') == 'medicamento' ? 'selected' : '' }}>Medicamento</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fabricante" class="block text-sm font-medium text-gray-700 mb-2">
                                Fabricante
                            </label>
                            <input type="text" 
                                   id="fabricante" 
                                   name="fabricante" 
                                   value="{{ old('fabricante') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('fabricante')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="local_armazenamento" class="block text-sm font-medium text-gray-700 mb-2">
                                Local de Armazenamento
                            </label>
                            <input type="text" 
                                   id="local_armazenamento" 
                                   name="local_armazenamento" 
                                   value="{{ old('local_armazenamento') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Ex: Armário A, Prateleira B, Geladeira...">
                            @error('local_armazenamento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dados do Lote -->
                <div>
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Lote Inicial
                        </h2>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="criar_lote" 
                                   name="criar_lote" 
                                   value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('criar_lote') ? 'checked' : '' }}>
                            <label for="criar_lote" class="ml-2 text-sm text-gray-700">
                                Adicionar lote inicial
                            </label>
                        </div>
                    </div>

                    <div id="lote-fields" class="space-y-4" style="display: {{ old('criar_lote') ? 'block' : 'none' }};">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-700">
                                <strong>Atenção:</strong> Preencha os dados abaixo para criar o primeiro lote deste produto com estoque inicial.
                            </p>
                        </div>

                        <div>
                            <label for="numero_lote" class="block text-sm font-medium text-gray-700 mb-2">
                                Número do Lote <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="numero_lote" 
                                   name="numero_lote" 
                                   value="{{ old('numero_lote') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Ex: LOT001, 2024-001, etc.">
                            @error('numero_lote')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantidade_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantidade Inicial <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="quantidade_inicial" 
                                   name="quantidade_inicial" 
                                   value="{{ old('quantidade_inicial') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   min="1"
                                   placeholder="Quantidade de unidades">
                            @error('quantidade_inicial')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_validade" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Validade
                            </label>
                            <input type="date" 
                                   id="data_validade" 
                                   name="data_validade" 
                                   value="{{ old('data_validade') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   min="{{ now()->format('Y-m-d') }}">
                            <p class="mt-1 text-sm text-gray-500">Deixe em branco se a validade for indeterminada</p>
                            @error('data_validade')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_entrada" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Entrada <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="data_entrada" 
                                   name="data_entrada" 
                                   value="{{ old('data_entrada', now()->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('data_entrada')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="lote-placeholder" class="text-center py-12 text-gray-400" style="display: {{ old('criar_lote') ? 'none' : 'block' }};">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm">Marque "Adicionar lote inicial" para criar estoque</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('produtos.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Salvar Produto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const criarLoteCheckbox = document.getElementById('criar_lote');
    const loteFields = document.getElementById('lote-fields');
    const lotePlaceholder = document.getElementById('lote-placeholder');
    
    function toggleLoteFields() {
        if (criarLoteCheckbox.checked) {
            loteFields.style.display = 'block';
            lotePlaceholder.style.display = 'none';
        } else {
            loteFields.style.display = 'none';
            lotePlaceholder.style.display = 'block';
        }
    }
    
    criarLoteCheckbox.addEventListener('change', toggleLoteFields);
    
    // Gerar número de lote automático baseado no código do produto
    const codigoInput = document.getElementById('codigo');
    const numeroLoteInput = document.getElementById('numero_lote');
    
    codigoInput.addEventListener('blur', function() {
        if (this.value && !numeroLoteInput.value) {
            const ano = new Date().getFullYear();
            numeroLoteInput.value = `LOT-${this.value}-${ano}`;
        }
    });
});
</script>
@endsection
