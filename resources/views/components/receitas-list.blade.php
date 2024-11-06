<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($receitas as $receita)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <img src="https://via.placeholder.com/600x400?text={{ urlencode($receita->nome) }}"
            alt="{{ $receita->nome }}"
            class="w-full h-40 object-cover">
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $receita->nome }}</h3>
            <p class="text-gray-600 text-sm mt-2">{{ $receita->descricao }}</p>
            <a href="{{ route('receitas.show', [$receita->categoria->slug, $receita->slug]) }}"
                class="text-red-600 font-semibold text-sm mt-4 inline-block">
                Ver Receita
            </a>
        </div>
    </div>
    @endforeach
</div>