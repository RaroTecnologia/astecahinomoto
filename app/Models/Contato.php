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
        'ultima_interacao',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $with = ['anotacoes.user']; // Carrega anotações e usuários automaticamente

    const STATUS_VALIDOS = ['novo', 'em_andamento', 'respondido', 'finalizado'];

    const DEPARTAMENTOS = [
        'Atendimento ao Cliente',
        'Representantes Comerciais',
        'Imprensa',
        'Recursos Humanos',
        'Financeiro',
        'Outros Assuntos'
    ];

    public function usuarioAtribuido()
    {
        return $this->belongsTo(User::class, 'atribuido_para');
    }

    public function anotacoes()
    {
        return $this->hasMany(AnotacaoContato::class)->orderBy('created_at', 'desc');
    }
}
