<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    protected $fillable = [
        'titulo',
        'subtitulo',
        'link',
        'ordem',
        'imagem_desktop',
        'imagem_mobile'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];
}
