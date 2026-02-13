@extends('layouts.app')

@section('title', 'Auditoria de Sistema')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Título -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Registro de Atividades</h1>
                <p class="text-slate-500 font-medium">Histórico completo de ações realizadas no sistema da S.M.S.</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-4 py-2 bg-white rounded-2xl shadow-sm border border-slate-100 text-xs font-bold text-slate-400">
                    <i class="bi bi-shield-check mr-2"></i>
                    Auditando {{ $logs->total() }} eventos
                </span>
            </div>
        </div>

        <!-- Tabela de Logs (Visualização Premium) -->
        <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/40 border border-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Data/Hora
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Usuário
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Ação</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Detalhes
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span
                                        class="text-xs font-bold text-slate-400 block">{{ $log->created_at->format('d/m/Y') }}</span>
                                    <span
                                        class="text-sm font-black text-slate-700">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-black text-[10px] text-slate-400 border border-slate-200">
                                            {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span
                                                class="block text-sm font-bold text-slate-800">{{ $log->user->name ?? 'Sistema' }}</span>
                                            <small
                                                class="text-[9px] text-slate-400 font-black uppercase tracking-wider">{{ $log->ip_address }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border
                                                        {{ $log->action == 'created' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                                        {{ $log->action == 'updated' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                                        {{ $log->action == 'deleted' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}
                                                    ">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-sm font-medium text-slate-600">{{ $log->description }}</p>

                                    @if($log->properties && (isset($log->properties['old']) || isset($log->properties['new'])))
                                        <button onclick="toggleDetails('details-{{ $log->id }}')"
                                            class="mt-2 text-[10px] font-black text-teal-600 uppercase tracking-widest hover:text-teal-800 flex items-center gap-1">
                                            <i class="bi bi-arrow-down-up"></i> Detalhes da Alteração
                                        </button>

                                        <div id="details-{{ $log->id }}"
                                            class="hidden mt-4 bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden animate-in fade-in duration-300">
                                            <table class="w-full text-[10px] text-left border-collapse">
                                                <thead>
                                                    <tr class="bg-slate-100/50">
                                                        <th class="px-4 py-2 font-black text-slate-400 uppercase tracking-widest">
                                                            Campo</th>
                                                        <th class="px-4 py-2 font-black text-rose-400 uppercase tracking-widest">
                                                            Antes</th>
                                                        <th class="px-4 py-2 font-black text-emerald-400 uppercase tracking-widest">
                                                            Depois</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @php
                                                        $keys = array_unique(array_merge(
                                                            array_keys($log->properties['old'] ?? []),
                                                            array_keys($log->properties['new'] ?? [])
                                                        ));

                                                        $labels = [
                                                            'name' => 'Nome',
                                                            'email' => 'E-mail',
                                                            'role' => 'Nível',
                                                            'password' => 'Senha',
                                                            'data_validade' => 'Validade',
                                                            'quantidade_atual' => 'Qtd Atual',
                                                            'quantidade_inicial' => 'Qtd Inicial',
                                                            'nome' => 'Nome Item',
                                                            'codigo' => 'Código',
                                                            'tipo' => 'Tipo',
                                                            'fabricante' => 'Fabricante',
                                                            'unidade_id' => 'ID Unidade',
                                                            'responsavel' => 'Responsável',
                                                            'observacao' => 'Observação'
                                                        ];
                                                    @endphp

                                                    @foreach($keys as $key)
                                                        @if($key === 'updated_at' || $key === 'id' || $key === 'created_at') @continue @endif
                                                        <tr>
                                                            <td class="px-4 py-2 font-black text-slate-400 uppercase">
                                                                {{ $labels[$key] ?? $key }}</td>
                                                            <td class="px-4 py-2 text-rose-500 font-medium">
                                                                @php
                                                                    $val = $log->properties['old'][$key] ?? null;
                                                                    if (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}/', $val)) {
                                                                        try {
                                                                            $val = \Carbon\Carbon::parse($val)->format('d/m/Y H:i');
                                                                        } catch (\Exception $e) {}
                                                                    }
                                                                @endphp
                                                                {{ is_array($val) ? json_encode($val) : ($val ?: '-') }}
                                                            </td>
                                                            <td class="px-4 py-2 text-emerald-600 font-bold">
                                                                @php
                                                                    $val = $log->properties['new'][$key] ?? null;
                                                                    if (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}/', $val)) {
                                                                        try {
                                                                            $val = \Carbon\Carbon::parse($val)->format('d/m/Y H:i');
                                                                        } catch (\Exception $e) {}
                                                                    }
                                                                @endphp
                                                                {{ is_array($val) ? json_encode($val) : ($val ?: '-') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
@endsection