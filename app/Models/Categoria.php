<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Categoria extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = ['nome', 'slug', 'descricao', 'imagem', 'parent_id', 'tipo', 'nivel'];

    // Relacionamentos já existentes
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

    // Método para percorrer recursivamente as categorias até encontrar a marca
    public function getMarca()
    {
        $categoria = $this;
        while ($categoria && $categoria->nivel !== 'marca') {
            $categoria = $categoria->parent;
        }
        return $categoria; // Retorna a categoria de nível "marca", ou null se não encontrar
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
}
