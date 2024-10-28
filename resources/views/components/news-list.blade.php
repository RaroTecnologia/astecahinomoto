<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($noticias as $noticia)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <img src="https://via.placeholder.com/600x400?text={{ urlencode($noticia->titulo) }}" alt="{{ $noticia->titulo }}" class="w-full h-40 object-cover">
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $noticia->titulo }}</h3>
            <p class="text-gray-600 text-sm mt-2">{{ Str::limit($noticia->conteudo, 100) }}</p>
            <a href="{{ route('noticias.show', ['categoria' => $noticia->categoria->slug, 'slug' => $noticia->slug]) }}" class="text-red-600 font-semibold text-sm mt-4 inline-block">Leia Mais</a>
        </div>
    </div>
    @endforeach
</div>