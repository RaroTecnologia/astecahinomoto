<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarefaContato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarefas_contatos';

    protected $fillable = [
        'contato_id',
        'criado_por',
        'atribuido_para',
        'titulo',
        'descricao',
        'prioridade',
        'status',
        'data_vencimento',
        'concluido_em'
    ];

    protected $dates = [
        'data_vencimento',
        'concluido_em'
    ];

    public function contato()
    {
        return $this->belongsTo(Contato::class, 'contato_id');
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function usuarioAtribuido()
    {
        return $this->belongsTo(User::class, 'atribuido_para');
    }
}
