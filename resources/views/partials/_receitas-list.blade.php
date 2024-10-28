@foreach($receitas as $receita)
<x-card-item
    title="{{ $receita->nome }}"
    description="{{ Str::limit($receita->ingredientes, 100) }}"
    image="https://via.placeholder.com/600x400?text={{ urlencode($receita->nome) }}"
    link="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
    linkText="Ver Receita" />
@endforeach