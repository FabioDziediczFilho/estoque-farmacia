@extends('layouts.app')

@section('title', 'Gestão de Itens')

@section('content')
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Itens em Estoque</h1>
            <p class="text-slate-500 mt-1">Gerencie produtos, categorias e níveis de armazenamento.</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.produtos.create') }}"
                class="flex items-center px-4 py-2 bg-teal-600 rounded-lg text-sm font-medium text-white hover:bg-teal-700 transition-colors shadow-md">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Adicionar Novo Item
            </a>
        </div>
        @endif
    </div>

    <!-- Search & Filters 
         Professor Note: Using a 'floating' card for search makes it feel more modern than a docked section. -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-2 mb-8">
        <form method="GET" action="{{ route('produtos.index') }}"
            class="flex flex-col md:flex-row items-stretch md:items-center gap-2">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por código, nome ou fabricante..."
                    class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
            </div>

            <button type="submit"
                class="bg-slate-900 text-white px-6 py-3 rounded-xl hover:bg-slate-800 transition-colors flex items-center justify-center font-medium">
                Pesquisar
            </button>

            @if(request('search'))
                <a href="{{ route('produtos.index') }}"
                    class="px-4 py-3 text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center text-sm font-medium">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                    Limpar
                </a>
            @endif
        </form>
    </div>

    <!-- Products Table Container
         Professor Note: 'overflow-hidden' + 'rounded-2xl' is the secret for that 'app' container look. -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Cód.</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Produto</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Tipo
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Fabricante</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Estoque
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($produtos as $produto)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td
                                class="px-6 py-4 text-sm font-mono text-slate-400 group-hover:text-teal-600 transition-colors leading-relaxed">
                                #{{ $produto->codigo }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $produto->nome }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $produto->local_armazenamento ?? 'Não especificado' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    // Professor's Dynamic Logic: Map types to modern badges
                                    $typeColors = [
                                        'medicamento' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'limpeza' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'geral' => 'bg-emerald-50 text-emerald-600 border-emerald-100'
                                    ];
                                    $color = $typeColors[$produto->tipo] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full border {{ $color }}">
                                    {{ $produto->tipo }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $produto->fabricante ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end">
                                    <span
                                        class="text-sm font-bold {{ $produto->quantidade_total <= 5 ? 'text-rose-600' : 'text-slate-900' }}">
                                        {{ $produto->quantidade_total }} unid.
                                    </span>
                                    <!-- Status Pulse Indicator 
                                             Professor Note: This 'pulse' is a micro-interaction that draws attention to critical states. -->
                                    @if($produto->quantidade_total <= 5)
                                        <span class="flex items-center text-[10px] font-bold text-rose-500 mt-1 uppercase">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Crítico
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-500 mt-1 uppercase">
                                            Garantido
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('produtos.show', $produto) }}"
                                        class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all"
                                        title="Ver Detalhes">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>

                                    @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.produtos.edit', $produto) }}"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                        title="Editar">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.produtos.destroy', $produto) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            onclick="return confirm('Excluir este item permanentemente?')" title="Excluir">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i data-lucide="package-search" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                                <p>Nenhum produto encontrado.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $produtos->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection