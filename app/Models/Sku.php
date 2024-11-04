<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    use HasFactory;

    protected $table = 'skus';

    protected $fillable = [
        'nome',
        'slug',
        'imagem',
        'thumbnail',
        'produto_id',
        'quantidade',
        'unidade',
        'ean',
        'dun',
        'descricao',
        'porcao_tabela',
        'quantidade_inner',
        'codigo_sku',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relacionamento com o produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    // Relacionamento com valores nutricionais
    public function valoresNutricionais()
    {
        return $this->hasMany(ValorNutricional::class, 'sku_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
