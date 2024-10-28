<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValorNutricional extends Model
{
    use HasFactory;

    protected $table = 'valores_nutricionais';

    protected $fillable = [
        'produto_id',
        'sku_id',
        'nutriente_id',
        'valor_por_100g',
        'valor_por_porção',
        'valor_diario',
    ];

    // Relacionamento com a tabela de produtos
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    // Relacionamento com a tabela de SKUs
    public function sku()
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    // Relacionamento com a tabela de nutrientes
    public function nutriente()
    {
        return $this->belongsTo(Nutriente::class, 'nutriente_id');
    }
}
