@extends('layouts.app')

@section('title', 'Painel Gerencial')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Título e Saudação -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Gerencial</h1>
                <p class="text-slate-500 font-medium">Bem-vindo, {{ Auth::user()->name }}. Veja a situação da S.M.S. Porto
                    Amazonas.</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-4 py-2 bg-white rounded-2xl shadow-sm border border-slate-100 text-xs font-bold text-slate-400">
                    <i class="bi bi-clock-history mr-2"></i>
                    Última atualização: {{ now()->format('H:i') }}
                </span>
            </div>
        </div>

        <!-- Cards de Indicadores (Estilo Colega) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Estoque Crítico -->
            <div
                class="bg-white rounded-3xl p-6 border-l-[6px] border-rose-500 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Estoque Crítico</p>
                        <h3 class="text-3xl font-black text-rose-600">{{ $itensCriticos }}</h3>
                    </div>
                    <div class="p-3 bg-rose-50 rounded-2xl">
                        <i class="bi bi-exclamation-octagon text-rose-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- A Vencer -->
            <div
                class="bg-white rounded-3xl p-6 border-l-[6px] border-amber-500 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">A Vencer (30D)</p>
                        <h3 class="text-3xl font-black text-amber-600">{{ $lotesVencendo }}</h3>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-2xl">
                        <i class="bi bi-calendar-event text-amber-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total de Itens -->
            <div
                class="bg-white rounded-3xl p-6 border-l-[6px] border-emerald-500 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Itens</p>
                        <h3 class="text-3xl font-black text-emerald-600">{{ $totalItens }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-2xl">
                        <i class="bi bi-box-seam text-emerald-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Saídas Hoje -->
            <div
                class="bg-white rounded-3xl p-6 border-l-[6px] border-sky-500 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Saídas Hoje</p>
                        <h3 class="text-3xl font-black text-sky-600">{{ $saidasHoje }}</h3>
                    </div>
                    <div class="p-3 bg-sky-50 rounded-2xl">
                        <i class="bi bi-truck text-sky-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos Gerenciais -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Gráfico 1: Categorias (Doughnut) -->
            <div class="bg-white rounded-[32px] p-8 shadow-xl shadow-slate-200/40 border border-white">
                <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                    <i class="bi bi-pie-chart-fill mr-3 text-sms-green" style="color: #006266"></i>
                    Distribuição por Categoria
                </h4>
                <div class="relative h-[300px]">
                    <canvas id="chartCategorias"></canvas>
                </div>
            </div>

            <!-- Gráfico 2: Situação do Estoque (Bar) -->
            <div class="bg-white rounded-[32px] p-8 shadow-xl shadow-slate-200/40 border border-white">
                <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                    <i class="bi bi-bar-chart-fill mr-3 text-sms-green" style="color: #006266"></i>
                    Situação do Estoque
                </h4>
                <div class="relative h-[300px]">
                    <canvas id="chartSituacao"></canvas>
                </div>
            </div>
        </div>

        <!-- Grade de Itens Críticos (Novo) -->
        <div class="grid grid-cols-1 gap-8 mb-8">
            <div class="bg-white rounded-[32px] p-8 shadow-xl shadow-slate-200/40 border-2 border-rose-100 overflow-hidden relative">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-rose-50 rounded-full opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h4 class="text-xl font-black text-slate-800 flex items-center">
                                <i class="bi bi-shield-exclamation mr-3 text-rose-500"></i>
                                Alertas de Validade Crítica
                            </h4>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Lotes que exigem atenção imediata</p>
                        </div>
                        <a href="{{ route('lotes.index') }}" class="px-5 py-2 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all border border-rose-100">
                            Ver todos
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-300 uppercase tracking-widest">
                                    <th class="pb-4">Material</th>
                                    <th class="pb-4 text-center">Lote</th>
                                    <th class="pb-4 text-center">Validade</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($lotesCriticos as $c)
                                    <tr class="group">
                                        <td class="py-4">
                                            <span class="block text-sm font-bold text-slate-700">{{ $c->produto->nome }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase">Cód: {{ $c->produto->codigo }}</span>
                                        </td>
                                        <td class="py-4 text-center font-mono text-xs font-bold text-slate-500">{{ $c->numero_lote }}</td>
                                        <td class="py-4 text-center">
                                            <span class="text-xs font-bold {{ $c->estaVencido() ? 'text-rose-600' : 'text-amber-600' }}">
                                                {{ $c->data_validade->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right">
                                            @if($c->estaVencido())
                                                <span class="px-2 py-1 bg-rose-500 text-white rounded-lg text-[8px] font-black uppercase shadow-lg shadow-rose-200">Vencido</span>
                                            @else
                                                <span class="px-2 py-1 bg-amber-400 text-white rounded-lg text-[8px] font-black uppercase shadow-lg shadow-amber-100 italic">Vence em {{ $c->dias_para_vencer }}d</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center text-slate-400 italic text-sm">
                                            Parabéns! Nenhum item com validade crítica no momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atalhos Rápidos (Estilo SMS) -->
        <div>
            <h4 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Acesso Rápido</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('movimentacoes.create') }}"
                    class="group bg-white p-5 rounded-3xl border border-slate-100 hover:border-emerald-500 transition-all flex items-center gap-4 shadow-sm">
                    <div
                        class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <i class="bi bi-plus-lg text-xl"></i>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-700">Nova Saída</span>
                        <span class="text-[10px] text-slate-400 font-medium">Registrar dispensação</span>
                    </div>
                </a>

                <a href="{{ route('produtos.index') }}"
                    class="group bg-white p-5 rounded-3xl border border-slate-100 hover:border-[#006266] transition-all flex items-center gap-4 shadow-sm">
                    <div
                        class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-[#006266] group-hover:text-white transition-all">
                        <i class="bi bi-search text-xl"></i>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-700">Consultar</span>
                        <span class="text-[10px] text-slate-400 font-medium">Ver estoque geral</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts para os Gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Gráfico de Categorias
            new Chart(document.getElementById('chartCategorias'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($catsLabels) !!},
                    datasets: [{
                        data: {!! json_encode($catsValues) !!},
                        backgroundColor: ['#006266', '#009432', '#1289A7', '#ED4C67', '#F79F1F'],
                        borderWidth: 0,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: 'Poppins', weight: 'bold', size: 10 } } }
                    },
                    cutout: '70%'
                }
            });

            // Gráfico de Situação (Barras)
            const ctxSituacao = document.getElementById('chartSituacao');
            if (ctxSituacao) {
                new Chart(ctxSituacao, {
                    type: 'bar',
                    data: {
                        labels: ['Normal', 'Crítico', 'Vencendo'],
                        datasets: [{
                            label: 'Itens',
                            data: [{{ $situacao['normal'] }}, {{ $situacao['critico'] }}, {{ $situacao['vencendo'] }}],
                            backgroundColor: ['#009432', '#ED4C67', '#F79F1F'],
                            borderRadius: 12
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { display: false },
                                ticks: { font: { family: 'Poppins', size: 10 } }
                            },
                            x: { 
                                grid: { display: false }, 
                                ticks: { font: { family: 'Poppins', weight: 'bold', size: 10 } } 
                            }
                        },
                        plugins: { 
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
@endsection