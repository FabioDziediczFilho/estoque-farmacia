<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Lote;
use App\Models\Movimentacao;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ImportacaoController extends Controller
{
    public function index()
    {
        return view('importacao.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $file = $request->file('arquivo');
            $data = Excel::toArray([], $file);
            
            if (empty($data) || empty($data[0])) {
                return redirect()->back()->with('error', 'O arquivo está vazio ou não pôde ser lido.');
            }

            $rows = $data[0];
            $importados = 0;
            $erros = [];
            
            // Pular cabeçalho se existir
            $startIndex = $this->isHeaderRow($rows[0]) ? 1 : 0;
            
            for ($i = $startIndex; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                try {
                    $result = $this->importRow($row);
                    if ($result['success']) {
                        $importados++;
                    } else {
                        $erros[] = "Linha " . ($i + 1) . ": " . $result['message'];
                    }
                } catch (\Exception $e) {
                    $erros[] = "Linha " . ($i + 1) . ": Erro ao processar - " . $e->getMessage();
                }
            }

            $message = "Importação concluída! {$importados} registros importados com sucesso.";
            if (!empty($erros)) {
                $message .= " " . count($erros) . " erros encontrados.";
                session(['import_errors' => $erros]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar o arquivo: ' . $e->getMessage());
        }
    }

    private function isHeaderRow($row)
    {
        // Verifica se a primeira linha contém textos típicos de cabeçalho
        $headerKeywords = ['codigo', 'produto', 'quantidade', 'validade', 'data'];
        
        foreach ($row as $cell) {
            if (is_string($cell)) {
                $cell = strtolower(trim($cell));
                foreach ($headerKeywords as $keyword) {
                    if (strpos($cell, $keyword) !== false) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    private function importRow($row)
    {
        // Mapeamento esperado: [Código, Produto, Quantidade, Data de Validade]
        if (count($row) < 3) {
            return ['success' => false, 'message' => 'Linha incompleta'];
        }

        $codigo = trim($row[0] ?? '');
        $nome = trim($row[1] ?? '');
        $quantidade = is_numeric($row[2] ?? 0) ? (int)$row[2] : 0;
        $dataValidade = $row[3] ?? null;

        if (empty($codigo) || empty($nome)) {
            return ['success' => false, 'message' => 'Código e nome são obrigatórios'];
        }

        // Buscar ou criar produto
        $produto = Produto::where('codigo', $codigo)->first();
        
        if (!$produto) {
            $produto = Produto::create([
                'codigo' => $codigo,
                'nome' => $nome,
                'tipo' => 'item', // Padrão, pode ser ajustado depois
                'fabricante' => null,
                'local_armazenamento' => null
            ]);
        }

        // Processar data de validade
        $validade = null;
        if ($dataValidade && !empty($dataValidade) && strtoupper($dataValidade) !== 'INDETERMINADA') {
            try {
                $validade = Carbon::parse($dataValidade);
            } catch (\Exception $e) {
                // Tenta formatos brasileiros comuns
                $formats = ['d/m/Y', 'd/m/y', 'Y-m-d'];
                foreach ($formats as $format) {
                    try {
                        $validade = Carbon::createFromFormat($format, $dataValidade);
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        // Criar lote
        $lote = Lote::create([
            'produto_id' => $produto->id,
            'numero_lote' => 'IMPORT-' . date('YmdHis') . '-' . uniqid(),
            'data_validade' => $validade,
            'quantidade_inicial' => $quantidade,
            'quantidade_atual' => $quantidade,
            'data_entrada' => now()
        ]);

        // Criar movimentação de entrada
        Movimentacao::create([
            'lote_id' => $lote->id,
            'tipo' => 'entrada',
            'quantidade' => $quantidade,
            'data_movimentacao' => now(),
            'observacao' => 'Importado via Excel'
        ]);

        return ['success' => true];
    }
}
