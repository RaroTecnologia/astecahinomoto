<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Noticia extends Model
{
    use HasFactory;

    // Defina os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = ['titulo', 'slug', 'conteudo', 'imagem', 'status', 'categoria_id', 'publicado_em'];

    protected $casts = [
        'publicado_em' => 'datetime'
    ];

    public function setPublicadoEmAttribute($value)
    {
        $this->attributes['publicado_em'] = $value ? Carbon::parse($value) : null;
    }

    // Relacionamento com a categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
