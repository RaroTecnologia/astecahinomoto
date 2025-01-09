<div class="recipe-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-1">
    <!-- Imagem -->
    <div class="relative aspect-video">
        <img src="{{ $receita->imagem ? asset('storage/receitas/thumbnails/' . $receita->imagem) : asset('assets/sem_imagem.png') }}"
             alt="{{ $receita->nome }}"
             class="w-full h-full object-cover">
        
        <!-- Badge da categoria -->
        @if($receita->categoria)
        <span class="absolute top-2 right-2 bg-vermelho-asteca text-white text-xs px-2 py-1 rounded-full">
            {{ $receita->categoria->nome }}
        </span>
        @endif
    </div>

    <!-- Conteúdo -->
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $receita->nome }}</h3>
        
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            {{ Str::limit($receita->chamada, 100) }}
        </p>

        <!-- Footer do Card -->
        <div class="flex items-center justify-between">
            <!-- Curtidas -->
            <div class="flex items-center text-gray-500">
                <i class="fas fa-heart mr-1 {{ $receita->curtidas > 0 ? 'text-vermelho-asteca' : '' }}"></i>
                <span class="text-sm">{{ $receita->curtidas }}</span>
            </div>

            <!-- Link -->
            <a href="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
               class="text-vermelho-asteca hover:text-vermelho-asteca-dark text-sm font-medium transition-colors">
                Ver Receita
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div> 