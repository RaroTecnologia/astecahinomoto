<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeDestaque extends Model
{
    protected $fillable = [
        'produto_id',
        'ordem',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
