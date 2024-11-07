<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaRemissiva extends Model
{
    protected $table = 'categorias_remissivas';

    protected $fillable = [
        'categoria_origem_id',
        'categoria_destino_id'
    ];

    public function categoriaOrigem()
    {
        return $this->belongsTo(Categoria::class, 'categoria_origem_id');
    }

    public function categoriaDestino()
    {
        return $this->belongsTo(Categoria::class, 'categoria_destino_id');
    }
}
