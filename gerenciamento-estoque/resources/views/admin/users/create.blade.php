@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <div class="max-w-4xl mx-auto animate-in fade-in slide-in-from-bottom-4 duration-500">
        <!-- Cabeçalho -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Novo Operador</h1>
                <p class="text-slate-500 font-medium">Cadastre um novo funcionário para acessar o sistema.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center text-sm font-bold text-slate-400 hover:text-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/40 border border-white p-10">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Informações Básicas -->
                    <div class="space-y-8">
                        <div>
                            <label for="name"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Nome
                                Completo</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-6 py-4 bg-slate-50 border-none focus:ring-2 focus:ring-[#006266]/20 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                                placeholder="Ex: Maria Oliveira">
                            @error('name') <p class="mt-2 text-[10px] text-rose-500 font-bold uppercase tracking-wider">
                            {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">E-mail
                                de Acesso</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-6 py-4 bg-slate-50 border-none focus:ring-2 focus:ring-[#006266]/20 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                                placeholder="maria@sms.gov.br">
                            @error('email') <p class="mt-2 text-[10px] text-rose-500 font-bold uppercase tracking-wider">
                            {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="role"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Nível de
                                Permissão</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label
                                    class="relative flex flex-col p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="radio" name="role" value="operador" class="sr-only peer" checked>
                                    <span
                                        class="text-xs font-bold text-slate-700 peer-checked:text-[#006266]">Operador</span>
                                    <span class="text-[9px] text-slate-400 mt-1">Uso diário do estoque</span>
                                    <div
                                        class="absolute top-4 right-4 w-2 h-2 rounded-full border-2 border-slate-300 peer-checked:bg-[#006266] peer-checked:border-[#006266]">
                                    </div>
                                </label>
                                <label
                                    class="relative flex flex-col p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="radio" name="role" value="admin" class="sr-only peer">
                                    <span
                                        class="text-xs font-bold text-slate-700 peer-checked:text-[#006266]">Coordenador</span>
                                    <span class="text-[9px] text-slate-400 mt-1">Gestão e Relatórios</span>
                                    <div
                                        class="absolute top-4 right-4 w-2 h-2 rounded-full border-2 border-slate-300 peer-checked:bg-[#006266] peer-checked:border-[#006266]">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Segurança -->
                    <div class="space-y-8 bg-slate-50/50 p-8 rounded-[32px] border border-slate-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-[#006266]">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Segurança</h3>
                        </div>

                        <div>
                            <label for="password"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Senha
                                Temporária</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-6 py-4 bg-white border-none focus:ring-2 focus:ring-[#006266]/20 rounded-2xl text-sm font-bold text-slate-700 transition-all">
                            @error('password') <p class="mt-2 text-[10px] text-rose-500 font-bold uppercase tracking-wider">
                            {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Confirmar
                                Senha</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-6 py-4 bg-white border-none focus:ring-2 focus:ring-[#006266]/20 rounded-2xl text-sm font-bold text-slate-700 transition-all">
                        </div>

                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <p class="text-[10px] text-slate-400 leading-relaxed italic">
                                **Nota:** Recomende ao usuário que altere sua senha no primeiro acesso através das
                                configurações de perfil.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50 flex items-center justify-end gap-3">
                    <button type="submit"
                        class="px-10 py-5 bg-teal-600 hover:bg-teal-700 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-teal-900/10 transition-all flex items-center gap-3">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Confirmar Cadastro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection