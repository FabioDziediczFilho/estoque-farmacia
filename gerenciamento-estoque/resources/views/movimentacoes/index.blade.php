@extends('layouts.app')

@section('title', 'Movimentações')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Movimentações</h1>
    <a href="{{ route('movimentacoes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Nova Movimentação
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h2>
    <form method="GET" action="{{ route('movimentacoes.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select id="tipo" name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos</option>
                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                </select>
            </div>
            
            <div>
                <label for="produto_id" class="block text-sm font-medium text-gray-700 mb-2">Produto</label>
                <select id="produto_id" name="produto_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}" {{ request('produto_id') == $produto->id ? 'selected' : '' }}>
                            {{ $produto->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            
            <div>
                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
        
        <div class="mt-4 flex justify-end space-x-3">
            <a href="{{ route('movimentacoes.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Limpar Filtros
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Lista de Movimentações -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Data/Hora
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Tipo
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Produto
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Lote
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Quantidade
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Observação
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($movimentacoes as $movimentacao)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $movimentacao->data_movimentacao->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $movimentacao->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($movimentacao->tipo) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>
                            <div class="font-medium">{{ $movimentacao->lote->produto->nome }}</div>
                            <div class="text-gray-500">{{ $movimentacao->lote->produto->codigo }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $movimentacao->lote->numero_lote }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-lg font-semibold {{ $movimentacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $movimentacao->tipo == 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $movimentacao->observacao ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('movimentacoes.show', $movimentacao) }}" 
                           class="text-blue-600 hover:text-blue-900">
                            Ver Detalhes
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Nenhuma movimentação encontrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $movimentacoes->links() }}
</div>
@endsection
