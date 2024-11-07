<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'slug', 'descricao', 'imagem', 'parent_id', 'tipo', 'nivel', 'tipo_id'];

    // Seus relacionamentos atuais são suficientes
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'categoria_id');
    }

    public function subcategorias()
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Categoria::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }

    public function getMarca()
    {
        $categoria = $this;
        while ($categoria && $categoria->nivel !== 'marca') {
            $categoria = $categoria->parent;
        }
        return $categoria;
    }

    public function tipos()
    {
        return $this->belongsToMany(Tipo::class, 'categoria_tipo')
            ->withPivot('is_principal');
    }

    public function tipoPrincipal()
    {
        return $this->belongsToMany(Tipo::class, 'categoria_tipo')
            ->wherePivot('is_principal', true)
            ->first();
    }

    // Adicionar o relacionamento com receitas
    public function receitas()
    {
        return $this->hasMany(Receita::class, 'categoria_id');
    }

    // Adicionar o relacionamento com notícias
    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'categoria_id');
    }

    public function remissivas()
    {
        return $this->hasMany(CategoriaRemissiva::class, 'categoria_origem_id');
    }

    public function remissivasDestino()
    {
        return $this->hasMany(CategoriaRemissiva::class, 'categoria_destino_id');
    }

    // Método auxiliar para verificar se a categoria tem produtos ativos com SKUs ativos
    public function hasActiveProdutos()
    {
        return $this->produtos()
            ->where('is_active', 1)
            ->whereHas('skus', function ($query) {
                $query->where('is_active', 1);
            })
            ->exists();
    }
}
