@extends('layouts.app')

@section('title', 'Importação de Dados')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Importação de Dados</h1>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
            ← Voltar ao Dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Instruções de Importação</h2>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <h3 class="font-medium text-blue-800 mb-2">📋 Formato do Arquivo Excel:</h3>
            <p class="text-blue-700 text-sm mb-3">O arquivo deve seguir esta estrutura de colunas:</p>
            <div class="bg-white rounded p-3 text-sm font-mono">
                Coluna A: Código do Produto<br>
                Coluna B: Nome do Produto<br>
                Coluna C: Quantidade<br>
                Coluna D: Data de Validade (opcional)
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-medium text-yellow-800 mb-2">⚠️ Observações:</h3>
                <ul class="text-yellow-700 text-sm space-y-1">
                    <li>• A primeira linha pode ser cabeçalho (será ignorada automaticamente)</li>
                    <li>• Códigos duplicados atualizarão o produto existente</li>
                    <li>• Data de validade pode ser "INDETERMINADA"</li>
                    <li>• Formatos de data aceitos: DD/MM/YYYY, DD/MM/YY, YYYY-MM-DD</li>
                    <li>• Tamanho máximo do arquivo: 10MB</li>
                </ul>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-medium text-green-800 mb-2">✅ O que será criado:</h3>
                <ul class="text-green-700 text-sm space-y-1">
                    <li>• Produto (se não existir)</li>
                    <li>• Lote automático para cada linha</li>
                    <li>• Movimentação de entrada</li>
                    <li>• Data de entrada será a data da importação</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Selecionar Arquivo</h2>
        
        <form action="{{ route('importacao.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="arquivo" class="block text-sm font-medium text-gray-700 mb-2">
                    Arquivo Excel (.xlsx, .xls, .csv) <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       id="arquivo" 
                       name="arquivo" 
                       accept=".xlsx,.xls,.csv"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('arquivo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Importar Dados
                </button>
            </div>
        </form>
    </div>

    @if(session('import_errors'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-6">
            <h3 class="font-medium text-red-800 mb-2">❌ Erros na Importação:</h3>
            <div class="max-h-40 overflow-y-auto">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-gray-50 rounded-lg p-6 mt-6">
        <h3 class="font-medium text-gray-800 mb-3">📊 Exemplo de Planilha:</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm">CÓDIGO</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm">PRODUTO</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm">QUANTIDADE</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm">DATA DE VALIDADE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-sm">001</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">CHÁ MATE 81</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">50</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">15/12/2025</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-sm">002</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">FILTRO DE CAFÉ C/30</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">25</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">INDETERMINADA</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-sm">003</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">MAIONESE</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">10</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">20/01/2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
