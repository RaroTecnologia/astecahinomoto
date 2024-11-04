<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $fillable = [
        'nome',
        'slug',
        'ordem'
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_tipo')
            ->withPivot('is_principal')
            ->withoutTimestamps();
    }
}
