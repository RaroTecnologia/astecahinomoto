<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;

    protected $table = 'tipos';

    protected $fillable = ['nome', 'slug', 'cor-bg', 'cor-texto'];

    // Relacionamento com categorias/produtos
    public function categorias()
    {
        return $this->hasMany(Categoria::class, 'tipo_id');
    }
}
