<a href="{{ $link }}" class="recipe-card block h-[420px] rounded-lg overflow-hidden bg-white shadow hover:shadow-lg transition-all duration-300 flex flex-col group">
    <!-- Imagem -->
    <div class="h-[250px] w-full overflow-hidden">
        <img src="{{ $image }}"
            alt="{{ $title }}"
            class="w-full h-full object-cover">
    </div>

    <!-- Conteúdo -->
    <div class="p-6 flex flex-col flex-grow {{ $centerContent ? 'text-center' : '' }}">
        <!-- Área de texto -->
        <div class="flex-grow space-y-4">
            <!-- Título -->
            <h3 class="font-medium text-lg line-clamp-1 group-hover:text-vermelho-asteca transition-colors duration-300">{{ $title }}</h3>

            <!-- Descrição -->
            <p class="text-gray-600 text-sm line-clamp-2">{{ $description }}</p>
        </div>

        <!-- Botão visual (não mais um link) -->
        <div class="mt-4 {{ $centerContent ? 'flex justify-center' : '' }}">
            <div class="w-full text-center font-semibold border border-vermelho-asteca text-vermelho-asteca group-hover:text-white group-hover:bg-vermelho-asteca py-2 px-4 rounded-full transition-all duration-300 h-[40px] leading-[20px]">
                {{ $linkText }}
            </div>
        </div>
    </div>
</a>