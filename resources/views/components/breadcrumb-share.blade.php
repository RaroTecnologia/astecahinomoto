@props(['currentPage', 'parentPage' => null, 'tipo' => null, 'marca' => null, 'produto' => null, 'linha' => null, 'sku' => null, 'textColor' => 'text-gray-700'])

<div class="flex justify-between items-center mb-8">
    <div class="flex items-center space-x-2 overflow-x-auto">
        <!-- Versão Mobile -->
        <div class="lg:hidden flex items-center space-x-2">
            <a href="javascript:history.back()" class="{{ $textColor }} text-sm font-semibold">
                <i class="fas fa-arrow-left"></i>
            </a>
            <span class="{{ $textColor }}">/</span>

            @if($sku)
            <span class="{{ $textColor }} text-sm font-semibold">{{ $sku->nome }}</span>
            @elseif($produto)
            <span class="{{ $textColor }} text-sm font-semibold">{{ $produto->nome }}</span>
            @elseif($linha)
            <span class="{{ $textColor }} text-sm font-semibold">{{ $linha->nome }}</span>
            @elseif($marca)
            <span class="{{ $textColor }} text-sm font-semibold">{{ $marca->nome }}</span>
            @endif
        </div>

        <!-- Versão Desktop -->
        <div class="hidden lg:flex items-center space-x-2">
            <a href="/" class="{{ $textColor }} text-sm font-semibold flex items-center">
                <i class="fas fa-home mr-2"></i>
                Home
            </a>
            <span class="{{ $textColor }}">/</span>

            @if ($parentPage)
            <a href="{{ url('/nossas-marcas') }}" class="{{ $textColor }} text-sm font-semibold">
                {{ $parentPage }}
            </a>
            <span class="{{ $textColor }}">/</span>
            @endif

            @if ($marca)
            <a href="{{ url('/marcas/' . $marca->tipo . '/' . $marca->slug) }}" class="{{ $textColor }} text-sm font-semibold">
                {{ $marca->nome }}
            </a>
            <span class="{{ $textColor }}">/</span>
            @endif

            @if ($linha)
            <a href="{{ url('/marcas/' . $marca->slug . '/linhas/' . $linha->slug . '/produtos') }}" class="{{ $textColor }} text-sm font-semibold">
                {{ $linha->nome }}
            </a>
            <span class="{{ $textColor }}">/</span>
            @endif

            @if ($produto)
            <a href="{{ url('/marcas/' . $marca->slug . ($linha ? '/linhas/' . $linha->slug . '/produtos/' : '/produtos/') . $produto->slug) }}" class="{{ $textColor }} text-sm font-semibold">
                {{ $produto->nome }}
            </a>
            <span class="{{ $textColor }}">/</span>
            @endif

            @if ($sku)
            <span class="{{ $textColor }} text-sm font-semibold">{{ $sku->nome }}</span>
            <span class="{{ $textColor }}">/</span>
            @endif

            <span class="{{ $textColor }} font-bold">{{ $currentPage }}</span>
        </div>
    </div>

    <!-- Botão Compartilhar -->
    <div class="relative">
        <a href="#" id="shareButton" class="{{ $textColor }} text-sm font-semibold flex items-center cursor-pointer">
            <span id="shareText" class="hidden lg:inline">Compartilhar</span>
            <i id="shareIcon" class="fas fa-share-alt ml-2"></i>
        </a>
    </div>
</div>