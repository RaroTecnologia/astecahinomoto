<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contatos';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cidade',
        'departamento',
        'mensagem',
        'status',
        'notas_internas',
        'atribuido_para',
        'ultima_interacao'
    ];

    protected $dates = [
        'ultima_interacao'
    ];

    public function usuarioAtribuido()
    {
        return $this->belongsTo(User::class, 'atribuido_para');
    }

    public function tarefas()
    {
        return $this->hasMany(TarefaContato::class, 'contato_id');
    }
}
