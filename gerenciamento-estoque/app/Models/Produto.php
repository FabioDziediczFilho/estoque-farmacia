<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'codigo',
        'nome',
        'tipo',
        'fabricante',
        'local_armazenamento' // Professor: Onde o item físico está guardado? (Ex: Prateleira A1)
    ];

    protected $casts = [
        'tipo' => 'string'
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    public function getQuantidadeTotalAttribute()
    {
        // Professor: Calculamos o total somando todos os lotes ativos deste produto.
        return $this->lotes()->sum('quantidade_atual');
    }

    /* 
    | Professor Explica: Lógica de Status de Estoque (Baseada na Imagem 4)
    |--------------------------------------------------------------------------
    | Verde: Estoque Garantido (> 20 unidades)
    | Laranja: Estoque Comprometido (6 a 20 unidades)
    | Vermelho: Estoque Crítico / Sem Estoque (<= 5 unidades)
    */
    public function getStatusEstoqueAttribute()
    {
        $total = $this->quantidade_total;

        if ($total > 20) {
            return [
                'label' => 'Estoque Garantido',
                'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot' => 'bg-emerald-500'
            ];
        } elseif ($total > 5) {
            return [
                'label' => 'Estoque Comprometido',
                'color' => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot' => 'bg-amber-500'
            ];
        } else {
            return [
                'label' => $total > 0 ? 'Estoque Crítico' : 'Sem Estoque',
                'color' => 'bg-rose-50 text-rose-700 border-rose-200',
                'dot' => 'bg-rose-500'
            ];
        }
    }
}
