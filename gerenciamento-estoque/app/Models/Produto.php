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
        'local_armazenamento'
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
        return $this->lotes()->sum('quantidade_atual');
    }
}
