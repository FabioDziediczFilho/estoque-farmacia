@extends('layouts.app')

@section('title', 'Gestão de Usuários')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Título -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Gestão de Equipe</h1>
                <p class="text-slate-500 font-medium">Controle quem acessa e opera o sistema da S.M.S.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
                class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-teal-900/10 transition-all flex items-center gap-2">
                <i class="bi bi-person-plus-fill text-base"></i>
                Novo Usuário
            </a>
        </div>

        <!-- Tabela de Usuários -->
        <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/40 border border-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Operador</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">E-mail</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nível de Acesso</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-sms-gradient text-white flex items-center justify-center font-black text-xs shadow-lg shadow-emerald-900/10">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-slate-800">{{ $user->name }}</span>
                                            <small class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Membro desde {{ $user->created_at->format('M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-medium text-slate-600">{{ $user->email }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" 
                                            class="text-xs font-bold py-1.5 px-3 rounded-lg border-none bg-slate-100 focus:ring-2 focus:ring-[#006266]/20 transition-all {{ $user->role == 'admin' ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600' }}">
                                            <option value="operador" {{ $user->role == 'operador' ? 'selected' : '' }}>Operador</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Coordenador</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                            class="p-2 text-teal-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>

                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este acesso?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                    <i class="bi bi-trash3-fill text-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">Sua Conta</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Alerta de Segurança -->
        <div class="p-6 bg-[#006266]/5 border border-[#006266]/10 rounded-[32px] flex gap-4 items-start shadow-sm">
            <div class="p-3 bg-white rounded-2xl shadow-sm text-[#006266]">
                <i class="bi bi-shield-lock-fill text-xl"></i>
            </div>
            <div class="space-y-1">
                <h5 class="text-sm font-bold text-[#006266]">Protocolo de Segurança</h5>
                <p class="text-xs text-slate-500 leading-relaxed max-w-2xl">
                    Alterações nos níveis de acesso entram em vigor imediatamente. O nível <strong>Coordenador</strong> permite gerenciar usuários, deletar registros e acessar relatórios financeiros, enquanto o <strong>Operador</strong> foca na movimentação física do estoque.
                </p>
            </div>
        </div>
    </div>
@endsection
