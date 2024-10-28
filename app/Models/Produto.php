<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = ['nome', 'categoria_id', 'descricao', 'ingredientes', 'imagem', 'slug', 'tabela_nutricional_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tabelaNutricional()
    {
        return $this->belongsTo(TabelaNutricional::class);
    }

    public function valoresNutricionais()
    {
        return $this->hasMany(ValorNutricional::class, 'produto_id');
    }

    public function skus()
    {
        return $this->hasMany(Sku::class, 'produto_id');
    }
}
