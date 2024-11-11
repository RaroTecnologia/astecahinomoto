@forelse($noticias as $noticia)
    <x-card-item
        title="{{ $noticia->titulo }}"
        description="{{ Str::limit($noticia->resumo, 100) }}"
        image="{{ $noticia->imagem_url }}"
        link="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}"
        linkText="Ler Mais" />
@empty
    <div class="col-span-full flex flex-col items-center justify-center py-12">
        <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600">Nenhuma notícia disponível</h3>
        <p class="text-gray-500 mt-2">No momento não há notícias publicadas nesta categoria.</p>
    </div>
@endforelse