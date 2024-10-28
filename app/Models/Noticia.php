<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    // Defina os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = ['titulo', 'slug', 'conteudo', 'imagem', 'status', 'categoria_id', 'publicado_em'];

    // Relacionamento com a categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
