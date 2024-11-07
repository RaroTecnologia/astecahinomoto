<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($skus as $sku)
    @if($sku->produto && $sku->produto->categoria)
    @php
    $slugMarca = $sku->produto->categoria->parent ? $sku->produto->categoria->parent->slug : 'sem-marca';
    $slugProduto = $sku->produto->slug ?? 'produto-nao-encontrado';
    @endphp

    <x-card-item
        title="{{ $sku->produto->nome }}"
        description="{{ $sku->nome }}"
        image="{{ $sku->imagem ? asset('storage/produtos/thumbnails/' . $sku->imagem) : asset('assets/sem_imagem.png') }}"
        link="{{ route('produtos.show', ['slugMarca' => $slugMarca, 'slugProduto' => $slugProduto]) }}"
        linkText="Ver Produto"
        :centerContent="true" />
    @endif
    @endforeach
</div>

@if($skus->isEmpty())
<div class="text-center py-8">
    <p class="text-gray-500">Nenhum produto encontrado</p>
</div>
@endif