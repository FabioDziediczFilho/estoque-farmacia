<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Movimentacao;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function lotes()
    {
        $lotes = Lote::with('produto')->get();
        $fileName = 'estoque_lotes_' . now()->format('Ymd_Hi') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($lotes) {
            $file = fopen('php://output', 'w');
            // Bom para garantir que o Excel entenda como UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Instrução específica para o Excel reconhecer o separador
            fputs($file, "sep=;\n");

            fputcsv($file, ['ID', 'Produto', 'Código', 'Lote', 'Qtd Inicial', 'Qtd Atual', 'Validade', 'Entrada'], ';');

            foreach ($lotes as $l) {
                fputcsv($file, [
                    $l->id,
                    $l->produto->nome,
                    $l->produto->codigo,
                    $l->numero_lote,
                    $l->quantidade_inicial,
                    $l->quantidade_atual,
                    $l->data_validade ? $l->data_validade->format('d/m/Y') : 'N/A',
                    $l->data_entrada->format('d/m/Y')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function movimentacoes()
    {
        $movs = Movimentacao::with(['lote.produto', 'unidade'])->orderBy('created_at', 'desc')->get();
        $fileName = 'movimentacoes_' . now()->format('Ymd_Hi') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($movs) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Instrução específica para o Excel reconhecer o separador
            fputs($file, "sep=;\n");

            fputcsv($file, ['Protocolo', 'Data', 'Tipo', 'Material', 'Lote', 'Quantidade', 'Unidade', 'Responsável', 'Observação'], ';');

            foreach ($movs as $m) {
                fputcsv($file, [
                    $m->protocolo ?? '#' . $m->id,
                    $m->data_movimentacao->format('d/m/Y H:i'),
                    $m->tipo == 'entrada' ? 'Entrada (+)' : 'Saída (-)',
                    $m->lote->produto->nome,
                    $m->lote->numero_lote,
                    $m->quantidade,
                    $m->unidade->nome ?? 'Próprio/Interno',
                    $m->responsavel,
                    $m->observacao
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
