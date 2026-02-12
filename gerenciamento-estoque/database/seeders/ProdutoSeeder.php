<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\Lote;
use App\Models\Movimentacao;
use Carbon\Carbon;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        // Produtos de exemplo baseados na imagem do Excel
        $produtos = [
            ['codigo' => '001', 'nome' => 'CHÁ MATE 81', 'tipo' => 'item', 'fabricante' => 'Natura', 'local_armazenamento' => 'Armário A'],
            ['codigo' => '002', 'nome' => 'FILTRO DE CAFÉ C/30', 'tipo' => 'item', 'fabricante' => 'Melitta', 'local_armazenamento' => 'Prateleira B'],
            ['codigo' => '003', 'nome' => 'MAIONESE', 'tipo' => 'item', 'fabricante' => 'Hellmanns', 'local_armazenamento' => 'Geladeira 1'],
            ['codigo' => '004', 'nome' => 'LEITE LÍQUIDO', 'tipo' => 'item', 'fabricante' => 'Nestlé', 'local_armazenamento' => 'Geladeira 2'],
            ['codigo' => '005', 'nome' => 'DOCE DE LEITE', 'tipo' => 'item', 'fabricante' => 'Itambé', 'local_armazenamento' => 'Despensa'],
            ['codigo' => '006', 'nome' => 'BISCOITO CREAM CRACKER', 'tipo' => 'item', 'fabricante' => 'Nabisco', 'local_armazenamento' => 'Prateleira C'],
            ['codigo' => '007', 'nome' => 'MARGARINA VIGOR', 'tipo' => 'item', 'fabricante' => 'Vigor', 'local_armazenamento' => 'Geladeira 1'],
            ['codigo' => '008', 'nome' => 'CAFÉ ALVORADA', 'tipo' => 'item', 'fabricante' => 'Alvorada', 'local_armazenamento' => 'Despensa'],
            ['codigo' => '009', 'nome' => 'SUCO DE LARANJA 1L', 'tipo' => 'item', 'fabricante' => 'Del Valle', 'local_armazenamento' => 'Geladeira 3'],
            ['codigo' => '010', 'nome' => 'LEITE EM PÓ', 'tipo' => 'item', 'fabricante' => 'Ninho', 'local_armazenamento' => 'Despensa'],
            ['codigo' => '011', 'nome' => 'ÁGUA', 'tipo' => 'item', 'fabricante' => 'Crystal', 'local_armazenamento' => 'Pallet 1'],
            ['codigo' => '012', 'nome' => 'REFRI', 'tipo' => 'item', 'fabricante' => 'Coca-Cola', 'local_armazenamento' => 'Pallet 2'],
            ['codigo' => '013', 'nome' => 'E.V.A LISO AZUL', 'tipo' => 'item', 'fabricante' => 'Tilibra', 'local_armazenamento' => 'Arquivo 1'],
        ];

        foreach ($produtos as $produtoData) {
            $produto = Produto::create($produtoData);

            // Criar lotes para alguns produtos
            if (in_array($produto->codigo, ['001', '002', '003', '004', '005'])) {
                $this->criarLoteParaProduto($produto);
            }
        }
    }

    private function criarLoteParaProduto($produto)
    {
        $quantidade = rand(10, 100);
        $dataValidade = $produto->codigo == '005' ? null : Carbon::now()->addDays(rand(30, 365));
        
        $lote = Lote::create([
            'produto_id' => $produto->id,
            'numero_lote' => 'LOT' . $produto->codigo . '-' . date('Y'),
            'data_validade' => $dataValidade,
            'quantidade_inicial' => $quantidade,
            'quantidade_atual' => $quantidade,
            'data_entrada' => Carbon::now()->subDays(rand(1, 30))
        ]);

        // Criar movimentação de entrada
        Movimentacao::create([
            'lote_id' => $lote->id,
            'tipo' => 'entrada',
            'quantidade' => $quantidade,
            'data_movimentacao' => $lote->data_entrada,
            'observacao' => 'Entrada inicial'
        ]);

        // Criar algumas saídas para testar
        if (rand(0, 1)) {
            $saidaQtd = rand(1, min(10, $quantidade - 5));
            Movimentacao::create([
                'lote_id' => $lote->id,
                'tipo' => 'saida',
                'quantidade' => $saidaQtd,
                'data_movimentacao' => Carbon::now()->subDays(rand(1, 15)),
                'observacao' => 'Venda'
            ]);
            
            $lote->quantidade_atual -= $saidaQtd;
            $lote->save();
        }
    }
}
