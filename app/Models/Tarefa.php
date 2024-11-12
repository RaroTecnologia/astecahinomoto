<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'data_vencimento',
        'status',
        'contato_id',
        'criado_por'
    ];

    public function contato()
    {
        return $this->belongsTo(Contato::class);
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
