@foreach($receitas as $receita)
<x-card-item
    title="{{ $receita->nome }}"
    description="{{ Str::limit($receita->chamada, 100) }}"
    image="{{ $receita->imagem ? asset('storage/receitas/' . $receita->imagem) : 'assets/sem_imagem.png' }}"
    link="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
    linkText="Ver Receita" />
@endforeach