<div class="sku-header cursor-pointer bg-gray-200 p-4 rounded-md mb-2" data-target="#sku-form-{{ $sku->id ?? 'new' }}">
    <span>SKU: {{ $sku->nome ?? 'Novo SKU' }}</span>
</div>

<div id="sku-form-{{ $sku->id ?? 'new' }}" class="sku-body hidden"> <!-- Escondido inicialmente -->
    <form class="sku-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="{{ $method }}">
        <input type="hidden" name="produto_id" value="{{ $produtoId }}">

        <!-- Nome do SKU -->
        <div class="mb-4">
            <label for="nome_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="nome" id="nome_{{ $sku->id ?? 'new' }}" value="{{ $sku->nome ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Quantidade do SKU -->
        <div class="mb-4">
            <label for="quantidade_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Quantidade</label>
            <input type="text" name="quantidade" id="quantidade_{{ $sku->id ?? 'new' }}" value="{{ $sku->quantidade ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Unidade do SKU -->
        <div class="mb-4">
            <label for="unidade_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Unidade</label>
            <input type="text" name="unidade" id="unidade_{{ $sku->id ?? 'new' }}" value="{{ $sku->unidade ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Código EAN do SKU -->
        <div class="mb-4">
            <label for="ean_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Código EAN</label>
            <input type="text" name="ean" id="ean_{{ $sku->id ?? 'new' }}" value="{{ $sku->ean ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Código DUN do SKU -->
        <div class="mb-4">
            <label for="dun_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Código DUN</label>
            <input type="text" name="dun" id="dun_{{ $sku->id ?? 'new' }}" value="{{ $sku->dun ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Imagem do SKU -->
        <div class="mb-4">
            <label for="imagem_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Imagem do SKU</label>
            <input type="file" name="imagem" id="imagem_{{ $sku->id ?? 'new' }}" class="w-full px-4 py-2 border rounded-lg">
            @if(isset($sku->imagem))
            <img src="{{ asset('storage/thumbnails/' . $sku->imagem) }}" alt="Imagem do SKU" class="mt-2 h-32">
            @endif
        </div>

        <!-- Botões de ação com ícones -->
        <div class="flex space-x-4 mt-4">
            <!-- Botão Salvar SKU -->
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg save-sku" data-id="{{ $sku->id ?? 'new' }}">
                <i class="fas fa-save"></i> Salvar
            </button>

            <!-- Botão Duplicar SKU -->
            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded-lg duplicate-sku" data-id="{{ $sku->id ?? 'new' }}">
                <i class="fas fa-copy"></i> Duplicar
            </button>

            <!-- Botão Excluir SKU (exibido apenas se o SKU existir) -->
            @if(isset($sku->id))
            <form action="{{ route('skus.destroy', $sku->id) }}" method="POST" class="delete-sku-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-trash-alt"></i> Excluir
                </button>
            </form>
            @endif
        </div>
    </form>
</div>