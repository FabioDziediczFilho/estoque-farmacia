@extends('layouts.app')

@section('title', 'Novo Lote')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Lote</h1>
        <a href="{{ route('lotes.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('lotes.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="produto_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Produto <span class="text-red-500">*</span>
                </label>
                <select id="produto_id" 
                        name="produto_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Selecione um produto...</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                            {{ $produto->nome }} ({{ $produto->codigo }})
                        </option>
                    @endforeach
                </select>
                @error('produto_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="numero_lote" class="block text-sm font-medium text-gray-700 mb-2">
                    Número do Lote <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="numero_lote" 
                       name="numero_lote" 
                       value="{{ old('numero_lote') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Ex: LOT001, 2024-001, etc."
                       required>
                @error('numero_lote')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                           required>
                    @error('quantidade_inicial')
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data_entrada')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
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

            <div class="flex justify-end space-x-3">
                <a href="{{ route('lotes.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Criar Lote
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
