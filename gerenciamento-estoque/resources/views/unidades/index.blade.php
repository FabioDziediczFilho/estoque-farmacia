@extends('layouts.app')

@section('title', 'Unidades de Saúde')

@section('content')
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <!-- Título -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Unidades de Saúde</h1>
                <p class="text-slate-500 font-medium mt-1">Gerencie os pontos de destino das dispensações de materiais.</p>
            </div>
            @if(Auth::user()->role === 'admin')
                <button onclick="openModal()"
                    class="px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-teal-900/10 transition-all flex items-center gap-3">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Nova Unidade
                </button>
            @endif
        </div>

        <!-- Lista de Unidades -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($unidades as $unidade)
                <div
                    class="bg-white rounded-[40px] p-10 shadow-xl shadow-slate-200/40 border-2 border-transparent hover:border-teal-500/20 transition-all group relative overflow-hidden">

                    <!-- Decoração de Fundo do Card -->
                    <div
                        class="absolute -right-10 -top-10 w-32 h-32 bg-teal-50 rounded-full opacity-50 group-hover:scale-110 transition-transform">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-8">
                            <div
                                class="w-16 h-16 rounded-3xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl shadow-sm border border-teal-100">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            @if(Auth::user()->role === 'admin')
                                <div class="flex gap-2">
                                    <button
                                        onclick="editUnidade({{ $unidade->id }}, '{{ $unidade->nome }}', '{{ $unidade->responsavel_padrao }}')"
                                        class="p-3 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-2xl transition-all"
                                        title="Editar">
                                        <i class="bi bi-pencil-square text-xl"></i>
                                    </button>
                                    <form action="{{ route('admin.unidades.destroy', $unidade) }}" method="POST"
                                        onsubmit="return confirm('ATENÇÃO: Deseja realmente remover esta unidade?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-3 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-2xl transition-all"
                                            title="Remover">
                                            <i class="bi bi-trash3-fill text-xl"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-4 uppercase tracking-tighter">
                            {{ $unidade->nome }}
                        </h3>

                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 w-fit">
                                <i class="bi bi-person-badge text-teal-500"></i>
                                {{ $unidade->responsavel_padrao ?: 'Sem Responsável Fixo' }}
                            </div>

                            <div class="pt-6 mt-6 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Histórico de
                                        Fluxo</span>
                                    <span class="text-xs font-bold text-slate-500">Ações registradas</span>
                                </div>
                                <span
                                    class="w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-xs font-black shadow-lg">
                                    {{ $unidade->movimentacoes_count ?? $unidade->movimentacoes()->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal (Simples para Adicionar/Editar) -->
        <div id="modal-unidade"
            class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-slate-950/50 backdrop-blur-md hidden">
            <div
                class="bg-white rounded-[48px] shadow-2xl w-full max-w-xl overflow-hidden border border-white animate-in zoom-in-95 duration-300">
                <div class="p-12">
                    <div class="flex justify-between items-center mb-12">
                        <div>
                            <h2 id="modal-title" class="text-3xl font-black text-slate-800 tracking-tight">Nova Unidade</h2>
                            <p class="text-slate-500 text-sm mt-1">Configure um novo destino para materiais.</p>
                        </div>
                        <button onclick="closeModal()"
                            class="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-2xl transition-all">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>

                    <form id="unidade-form" action="{{ route('unidades.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <div id="method-field"></div>

                        <div class="space-y-3">
                            <label
                                class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Identificação
                                do Ponto</label>
                            <input type="text" name="nome" id="input-nome" required
                                class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-50 rounded-3xl text-sm font-bold focus:bg-white focus:ring-[10px] focus:ring-teal-500/5 focus:border-teal-500 transition-all outline-none"
                                placeholder="Ex: UBS Centro - Dr. Arnaldo">
                        </div>

                        <div class="space-y-3">
                            <label
                                class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Responsável
                                pela Unidade</label>
                            <input type="text" name="responsavel_padrao" id="input-responsavel"
                                class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-50 rounded-3xl text-sm font-bold focus:bg-white focus:ring-[10px] focus:ring-teal-500/5 focus:border-teal-500 transition-all outline-none"
                                placeholder="Nome do profissional que recebe os materiais">
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" onclick="closeModal()"
                                class="flex-1 py-5 bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs font-black uppercase tracking-[0.2em] rounded-3xl transition-all">
                                Cancelar
                            </button>
                            <button type="submit" id="btn-submit"
                                class="flex-[2] py-5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-black uppercase tracking-[0.2em] rounded-3xl shadow-xl shadow-teal-900/10 transition-all">
                                Salvar Unidade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('modal-unidade');
            const form = document.getElementById('unidade-form');
            const title = document.getElementById('modal-title');
            const methodField = document.getElementById('method-field');
            const btnSubmit = document.getElementById('btn-submit');

            title.innerText = 'Nova Unidade';
            btnSubmit.innerText = 'Salvar Unidade';
            form.action = "{{ route('admin.unidades.store') }}";
            methodField.innerHTML = '';

            document.getElementById('input-nome').value = '';
            document.getElementById('input-responsavel').value = '';

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-unidade').classList.add('hidden');
        }

        function editUnidade(id, nome, responsavel) {
            const modal = document.getElementById('modal-unidade');
            const form = document.getElementById('unidade-form');
            const title = document.getElementById('modal-title');
            const methodField = document.getElementById('method-field');
            const btnSubmit = document.getElementById('btn-submit');

            title.innerText = 'Editar Unidade';
            btnSubmit.innerText = 'Atualizar Unidade';
            form.action = `/admin/unidades/${id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';

            document.getElementById('input-nome').value = nome;
            document.getElementById('input-responsavel').value = responsavel;

            modal.classList.remove('hidden');
        }

        window.onclick = function (event) {
            const modal = document.getElementById('modal-unidade');
            if (event.target == modal) {
                closeModal();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
@endsection