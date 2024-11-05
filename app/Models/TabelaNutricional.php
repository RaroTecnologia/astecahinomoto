<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelaNutricional extends Model
{
    use HasFactory;

    protected $table = 'tabelas_nutricionais';

    protected $fillable = [
        'nome',
        'porcao_caseira',
        'primeiro_valor',
        'segundo_valor',
        'rodape'
    ];

    public function nutrientes()
    {
        return $this->belongsToMany(Nutriente::class, 'valores_nutricionais', 'tabela_nutricional_id', 'nutriente_id')
            ->withPivot('valor_por_100g', 'valor_por_porção', 'valor_diario');
    }
}
