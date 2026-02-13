@extends('layouts.app')

@section('title', 'Nova Movimentação')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Registrar Movimentação</h1>
                <p class="text-slate-500 mt-1">Controle de entrada e saída de materiais do estoque.</p>
            </div>
            <a href="{{ route('movimentacoes.index') }}"
                class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Ver Histórico
            </a>
        </div>

        <!-- Professor Explica: 
                         Para evitar erros de digitação em listas grandes, usamos o 'Tom Select'.
                         Ele transforma um <select> comum em um campo de busca inteligente. -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <form action="{{ route('movimentacoes.store') }}" method="POST" id="form-movimentacao">
                @csrf
                <input type="hidden" name="multiplo" value="1">
                <input type="hidden" name="itens" id="input-itens" value="[]">

                <!-- Parte 1: Cabeçalho da Operação (Unidade e Responsável) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 pb-10 border-b border-slate-50">
                    <div class="md:col-span-1">
                        <label for="tipo" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Tipo de Operação <span class="text-rose-500">*</span>
                        </label>
                        <select id="tipo" name="tipo" required
                            class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all font-bold text-teal-700">
                            <option value="saida" selected>Saída (-)</option>
                            <option value="entrada">Entrada (+)</option>
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label for="unidade_id"
                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex justify-between items-center">
                            <span>Unidade de Saúde / Destino <span class="text-rose-500">*</span></span>
                            <a href="{{ route('unidades.index') }}" target="_blank"
                                class="text-teal-600 hover:text-teal-700 transition-colors" title="Cadastrar nova unidade">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            </a>
                        </label>
                        <select id="unidade_id" name="unidade_id" required
                            class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                            <option value="">Selecione a unidade...</option>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}" data-resp="{{ $unidade->responsavel_padrao }}" {{ old('unidade_id') == $unidade->id ? 'selected' : '' }}>
                                    {{ $unidade->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label for="responsavel"
                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Responsável (Recebedor) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="responsavel" name="responsavel" value="{{ old('responsavel') }}" required
                            class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                            placeholder="Ex: Enf. Maria Silva">
                    </div>

                    <div class="md:col-span-2">
                        <label for="observacao"
                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Observações Gerais
                        </label>
                        <input type="text" id="observacao" name="observacao" value="{{ old('observacao') }}"
                            class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all"
                            placeholder="Motivo da saída, placa do veículo, etc (Opcional)">
                    </div>

                    <div class="md:col-span-1">
                        <label for="data_movimentacao"
                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Data e Hora
                        </label>
                        <input type="datetime-local" id="data_movimentacao" name="data_movimentacao"
                            value="{{ old('data_movimentacao', now()->format('Y-m-d\TH:i')) }}" required
                            class="w-full px-4 py-3 bg-slate-50 border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm transition-all">
                    </div>
                </div>

                <!-- Parte 2: Seletor de Itens (O Carrinho) -->
                <div class="bg-slate-50/50 rounded-3xl p-6 md:p-8 border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center">
                        <i data-lucide="shopping-cart" class="w-4 h-4 mr-2 text-teal-600"></i>
                        Adicionar Medicamentos / Materiais
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end mb-8">
                        <div class="md:col-span-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Pesquisar Item no
                                Estoque</label>
                            <select id="selector-item" placeholder="Digite o nome ou código do produto...">
                                <option value="">Selecione um produto...</option>
                                @foreach($lotes as $lote)
                                    <option value="{{ $lote->id }}" data-nome="{{ $lote->produto->nome }}"
                                        data-lote="{{ $lote->numero_lote }}" data-max="{{ $lote->quantidade_atual }}">
                                        {{ $lote->produto->nome }} (Lote: {{ $lote->numero_lote }}) - Disponível:
                                        {{ $lote->quantidade_atual }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Qtd.</label>
                            <input type="number" id="selector-qtd" min="1"
                                class="w-full px-4 py-3 bg-white border-none focus:ring-2 focus:ring-teal-500 rounded-xl text-sm shadow-sm transition-all"
                                placeholder="0">
                        </div>
                        <div class="md:col-span-2">
                            <button type="button" id="btn-add-item"
                                class="w-full py-3.5 bg-slate-900 hover:bg-black text-white rounded-xl shadow-lg transition-all flex items-center justify-center">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Itens Adicionados -->
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Item / Medicamento</th>
                                    <th class="px-6 py-4 text-center">Lote</th>
                                    <th class="px-6 py-4 text-center">Quantidade</th>
                                    <th class="px-6 py-4 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody id="lista-carrinho" class="divide-y divide-slate-50">
                                <!-- Injetado via JS -->
                                <tr id="row-empty">
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                        Nenhum item adicionado à lista.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rodapé do Card -->
                <div class="mt-10 pt-8 border-t border-slate-50 flex items-center justify-between">
                    <div id="resumo-carrinho" class="hidden">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total na Lista:</span>
                        <span id="total-itens"
                            class="ml-2 px-3 py-1 bg-teal-50 text-teal-700 rounded-full font-black text-sm">0 itens</span>
                    </div>
                    <div class="flex gap-3 ml-auto">
                        <a href="{{ route('movimentacoes.index') }}"
                            class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg shadow-teal-50 transition-all flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                            Confirmar Dispensação
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            // Carrinho em memória
            let carrinho = [];

            // Inicializar Tom Select
            const tomItem = new TomSelect("#selector-item", {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });

            const unidadeSelect = document.getElementById('unidade_id');
            const responsavelInput = document.getElementById('responsavel');
            const btnAddItem = document.getElementById('btn-add-item');
            const inputQtd = document.getElementById('selector-qtd');
            const inputItensHidden = document.getElementById('input-itens');
            const tabelaCorpo = document.getElementById('lista-carrinho');
            const resumoArea = document.getElementById('resumo-carrinho');
            const totalLabel = document.getElementById('total-itens');

            // Auto-preencher responsável ao mudar unidade
            unidadeSelect.addEventListener('change', function () {
                const option = this.options[this.selectedIndex];
                if (option.dataset.resp) {
                    responsavelInput.value = option.dataset.resp;
                }
            });

            // Adicionar item ao carrinho
            btnAddItem.addEventListener('click', function () {
                const loteId = tomItem.getValue();
                const qtd = parseInt(inputQtd.value);

                if (!loteId) {
                    alert('Por favor, selecione um produto.');
                    return;
                }

                if (isNaN(qtd) || qtd <= 0) {
                    alert('Informe uma quantidade válida.');
                    return;
                }

                // Pegar dados do item direto do TomSelect / Select original
                const origOption = document.querySelector(`#selector-item option[value="${loteId}"]`);
                const maxDisponivel = parseInt(origOption.dataset.max);
                const nomeProduto = origOption.dataset.nome;
                const numeroLote = origOption.dataset.lote;

                if (qtd > maxDisponivel) {
                    alert(`Quantidade indisponível! Estoque atual: ${maxDisponivel}`);
                    return;
                }

                // Verificar se já existe no carrinho para somar
                const indexExistente = carrinho.findIndex(i => i.lote_id === loteId);
                if (indexExistente > -1) {
                    const novaQtd = carrinho[indexExistente].quantidade + qtd;
                    if (novaQtd > maxDisponivel) {
                        alert(`Soma ultrapassa o estoque disponível (${maxDisponivel})`);
                        return;
                    }
                    carrinho[indexExistente].quantidade = novaQtd;
                } else {
                    carrinho.push({
                        lote_id: loteId,
                        nome: nomeProduto,
                        lote: numeroLote,
                        quantidade: qtd
                    });
                }

                inputQtd.value = '';
                tomItem.clear();
                renderCarrinho();
            });

            function renderCarrinho() {
                if (carrinho.length === 0) {
                    tabelaCorpo.innerHTML = `
                                <tr id="row-empty">
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                        Nenhum item adicionado à lista.
                                    </td>
                                </tr>`;
                    resumoArea.classList.add('hidden');
                } else {
                    tabelaCorpo.innerHTML = '';
                    carrinho.forEach((item, index) => {
                        tabelaCorpo.innerHTML += `
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-700">${item.nome}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-xs text-slate-500">
                                            ${item.lote}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-slate-100 px-3 py-1 rounded-lg font-black text-slate-700">${item.quantidade}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" onclick="removerItem(${index})" class="p-2 text-rose-300 hover:text-rose-600 transition-colors">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                    });
                    resumoArea.classList.remove('hidden');
                    totalLabel.innerText = `${carrinho.length} ${carrinho.length === 1 ? 'item' : 'itens'}`;
                }

                // Sincronizar campo oculto
                inputItensHidden.value = JSON.stringify(carrinho);
            }

            // Função global para remover item
            window.removerItem = function (index) {
                carrinho.splice(index, 1);
                renderCarrinho();
            };
        });
    </script>
@endsection