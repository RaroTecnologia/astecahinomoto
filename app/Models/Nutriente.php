<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutriente extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'unidade_medida', 'tipo_nutriente'];

    // Relação muitos-para-muitos com Tabelas Nutricionais
    public function tabelasNutricionais()
    {
        return $this->belongsToMany(TabelaNutricional::class, 'nutriente_tabela_nutricional')
            ->withPivot('valor_por_100g', 'valor_por_porção', 'valor_diario')
            ->withTimestamps();
    }
}
