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
        'is_active',
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
        'codigo_sku'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Mutator para garantir que is_active seja sempre 0 ou 1
    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = $value ? 1 : 0;
    }

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
