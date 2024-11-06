@props([
'currentPage',
'parentPage' => null,
'parentRoute' => null,
'parentText' => null,
'tipo' => null,
'marca' => null,
'linha' => null,
'produto' => null,
'sku' => null,
'textColor' => 'text-gray-700'
])

<div class="flex justify-between items-center mb-8">
    <div class="flex items-center space-x-4">
        <!-- Botão Voltar -->
        <a href="javascript:history.back()"
            class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors duration-200 {{ $textColor }}">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>

        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 overflow-x-auto">
            <!-- Versão Desktop -->
            <div class="hidden lg:flex items-center space-x-2">
                <a href="/" class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-home"></i>
                </a>

                @if($marca)
                <span class="{{ $textColor }}">/</span>
                <a href="{{ route('marcas') }}" class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    Nossas Marcas
                </a>

                @if($tipo)
                <span class="{{ $textColor }}">/</span>
                <a href="{{ route('marcas.tipo', $tipo->slug) }}" class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    {{ $tipo->nome }}
                </a>
                @endif

                <span class="{{ $textColor }}">/</span>
                <a href="{{ route('marcas.produtos', ['tipoSlug' => $tipo->slug, 'slugMarca' => $marca->slug]) }}"
                    class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    {{ $marca->nome }}
                </a>

                @if($linha && (!$marca || $linha->nome !== $marca->nome))
                <span class="{{ $textColor }}">/</span>
                <a href="{{ route('marcas.produtos.linhas', [
                            'tipoSlug' => $tipo->slug,
                            'slugMarca' => $marca->slug,
                            'slugProduto' => $linha->slug
                        ]) }}" class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    {{ $linha->nome }}
                </a>
                @endif

                @if($produto && (!$linha || $produto->nome !== $linha->nome))
                <span class="{{ $textColor }}">/</span>
                @if($produto->slug)
                <a href="{{ route('marcas.produtos.linhas', [
                                    'tipoSlug' => $tipo->slug,
                                    'slugMarca' => $marca->slug,
                                    'slugProduto' => $produto->slug
                                ]) }}" class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    {{ $produto->nome }}
                </a>
                @else
                <span class="{{ $textColor }} text-sm">{{ $produto->nome }}</span>
                @endif
                @endif
                @elseif($parentRoute && $parentText)
                <span class="{{ $textColor }}">/</span>
                <a href="{{ route($parentRoute) }}" class="{{ $textColor }} text-sm hover:text-red-600 transition-colors duration-200">
                    {{ $parentText }}
                </a>
                @endif

                @if($currentPage && $currentPage !== '')
                <span class="{{ $textColor }}">/</span>
                <span class="{{ $textColor }} text-sm font-semibold">{{ $currentPage }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Botão Compartilhar -->
    <div class="relative">
        <button id="shareButton" class="{{ $textColor }} text-sm font-semibold flex items-center cursor-pointer hover:text-red-600 transition-colors duration-200">
            <span id="shareText" class="hidden lg:inline mr-2">Compartilhar</span>
            <i id="shareIcon" class="fas fa-share-alt"></i>
        </button>
    </div>
</div>