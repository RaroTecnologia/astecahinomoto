@foreach($receitas as $receita)
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <x-card-item
        title="{{ $receita->nome }}"
        description="{{ Str::limit($receita->ingredientes, 100) }}"
        image="https://via.placeholder.com/600x400?text={{ urlencode($receita->nome) }}"
        link="{{ route('receitas.show', ['categoria' => $receita->categoria->slug, 'slug' => $receita->slug]) }}"
        linkText="Ver Receita" />
    <div class="text-sm text-gray-600">
        {{ $receita->categoria->nome }}
    </div>
</div>
@endforeach