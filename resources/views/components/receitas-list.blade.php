<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($receitas as $receita)
    <x-card-item
        :image="'https://via.placeholder.com/600x400?text=' . urlencode($receita->nome)"
        :title="$receita->nome"
        :description="Str::limit($receita->ingredientes, 100)"
        :link="route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug])"
        linkText="Ver Receita" />
    @endforeach
</div>