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
        'chamada',
        'imagem',
        'categoria_id',
        'dificuldade',
        'tempo_preparo',
        'ingredientes',
        'modo_preparo',
        'video_url',
        'curtidas'
    ];

    // Relacionamento: Uma receita pertence a uma categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
