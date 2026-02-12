@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total de Produtos</h3>
        <p class="text-3xl font-bold text-blue-600">{{ App\Models\Produto::count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Lotes Ativos</h3>
        <p class="text-3xl font-bold text-green-600">{{ App\Models\Lote::where('quantidade_atual', '>', 0)->count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Produtos Próximos ao Vencimento</h3>
        <p class="text-3xl font-bold text-red-600">
            {{ App\Models\Lote::whereNotNull('data_validade')
                ->where('data_validade', '<=', now()->addDays(30))
                ->where('quantidade_atual', '>', 0)
                ->count() }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">Produtos com Estoque Baixo</h3>
        </div>
        <div class="p-6">
            @php
                $produtosBaixoEstoque = App\Models\Produto::with('lotes')
                    ->get()
                    ->filter(function($produto) {
                        return $produto->quantidade_total <= 5 && $produto->quantidade_total > 0;
                    })
                    ->take(5);
            @endphp
            
            @if($produtosBaixoEstoque->count() > 0)
                <div class="space-y-3">
                    @foreach($produtosBaixoEstoque as $produto)
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $produto->nome }}</span>
                            <span class="text-yellow-600 font-semibold">{{ $produto->quantidade_total }} unid.</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Nenhum produto com estoque baixo no momento.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">Alertas de Validade</h3>
        </div>
        <div class="p-6">
            @php
                $lotesVencendo = App\Models\Lote::with('produto')
                    ->whereNotNull('data_validade')
                    ->where('data_validade', '<=', now()->addDays(30))
                    ->where('quantidade_atual', '>', 0)
                    ->orderBy('data_validade')
                    ->take(5)
                    ->get();
            @endphp
            
            @if($lotesVencendo->count() > 0)
                <div class="space-y-3">
                    @foreach($lotesVencendo as $lote)
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $lote->produto->nome }}</span>
                                <span class="text-sm text-gray-500 ml-2">Lote: {{ $lote->numero_lote }}</span>
                            </div>
                            <div class="text-right">
                                @if($lote->estaVencido())
                                    <span class="text-red-600 font-semibold">Vencido há {{ abs($lote->dias_para_vencer) }} dias</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">{{ $lote->dias_para_vencer }} dias</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Nenhum produto próximo do vencimento.</p>
            @endif
        </div>
    </div>
</div>
@endsection
