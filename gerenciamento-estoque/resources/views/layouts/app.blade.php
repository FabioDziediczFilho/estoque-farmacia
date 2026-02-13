<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Almoxarifado') - S.M.S. Porto Amazonas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #006266 0%, #009432 100%);
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f2f6;
        }

        .bg-sms-gradient {
            background: var(--primary-gradient);
        }

        .sidebar-link.active {
            background: white !important;
            color: #009432 !important;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Comprovante / Folha Papel (Referência Colega) */
        .folha-papel {
            background: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 50px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }
    </style>
</head>

<body class="text-slate-800 antialiased">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-sms-gradient text-white transform -translate-x-full md:translate-x-0 md:sticky md:top-0 transition-all duration-300 ease-in-out shadow-2xl flex flex-col">

            <!-- Branding Header -->
            <div class="p-8 text-center bg-black/10">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <i class="bi bi-hospital-fill text-4xl"></i>
                    <div class="text-center lh-1">
                        <span class="block text-xl font-black tracking-tight leading-none">SMS</span>
                        <small class="text-[10px] font-bold opacity-75 uppercase tracking-widest">Porto Amazonas</small>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 text-lg"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                <div class="pt-6 pb-2 px-5 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Gestão</div>

                <a href="{{ route('produtos.index') }}"
                    class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('produtos.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam text-lg"></i>
                    <span class="text-sm">Estoque Geral</span>
                </a>

                <a href="{{ route('lotes.index') }}"
                    class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('lotes.*') ? 'active' : '' }}">
                    <i class="bi bi-layers text-lg"></i>
                    <span class="text-sm">Controle de Lotes</span>
                </a>

                <a href="{{ route('movimentacoes.index') }}"
                    class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('movimentacoes.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right text-lg"></i>
                    <span class="text-sm">Movimentações</span>
                </a>

                <a href="{{ route('relatorios.consumo') }}"
                    class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line text-lg"></i>
                    <span class="text-sm">Relatórios</span>
                </a>

                @if(Auth::user()->role === 'admin')
                    <div class="pt-6 pb-2 px-5 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">
                        Configurações</div>

                    <a href="{{ route('unidades.index') }}"
                        class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('unidades.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt text-lg"></i>
                        <span class="text-sm">Unidades</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'admin')
                    <div class="pt-6 pb-2 px-5 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Painel Admin
                    </div>

                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people text-lg"></i>
                        <span class="text-sm">Gestão de Equipe</span>
                    </a>

                    <a href="{{ route('admin.audits.index') }}"
                        class="sidebar-link flex items-center px-5 py-3.5 rounded-xl hover:bg-white/10 transition-all gap-4 {{ request()->routeIs('admin.audits.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text text-lg"></i>
                        <span class="text-sm">Auditoria</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom User Info -->
            <div class="p-6 bg-black/10 border-t border-white/5 space-y-4">
                <div class="flex items-center gap-3 px-2">
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-black text-sm border border-white/10">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold truncate max-w-[150px]">{{ Auth::user()->name }}</span>
                        <span
                            class="text-[9px] font-black uppercase tracking-wider text-white/50">{{ Auth::user()->role == 'admin' ? 'Coordenador' : 'Operador' }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-3 text-xs font-black uppercase tracking-widest text-white border-2 border-white/20 rounded-xl hover:bg-white hover:text-[#006266] transition-all gap-2">
                        <i class="bi bi-box-arrow-left"></i>
                        Sair do Sistema
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Móvel -->
            <header class="md:hidden bg-white px-4 h-16 flex items-center justify-between shadow-sm sticky top-0 z-40">
                <div class="flex items-center gap-2">
                    <i class="bi bi-hospital-fill text-sms-green text-2xl font-bold" style="color: #006266"></i>
                    <span class="font-black text-slate-800 tracking-tight">SMS PORTO</span>
                </div>
                <button id="sidebar-toggle" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </header>

            <!-- Page Main Content -->
            <main class="flex-1 p-6 md:p-10 overflow-y-auto">
                @if(session('success'))
                    <div class="max-w-6xl mx-auto mb-8">
                        <div
                            class="bg-white border-l-4 border-emerald-500 text-slate-700 px-6 py-4 rounded-2xl flex items-center shadow-lg animate-in slide-in-from-top-4">
                            <i class="bi bi-check-circle-fill text-emerald-500 text-xl mr-4"></i>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-6xl mx-auto mb-8">
                        <div
                            class="bg-white border-l-4 border-rose-500 text-slate-700 px-6 py-4 rounded-2xl flex items-center shadow-lg animate-in slide-in-from-top-4">
                            <i class="bi bi-exclamation-circle-fill text-rose-500 text-xl mr-4"></i>
                            <span class="text-sm font-bold">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="max-w-6xl mx-auto">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar Lucide para compatibilidade se necessário
            if (typeof lucide !== 'undefined') lucide.createIcons();

            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');

            if (toggle) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
        });
    </script>
</body>

</html>