@extends('layouts.app_guest')

@section('title', 'Acesso Restrito')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-6 bg-sms-gradient">
        <div class="w-full max-w-md animate-in zoom-in-95 duration-500">
            <!-- Logo/Branding -->
            <div class="text-center mb-10 text-white">
                <div class="inline-flex p-5 bg-white/10 rounded-3xl border border-white/20 shadow-2xl mb-6">
                    <i class="bi bi-hospital-fill text-5xl"></i>
                </div>
                <h1 class="text-4xl font-black tracking-tight leading-none">S.M.S.</h1>
                <p class="text-white/70 mt-2 font-bold uppercase tracking-[0.3em] text-[11px]">Porto Amazonas</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-[40px] shadow-2xl overflow-hidden border border-white">
                <div class="p-10">
                    <div class="mb-10">
                        <h2 class="text-2xl font-black text-slate-800">Login</h2>
                        <p class="text-sm text-slate-400 font-medium">Acesse o sistema de gestão de estoque.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div
                            class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs rounded-2xl font-bold">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">E-mail
                                Institucional</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#006266] transition-colors">
                                    <i class="bi bi-envelope-fill text-lg"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:bg-white focus:ring-[6px] focus:ring-[#006266]/5 focus:border-[#006266] transition-all outline-none"
                                    placeholder="seu@email.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-tight ml-1">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Senha -->
                        <div>
                            <div class="flex items-center justify-between mb-2 ml-1">
                                <label for="password"
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Senha de
                                    Acesso</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-[10px] font-black text-[#006266] hover:text-[#009432] uppercase tracking-widest">Esqueceu?</a>
                                @endif
                            </div>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#006266] transition-colors">
                                    <i class="bi bi-lock-fill text-lg"></i>
                                </div>
                                <input id="password" type="password" name="password" required
                                    class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:bg-white focus:ring-[6px] focus:ring-[#006266]/5 focus:border-[#006266] transition-all outline-none"
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-tight ml-1">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Lembrar -->
                        <div class="flex items-center ml-1">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember"
                                    class="w-5 h-5 border-slate-200 rounded-lg text-[#006266] focus:ring-[#006266]/20 transition-all">
                                <span
                                    class="ml-3 text-xs font-bold text-slate-500 group-hover:text-slate-700 transition-colors uppercase tracking-wider">Manter
                                    Conectado</span>
                            </label>
                        </div>

                        <!-- Botão Entrar -->
                        <button type="submit"
                            class="w-full py-5 bg-[#006266] hover:bg-[#009432] text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-900/10 transition-all flex items-center justify-center gap-3">
                            Acessar Sistema
                            <i class="bi bi-arrow-right-short text-xl"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer do Card (Informativo) -->
                <div class="p-8 bg-slate-50 border-t border-slate-100 text-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">Acesso restrito à equipe
                        autorizada da S.M.S.</span>
                </div>
            </div>

            <p class="mt-8 text-center text-[10px] text-white/40 font-bold uppercase tracking-[0.2em]">S.M.S. Porto Amazonas
                &copy; {{ date('Y') }}</p>
        </div>
    </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
@endsection