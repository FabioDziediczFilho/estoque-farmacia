@extends('layouts.app')

@section('title', 'Novo Item')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho da Página -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Cadastrar Novo Item</h1>
                <p class="text-slate-500 mt-1">Preencha as informações para adicionar um material ao inventário.</p>
            </div>
            <a href="{{ route('produtos.index') }}"
                class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Voltar para a Lista
            </a>
        </div>

        <!-- Formulário Principal 
                     Professor: Usamos grid para separar 'O Que é o item' de 'Onde ele entra' (Estoque). -->
        <form action="{{ route('produtos.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                <!-- Coluna 1: Dados do Produto (3 partes) -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                            <i data-lucide="info" class="w-5 h-5 mr-2 text-teal-600"></i>
                            Informações Básicas
                        </h2>

                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="codigo"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Código do Item <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" required
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                        placeholder="Ex: MAT-001">
                                    @error('codigo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="tipo"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Categoria <span class="text-rose-500">*</span>
                                    </label>
                                    <select id="tipo" name="tipo" required
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                                        <option value="geral" {{ old('tipo') == 'geral' ? 'selected' : '' }}>Geral /
                                            Escritório</option>
                                        <option value="limpeza" {{ old('tipo') == 'limpeza' ? 'selected' : '' }}>Limpeza /
                                            Higiene</option>
                                        <option value="medicamento" {{ old('tipo') == 'medicamento' ? 'selected' : '' }}>
                                            Medicamento / Saúde</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="nome"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Nome do Produto <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                    placeholder="Nome descritivo do item">
                                @error('nome') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="fabricante"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Marca / Fabricante
                                    </label>
                                    <input type="text" id="fabricante" name="fabricante" value="{{ old('fabricante') }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                        placeholder="Opcional">
                                </div>
                                <div>
                                    <label for="local_armazenamento"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Local de Armazenamento
                                    </label>
                                    <input type="text" id="local_armazenamento" name="local_armazenamento"
                                        value="{{ old('local_armazenamento') }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                                        placeholder="Ex: Almoxarifado Central">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna 2: Lote Inicial (2 partes) 
                             Professor: "Progressive Disclosure" - Só mostramos os campos se o usuário quiser adicionar estoque agora. -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center">
                                <i data-lucide="layers" class="w-5 h-5 mr-2 text-teal-600"></i>
                                Estoque Inicial
                            </h2>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="criar_lote" name="criar_lote" value="1" class="sr-only peer" {{ old('criar_lote') ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600 transition-colors">
                                </div>
                            </label>
                        </div>

                        <div id="lote-fields" class="{{ old('criar_lote') ? '' : 'hidden' }} space-y-4">

                            <div>
                                <label for="numero_lote"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Identificação do Lote <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="numero_lote" name="numero_lote" value="{{ old('numero_lote') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all @error('numero_lote') ring-2 ring-rose-500 @enderror"
                                    placeholder="Ex: LOTE-2024A">
                                @error('numero_lote') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="quantidade_inicial"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Quantidade <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" id="quantidade_inicial" name="quantidade_inicial"
                                        value="{{ old('quantidade_inicial') }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all @error('quantidade_inicial') ring-2 ring-rose-500 @enderror"
                                        placeholder="0">
                                    @error('quantidade_inicial') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="data_entrada"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Data de Entrada <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="data_entrada" name="data_entrada"
                                        value="{{ old('data_entrada', date('Y-m-d')) }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all @error('data_entrada') ring-2 ring-rose-500 @enderror">
                                    @error('data_entrada') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="data_validade"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Data de Validade
                                </label>
                                <input type="date" id="data_validade" name="data_validade"
                                    value="{{ old('data_validade') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                            </div>
                        </div>

                        <div id="lote-placeholder" class="{{ old('criar_lote') ? 'hidden' : '' }} py-12 text-center">
                            <div class="bg-slate-50 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="package-plus" class="w-6 h-6 text-slate-300"></i>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Ative a chave acima para<br>adicionar estoque
                                agora.</p>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col space-y-3">
                        <button type="submit"
                            class="w-full py-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-100 transition-all flex items-center justify-center">
                            <i data-lucide="check" class="w-5 h-5 mr-2"></i>
                            Confirmar Cadastro
                        </button>
                        <a href="{{ route('produtos.index') }}"
                            class="w-full py-4 text-center text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                            Cancelar Operação
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            const criarLoteCheckbox = document.getElementById('criar_lote');
            const loteFields = document.getElementById('lote-fields');
            const lotePlaceholder = document.getElementById('lote-placeholder');

            function toggleLoteFields() {
                if (criarLoteCheckbox.checked) {
                    loteFields.classList.remove('hidden');
                    lotePlaceholder.classList.add('hidden');
                } else {
                    loteFields.classList.add('hidden');
                    lotePlaceholder.classList.remove('hidden');
                }
            }

            criarLoteCheckbox.addEventListener('change', toggleLoteFields);

            // Auto-preenchimento do Lote (Dica do Professor para UX)
            const codigoInput = document.getElementById('codigo');
            const numeroLoteInput = document.getElementById('numero_lote');

            codigoInput.addEventListener('blur', function () {
                if (this.value && !numeroLoteInput.value) {
                    const data = new Date();
                    const sufixo = data.getFullYear().toString().substr(-2) + (data.getMonth() + 1).toString().padStart(2, '0');
                    numeroLoteInput.value = `LT-${this.value}-${sufixo}`;
                }
            });
        });
    </script>
@endsection