<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';
    
    protected $fillable = [
        'lote_id',
        'tipo',
        'quantidade',
        'data_movimentacao',
        'observacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime',
        'quantidade' => 'integer',
        'tipo' => 'string'
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($movimentacao) {
            $lote = $movimentacao->lote;
            
            if ($movimentacao->tipo === 'entrada') {
                $lote->quantidade_atual += $movimentacao->quantidade;
            } elseif ($movimentacao->tipo === 'saida') {
                $lote->quantidade_atual -= $movimentacao->quantidade;
            }
            
            $lote->save();
        });
    }
}
