@foreach($noticias as $noticia)
<x-card-item
    title="{{ $noticia->titulo }}"
    description="{{ Str::limit($noticia->resumo, 100) }}"
    image="{{ $noticia->imagem_url }}"
    link="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}"
    linkText="Ler Mais" />
@endforeach