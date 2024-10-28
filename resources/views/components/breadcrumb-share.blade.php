@props(['currentPage', 'parentPage' => null, 'marca' => null, 'produto' => null, 'linha' => null, 'sku' => null, 'textColor' => 'text-gray-700'])

<div class="flex justify-between items-center mb-8">
    <div class="flex items-center space-x-2">
        <!-- Ícone de voltar -->
        <a href="javascript:history.back()" class="{{ $textColor }} text-sm font-semibold flex items-center mr-2">
            <i class="fas fa-arrow-left mr-1"></i>
            Voltar
        </a>

        <!-- Link para Home -->
        <a href="/" class="{{ $textColor }} text-sm font-semibold flex items-center">
            <i class="fas fa-home mr-2"></i>
            Home
        </a>
        <span class="{{ $textColor }}">/</span>

        <!-- Link para a página principal, como "Nossas Marcas" -->
        @if ($parentPage)
        <a href="{{ url('/nossas-marcas') }}" class="{{ $textColor }} text-sm font-semibold">
            {{ $parentPage }}
        </a>
        <span class="{{ $textColor }}">/</span>
        @endif

        <!-- Link para a Marca -->
        @if ($marca)
        <a href="{{ url('/nossas-marcas/' . $marca->slug) }}" class="{{ $textColor }} text-sm font-semibold">
            {{ $marca->nome }}
        </a>
        <span class="{{ $textColor }}">/</span>
        @endif

        <!-- Link para a Linha, caso exista -->
        @if ($linha)
        <a href="{{ url('/nossas-marcas/' . $marca->slug . '/linhas/' . $linha->slug . '/produtos') }}" class="{{ $textColor }} text-sm font-semibold">
            {{ $linha->nome }}
        </a>
        <span class="{{ $textColor }}">/</span>
        @endif

        <!-- Link para o Produto; verificar se há uma linha, caso contrário, usar o caminho direto com a marca -->
        @if ($produto)
        <a href="{{ url('/nossas-marcas/' . $marca->slug . ($linha ? '/linhas/' . $linha->slug . '/produtos/' : '/produtos/') . $produto->slug) }}" class="{{ $textColor }} text-sm font-semibold">
            {{ $produto->nome }}
        </a>
        <span class="{{ $textColor }}">/</span>
        @endif

        <!-- Verificar se o SKU está definido e exibir no breadcrumb -->
        @if ($sku)
        <span class="{{ $textColor }} text-sm font-semibold">{{ $sku->nome }}</span>
        <span class="{{ $textColor }}">/</span>
        @endif

        <!-- Página Atual -->
        <span class="{{ $textColor }} font-bold">{{ $currentPage }}</span>
    </div>

    <!-- Botão Compartilhar -->
    <div class="relative">
        <a href="#" id="shareButton" class="{{ $textColor }} text-sm font-semibold flex items-center cursor-pointer">
            <span id="shareText">Compartilhar</span>
            <i id="shareIcon" class="fas fa-share-alt ml-2"></i>
        </a>
    </div>
</div>