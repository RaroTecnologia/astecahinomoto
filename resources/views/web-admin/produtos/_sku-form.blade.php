<div class="sku-header cursor-pointer bg-gray-200 p-4 rounded-md mb-2" data-target="#sku-form-{{ $sku->id ?? 'new' }}">
    <span>SKU: {{ $sku->nome ?? 'Novo SKU' }}</span>
</div>

<div id="sku-form-{{ $sku->id ?? 'new' }}" class="sku-body hidden">
    <form class="sku-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="{{ $method }}">
        <input type="hidden" name="produto_id" value="{{ $produtoId }}">

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <!-- Nome do SKU -->
                <div class="mb-4">
                    <label for="nome_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" name="nome" id="nome_{{ $sku->id ?? 'new' }}" value="{{ $sku->nome ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <!-- Coluna Esquerda -->
            <div>
                <!-- Quantidade do SKU -->
                <div class="mb-4">
                    <label for="quantidade_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Quantidade</label>
                    <input type="text" name="quantidade" id="quantidade_{{ $sku->id ?? 'new' }}" value="{{ $sku->quantidade ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Porção por embalagem -->
                <div class="mb-4">
                    <label for="porcao_tabela_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Porção por embalagem</label>
                    <input type="text" name="porcao_tabela" id="porcao_tabela_{{ $sku->id ?? 'new' }}" value="{{ $sku->porcao_tabela ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Código SKU -->
                <div class="mb-4">
                    <label for="codigo_sku_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Código SKU</label>
                    <input type="text" name="codigo_sku" id="codigo_sku_{{ $sku->id ?? 'new' }}" value="{{ $sku->codigo_sku ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>

            </div>

            <!-- Coluna Direita -->
            <div>
                <!-- Quantidade inner -->
                <div class="mb-4">
                    <label for="quantidade_inner_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Quantidade inner</label>
                    <input type="text" name="quantidade_inner" id="quantidade_inner_{{ $sku->id ?? 'new' }}" value="{{ $sku->quantidade_inner ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Código DUN do SKU -->
                <div class="mb-4">
                    <label for="dun_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Código DUN</label>
                    <input type="text" name="dun" id="dun_{{ $sku->id ?? 'new' }}" value="{{ $sku->dun ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Código EAN do SKU -->
                <div class="mb-4">
                    <label for="ean_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Código EAN</label>
                    <input type="text" name="ean" id="ean_{{ $sku->id ?? 'new' }}" value="{{ $sku->ean ?? '' }}" class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <!-- Imagem do SKU (ocupando duas colunas) -->
            <div class="col-span-2">
                <div class="mb-4">
                    <label for="imagem_{{ $sku->id ?? 'new' }}" class="block text-sm font-medium text-gray-700">Imagem do SKU</label>
                    <input type="file" name="imagem" id="imagem_{{ $sku->id ?? 'new' }}" class="w-full px-4 py-2 border rounded-lg">
                    @if(isset($sku->imagem))
                    <img src="{{ asset('storage/thumbnails/' . $sku->imagem) }}" alt="Imagem do SKU" class="mt-2 h-32">
                    @endif
                </div>
            </div>
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
            <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg delete-sku" data-id="{{ $sku->id }}">
                <i class="fas fa-trash-alt"></i> Excluir
            </button>
            @endif
        </div>
    </form>
</div>