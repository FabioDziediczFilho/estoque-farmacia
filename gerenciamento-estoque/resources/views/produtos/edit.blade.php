@extends('layouts.app')

@section('title', 'Editar Item')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho da Página -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Editar Detalhes</h1>
                <p class="text-slate-500 mt-1">Atualize as informações do item <span
                        class="text-teal-600 font-bold">#{{ $produto->codigo }}</span>.</p>
            </div>
            <a href="{{ route('produtos.index') }}"
                class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Cancelar e Voltar
            </a>
        </div>

        <!-- Formulário Principal 
             Professor: Em edição, mantemos a mesma estrutura de criação para não confundir o usuário. -->
        <form action="{{ route('produtos.update', $produto) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                <!-- Coluna: Dados do Produto -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                            <i data-lucide="edit-3" class="w-5 h-5 mr-2 text-teal-600"></i>
                            Dados Cadastrais
                        </h2>

                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="codigo"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Código <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="codigo" name="codigo"
                                        value="{{ old('codigo', $produto->codigo) }}" required
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                                    @error('codigo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="tipo"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Categoria <span class="text-rose-500">*</span>
                                    </label>
                                    <select id="tipo" name="tipo" required
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                                        <option value="geral" {{ old('tipo', $produto->tipo) == 'geral' ? 'selected' : '' }}>
                                            Geral / Escritório</option>
                                        <option value="limpeza" {{ old('tipo', $produto->tipo) == 'limpeza' ? 'selected' : '' }}>Limpeza / Higiene</option>
                                        <option value="medicamento" {{ old('tipo', $produto->tipo) == 'medicamento' ? 'selected' : '' }}>Medicamento / Saúde</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="nome"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Nome do Produto <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                                @error('nome') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="fabricante"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Marca / Fabricante
                                    </label>
                                    <input type="text" id="fabricante" name="fabricante"
                                        value="{{ old('fabricante', $produto->fabricante) }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                                </div>
                                <div>
                                    <label for="local_armazenamento"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Local de Armazenamento
                                    </label>
                                    <input type="text" id="local_armazenamento" name="local_armazenamento"
                                        value="{{ old('local_armazenamento', $produto->local_armazenamento) }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna: Ações e Resumo -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Card de Status Rápido 
                         Professor: Mostra ao usuário o estado atual antes dele salvar. -->
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 {{ $produto->status_estoque['dot'] }} rounded-full flex items-center justify-center text-white mb-4 shadow-lg shadow-slate-100">
                            <i data-lucide="package" class="w-8 h-8"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Estoque Atual</h3>
                        <p class="text-3xl font-black text-slate-900 my-2">{{ $produto->quantidade_total }}</p>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $produto->status_estoque['color'] }}">
                            {{ $produto->status_estoque['label'] }}
                        </span>

                        <p class="text-xs text-slate-400 mt-6 leading-relaxed">
                            <strong>Lembrete:</strong> Lotes são editados separadamente no módulo de Lotes por questões de
                            segurança e rastreabilidade.
                        </p>
                    </div>

                    <div class="flex flex-col space-y-3">
                        <button type="submit"
                            class="w-full py-4 bg-slate-900 hover:bg-black text-white font-bold rounded-2xl shadow-lg transition-all flex items-center justify-center">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            Salvar Alterações
                        </button>
                        <a href="{{ route('produtos.index') }}"
                            class="w-full py-4 text-center text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                            Descartar Mudanças
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection