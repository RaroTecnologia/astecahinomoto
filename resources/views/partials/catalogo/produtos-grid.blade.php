<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($skus as $sku)
    @php
    // Encontra a categoria marca (parent até chegar no nível marca)
    $categoriaMarca = $sku->produto->categoria;
    while ($categoriaMarca && $categoriaMarca->nivel !== 'marca') {
    $categoriaMarca = $categoriaMarca->parent;
    }
    @endphp

    @if($categoriaMarca) {{-- Só mostra se tiver uma marca válida --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <a href="{{ route('produtos.show', [
            'slugMarca' => $categoriaMarca->slug,
            'slugProduto' => $sku->produto->slug
        ]) }}" class="block">
            <x-card-item
                title="{{ $sku->produto->nome }}"
                description="{{ $sku->nome }}"
                image="{{ $sku->imagem ? asset('storage/skus/thumbnails/' . $sku->imagem) : asset('assets/sem_imagem.png') }}"
                link="{{ route('produtos.show', [
                    'slugMarca' => $categoriaMarca->slug,
                    'slugProduto' => $sku->produto->slug
                ]) }}"
                linkText="Ver Produto"
                :centerContent="true"
                :fullImage="true" />
        </a>
    </div>
    @endif
    @endforeach
</div>

@if($skus->isEmpty())
<div class="text-center py-8">
    <p class="text-gray-500">Nenhum produto encontrado</p>
</div>
@endif