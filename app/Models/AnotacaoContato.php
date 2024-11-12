<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnotacaoContato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anotacoes_contatos';

    protected $fillable = [
        'contato_id',
        'user_id',
        'conteudo'
    ];

    protected $with = ['user']; // Sempre carrega o usuário junto

    public function contato()
    {
        return $this->belongsTo(Contato::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
