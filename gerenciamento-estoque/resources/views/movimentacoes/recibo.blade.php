<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Dispensação - S.M.S.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .folha-papel {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f2f6;
        }

        .folha-papel {
            background: white;
            max-width: 800px;
            margin: 40px auto;
            padding: 60px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #eee;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="no-print fixed top-6 right-6 flex gap-3">
        <button onclick="window.print()"
            class="bg-[#006266] hover:bg-[#009432] text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-printer-fill" viewBox="0 0 16 16">
                <path
                    d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z" />
                <path
                    d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
            </svg>
            Imprimir Comprovante
        </button>
        <a href="{{ route('movimentacoes.index') }}"
            class="bg-white hover:bg-slate-50 text-slate-400 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest border border-slate-200 transition-all">Voltar</a>
    </div>

    <div class="folha-papel">
        <!-- Cabeçalho -->
        <div class="flex items-center justify-between border-b-2 border-slate-100 pb-10 mb-10">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-sms-gradient rounded-2xl flex items-center justify-center text-white"
                    style="background: linear-gradient(135deg, #006266 0%, #009432 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                        class="bi bi-hospital-fill" viewBox="0 0 16 16">
                        <path
                            d="M6 0a2 2 0 0 0-2 2v1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2V2a2 2 0 0 0-2-2H6zm4 1v1h2v1H4V2a1 1 0 0 1 1-1h5zM3 5h10v10H3V5z" />
                        <path d="M8.5 7h-1v1.5H6v1h1.5V11h1V9.5H10v1h-1.5V7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">S.M.S. Porto Amazonas</h1>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Secretaria Municipal de Saúde
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span
                    class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Protocolo</span>
                <span
                    class="text-lg font-mono font-bold text-[#006266]">#{{ $movimentacao->protocolo ?? str_pad($movimentacao->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <!-- Título do Documento -->
        <div class="text-center mb-12">
            <h2
                class="text-xl font-black text-slate-700 uppercase tracking-[0.2em] underline underline-offset-8 decoration-[#009432]">
                Comprovante de Saída</h2>
        </div>

        <!-- Dados da Movimentação -->
        <div class="grid grid-cols-2 gap-10 mb-12">
            <div class="space-y-4">
                <div>
                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Destino</span>
                    <p class="font-bold text-slate-700">{{ $movimentacao->unidade->nome ?? 'Unidade Interna' }}</p>
                </div>
                <div>
                    <span
                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Responsável</span>
                    <p class="font-bold text-slate-700">{{ $movimentacao->responsavel }}</p>
                </div>
            </div>
            <div class="space-y-4 text-right">
                <div>
                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Data da
                        Emissão</span>
                    <p class="font-bold text-slate-700">
                        {{ $movimentacao->data_movimentacao ? $movimentacao->data_movimentacao->format('d/m/Y') : now()->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Hora</span>
                    <p class="font-bold text-slate-700">
                        {{ $movimentacao->created_at ? $movimentacao->created_at->format('H:i') : now()->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabela de Itens -->
        <div class="border rounded-2xl overflow-hidden mb-16">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Item / Medicamento</th>
                        <th
                            class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Lote</th>
                        <th
                            class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $item)
                        <tr>
                            <td class="px-6 py-5">
                                <span class="block font-bold text-slate-700">{{ $item->lote->produto->nome }}</span>
                                <small class="text-[10px] text-slate-400 font-medium">Cód:
                                    {{ $item->lote->produto->codigo }}</small>
                            </td>
                            <td class="px-6 py-5 text-center font-mono font-bold text-slate-600 text-sm">
                                {{ $item->lote->numero_lote }}
                            </td>
                            <td class="px-6 py-5 text-center font-black text-lg text-[#006266]">
                                {{ $item->quantidade }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($movimentacao->observacao)
            <div class="mb-16 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Observações
                    Adicionais</span>
                <p class="text-xs text-slate-600 italic leading-relaxed">{{ $movimentacao->observacao }}</p>
            </div>
        @endif

        <!-- Assinaturas -->
        <div class="grid grid-cols-2 gap-20 pt-10">
            <div class="text-center">
                <div class="border-t border-slate-300 pt-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Assinatura do Recebedor</p>
                    <p class="text-[8px] text-slate-300 mt-1 uppercase">S.M.S. PORTO AMAZONAS</p>
                </div>
            </div>
            <div class="text-center">
                <div class="border-t border-slate-300 pt-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Responsável pela Dispensação</p>
                    <p class="text-[8px] text-slate-300 mt-1 uppercase">{{ $movimentacao->responsavel }}</p>
                </div>
            </div>
        </div>

        <!-- Rodapé Legal -->
        <div class="mt-20 pt-8 border-t border-slate-100 text-center">
            <p class="text-[8px] text-slate-300 uppercase tracking-[0.2em]">Documento gerado eletronicamente pelo
                Sistema de Gestão de Estoque S.M.S.</p>
        </div>
    </div>
</body>

</html>