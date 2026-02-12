<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lote extends Model
{
    protected $fillable = [
        'produto_id',
        'numero_lote',
        'data_validade',
        'quantidade_inicial',
        'quantidade_atual',
        'data_entrada'
    ];

    protected $casts = [
        'data_validade' => 'date',
        'data_entrada' => 'date',
        'quantidade_inicial' => 'integer',
        'quantidade_atual' => 'integer'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }

    public function estaProximoDeVencer($dias = 30)
    {
        if (!$this->data_validade) {
            return false;
        }
        
        return $this->data_validade->lte(Carbon::now()->addDays($dias));
    }

    public function estaVencido()
    {
        if (!$this->data_validade) {
            return false;
        }
        
        return $this->data_validade->lt(Carbon::now());
    }

    public function getDiasParaVencerAttribute()
    {
        if (!$this->data_validade) {
            return null;
        }
        
        $agora = Carbon::now();
        $validade = Carbon::parse($this->data_validade);
        
        // Calcular diferença em dias inteiros
        $dias = $validade->diffInDays($agora, false);
        
        // Se já venceu, mostrar como negativo
        if ($validade->lt($agora)) {
            return -(int)$dias;
        }
        
        return (int)$dias;
    }
}
