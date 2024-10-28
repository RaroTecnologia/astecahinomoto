<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    use HasFactory;

    // Adicionar os novos campos ao $fillable para que possam ser preenchidos em massa
    protected $fillable = [
        'nome',
        'slug',
        'ingredientes',
        'modo_preparo',
        'categoria_id',
        'imagem',
        'chamada',
        'compartilhamentos', // Novo campo
        'curtidas',          // Novo campo
        'dificuldade',       // Novo campo
        'tempo_preparo',     // Novo campo
    ];

    // Relacionamento: Uma receita pertence a uma categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
