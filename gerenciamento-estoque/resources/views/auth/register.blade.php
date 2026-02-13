@extends('layouts.app_guest')

@section('title', 'Criar Conta')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-6 bg-slate-50">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Criar Acesso</h1>
                <p class="text-slate-500 mt-2 font-medium">Cadastre-se para gerenciar o estoque.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Nome -->
                    <div>
                        <label for="name"
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nome
                            Completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none">
                        @error('name') <p class="mt-1 text-[11px] text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none">
                        @error('email') <p class="mt-1 text-[11px] text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password"
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Senha</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none">
                        @error('password') <p class="mt-1 text-[11px] text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Confirmar
                            Senha</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none">
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('login') }}"
                            class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Já
                            tenho conta</a>
                        <button type="submit"
                            class="px-8 py-3.5 bg-teal-500 hover:bg-teal-600 text-white text-sm font-black rounded-2xl shadow-lg shadow-teal-100 transition-all">
                            Finalizar Cadastro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection