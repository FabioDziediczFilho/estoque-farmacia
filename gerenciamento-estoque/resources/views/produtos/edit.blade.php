@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Produto</h1>
        <a href="{{ route('produtos.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('produtos.update', $produto) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                    Código <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="codigo" 
                       name="codigo" 
                       value="{{ old('codigo', $produto->codigo) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('codigo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Produto <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nome" 
                       name="nome" 
                       value="{{ old('nome', $produto->nome) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('nome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo <span class="text-red-500">*</span>
                </label>
                <select id="tipo" 
                        name="tipo" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Selecione...</option>
                    <option value="item" {{ old('tipo', $produto->tipo) == 'item' ? 'selected' : '' }}>Item</option>
                    <option value="medicamento" {{ old('tipo', $produto->tipo) == 'medicamento' ? 'selected' : '' }}>Medicamento</option>
                </select>
                @error('tipo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="fabricante" class="block text-sm font-medium text-gray-700 mb-2">
                    Fabricante
                </label>
                <input type="text" 
                       id="fabricante" 
                       name="fabricante" 
                       value="{{ old('fabricante', $produto->fabricante) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('fabricante')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="local_armazenamento" class="block text-sm font-medium text-gray-700 mb-2">
                    Local de Armazenamento
                </label>
                <input type="text" 
                       id="local_armazenamento" 
                       name="local_armazenamento" 
                       value="{{ old('local_armazenamento', $produto->local_armazenamento) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Ex: Armário A, Prateleira B, Geladeira...">
                @error('local_armazenamento')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('produtos.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Atualizar Produto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
