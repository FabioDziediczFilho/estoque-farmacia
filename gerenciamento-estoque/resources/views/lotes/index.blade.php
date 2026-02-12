@extends('layouts.app')

@section('title', 'Lotes')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Lotes</h1>
        <a href="{{ route('lotes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Novo Lote
        </a>
    </div>
    
    <!-- Barra de Pesquisa -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('lotes.index') }}">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Buscar por nome do produto ou número do lote..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('lotes.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Produto
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Número do Lote
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Quantidade
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Data de Entrada
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Validade
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($lotes as $lote)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $lote->produto->nome }}</div>
                            <div class="text-sm text-gray-500">{{ $lote->produto->codigo }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $lote->numero_lote }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $lote->quantidade_atual }} / {{ $lote->quantidade_inicial }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ round(($lote->quantidade_atual / $lote->quantidade_inicial) * 100, 1) }}% restante
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $lote->data_entrada->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($lote->data_validade)
                            {{ $lote->data_validade->format('d/m/Y') }}
                        @else
                            <span class="text-gray-400">Indeterminada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($lote->quantidade_atual == 0)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Esgotado
                            </span>
                        @elseif($lote->data_validade && $lote->estaVencido())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Vencido
                            </span>
                        @elseif($lote->data_validade && $lote->estaProximoDeVencer())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $lote->dias_para_vencer }} dias
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                OK
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('lotes.show', $lote) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                        <a href="{{ route('lotes.edit', $lote) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                        @if($lote->quantidade_atual > 0)
                            <a href="{{ route('lotes.saida', $lote) }}" class="text-orange-600 hover:text-orange-900 mr-3">Saída</a>
                        @endif
                        <form action="{{ route('lotes.destroy', $lote) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                    onclick="return confirm('Tem certeza que deseja excluir este lote?')">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Nenhum lote cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $lotes->links() }}
</div>
@endsection
