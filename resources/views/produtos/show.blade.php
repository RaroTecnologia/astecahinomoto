@extends('layouts.app')

@section('title', $produto->nome)

@section('content')
<div class="relative bg-white pb-24">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb -->
        <x-breadcrumb-share
            currentPage="{{ $produto->nome }}"
            :marca="$categoriaMarca"
            :produto="$categoriaProduto"
            :linha="$categoriaLinha ?? null" />

        <!-- Conteúdo da página: imagem à esquerda, informações à direita -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-16">
            <!-- Coluna da Imagem do Produto -->
            <div class="flex justify-center">
                <img id="product-image"
                    src="{{ $skus->first()->imagem ? asset('storage/skus/' . $skus->first()->imagem) : asset('assets/sem_imagem.png') }}"
                    alt="{{ $produto->nome }}"
                    class="w-[450px] h-[450px] object-contain rounded transition-opacity duration-200 opacity-100">
            </div>

            <!-- Coluna das Informações do Produto -->
            <div>
                <!-- Título do Produto -->
                <div class="mb-6">
                    <h1 id="product-title" class="text-4xl font-bold text-gray-700 transition-opacity duration-200 opacity-100">{{ $produto->nome }}</h1>
                    <h2 id="sku-title" class="text-2xl font-semibold text-gray-500 mt-2 transition-opacity duration-200 opacity-100"></h2>
                </div>

                <!-- Disponível em -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Disponível em:</h3>
                    <div class="flex flex-wrap gap-2" id="sku-options">
                        @foreach($skus as $sku)
                        <button
                            class="sku-option px-3 py-1 border text-gray-700 rounded-full hover:bg-red-600 hover:text-white transition min-w-[80px] text-center text-sm md:text-base"
                            data-sku-name="{{ $sku->nome }}"
                            data-sku-slug="{{ $sku->slug }}"
                            data-image-url="{{ asset('storage/skus/' . $sku->imagem) }}">
                            {{ $sku->quantidade }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Descrição do Produto -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Descrição:</h3>
                    <p class="text-gray-600">{{ $produto->descricao }}</p>
                </div>

                <!-- Ingredientes -->
                @if($produto->ingredientes)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Ingredientes:</h3>
                    <p class="text-gray-600">{{ $produto->ingredientes }}</p>
                </div>
                @endif

                <!-- Accordion para a Tabela Nutricional com animação -->
                @if(isset($produto->tabelaNutricional) && $produto->tabelaNutricional)
                <div class="mt-6">
                    <button class="accordion-toggle font-semibold text-lg flex justify-between items-center w-full">
                        Informação Nutricional
                        <i class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                    <div class="accordion-content overflow-hidden transition-[height] duration-300 ease-in-out hidden" style="height: 0;">
                        <div class="tabela-nutricional">
                            <table class="w-full table-fixed border border-black mt-4">
                                <thead>
                                    <tr>
                                        <td colspan="4" class="text-center font-bold text-lg py-2">INFORMAÇÃO NUTRICIONAL</td>
                                    </tr>
                                    <tr class="tabela-linha1">
                                        <td colspan="4" class="border-b border-black py-2 text-sm">
                                            Porções por embalagem: {{ $produto->tabelaNutricional->porcoes_por_embalagem ?? '' }}<br>
                                            Porção: {{ $produto->tabelaNutricional->porcao ?? '' }}
                                        </td>
                                    </tr>
                                    <tr class="border-t-4 border-black">
                                        <td class="text-left py-2"></td>
                                        <td class="text-center py-2 font-bold">100g</td>
                                        <td class="text-center py-2 font-bold">{{ $produto->tabelaNutricional->porcao_g ?? '' }}g</td>
                                        <td class="text-center py-2 font-bold">%VD*</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produto->tabelaNutricional->nutrientes ?? [] as $nutriente)
                                    <tr>
                                        <td class="border-t border-b border-black py-2">{{ $nutriente->nome ?? '' }}</td>
                                        <td class="border-t border-b border-black text-center">{{ $nutriente->valor_100g ?? '' }}</td>
                                        <td class="border-t border-b border-black text-center">{{ $nutriente->valor_porcao ?? '' }}</td>
                                        <td class="border-t border-b border-black text-center">{{ $nutriente->vd ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t-4 border-black">
                                        <td colspan="4" class="py-2 text-sm">*Percentual de valores diários fornecidos pela porção.</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div> <!-- Fim da Coluna de Informações -->
        </div>
        <!-- Veja Também: Produtos Relacionados -->
        @if($produtosRelacionados->isNotEmpty())
        <div class="mt-12">
            <h3 class="text-2xl font-bold mb-4">Veja Também</h3>

            <!-- Grid de Produtos Relacionados -->
            <div id="relatedProductsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($produtosRelacionados->take(12) as $index => $relacionado)
                <div class="border p-4 rounded-lg @if($index >= 4) hidden @endif related-product">
                    <a href="{{ url('/produto/' . $categoriaMarca->slug . '/' . $relacionado->slug) }}">
                        <img src="{{ $relacionado->skus->first()->imagem ? asset('storage/skus/' . $relacionado->skus->first()->imagem) : asset('assets/sem_imagem.png') }}"
                            alt="{{ $relacionado->nome }}"
                            class="w-full h-48 object-contain mb-4">
                        <h4 class="text-xl font-semibold text-gray-800">{{ $relacionado->nome }}</h4>
                        <p class="text-gray-600">{{ $relacionado->descricao ?? 'Descrição indisponível' }}</p>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Botão para Exibir Todos (apenas se houver mais de 5 produtos) -->
            @if($produtosRelacionados->count() > 5)
            <div class="text-center mt-8">
                <button id="toggleViewButton" class="px-6 py-2 border border-gray-600 text-gray-600 font-semibold rounded-lg hover:text-gray-700 hover:bg-gray-50 transition duration-300">Ver todos</button>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Função para atualizar o título e a imagem do produto ao clicar no SKU
        function updateProductTitle(skuName, skuSlug) {
            const skuTitleElement = document.getElementById('sku-title');
            const productImage = document.getElementById('product-image');
            fadeOutElement(skuTitleElement, function() {
                skuTitleElement.textContent = skuName;
                fadeInElement(skuTitleElement);
            });
            window.location.hash = skuSlug;
        }

        // Funções de fade in e fade out
        function fadeInElement(element) {
            element.classList.remove('opacity-0');
            element.classList.add('opacity-100');
        }

        function fadeOutElement(element, callback) {
            element.classList.remove('opacity-100');
            element.classList.add('opacity-0');
            setTimeout(callback, 200);
        }

        // Lógica para alterar o SKU e a imagem
        const skuOptions = document.querySelectorAll('.sku-option');
        const productImage = document.getElementById('product-image');

        skuOptions.forEach(option => {
            option.addEventListener('click', function() {
                const skuName = this.getAttribute('data-sku-name');
                const imageUrl = this.getAttribute('data-image-url');
                updateProductTitle(skuName, this.getAttribute('data-sku-slug'));
                fadeOutElement(productImage, function() {
                    productImage.src = imageUrl;
                    fadeInElement(productImage);
                });
            });
        });

        // Carrega o SKU baseado no hash da URL
        function loadSkuFromHash() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                const skuButton = document.querySelector(`button[data-sku-slug="${hash}"]`);
                if (skuButton) {
                    const skuName = skuButton.getAttribute('data-sku-name');
                    const imageUrl = skuButton.getAttribute('data-image-url');
                    updateProductTitle(skuName, hash);
                    productImage.src = imageUrl;
                }
            }
        }

        loadSkuFromHash();

        // Caso não tenha hash, carrega o primeiro SKU
        if (!window.location.hash) {
            const firstSkuButton = document.querySelector('.sku-option');
            if (firstSkuButton) {
                const skuName = firstSkuButton.getAttribute('data-sku-name');
                const imageUrl = firstSkuButton.getAttribute('data-image-url');
                updateProductTitle(skuName, firstSkuButton.getAttribute('data-sku-slug'));
                productImage.src = imageUrl;
            }
        }

        window.addEventListener('hashchange', loadSkuFromHash);

        // Lógica para exibir mais produtos relacionados
        const toggleViewButton = document.getElementById('toggleViewButton');
        const relatedProducts = document.querySelectorAll('.related-product');
        let isExpanded = false;

        if (toggleViewButton) {
            toggleViewButton.addEventListener('click', function() {
                if (!isExpanded) {
                    relatedProducts.forEach((product, index) => {
                        if (index >= 4) {
                            product.classList.remove('hidden');
                            product.classList.add('opacity-0');
                            setTimeout(() => {
                                product.classList.add('transition-opacity', 'duration-500', 'opacity-100');
                            }, 50 * index);
                        }
                    });
                    toggleViewButton.textContent = 'Ver menos';
                } else {
                    relatedProducts.forEach((product, index) => {
                        if (index >= 4) {
                            product.classList.add('hidden');
                            product.classList.remove('opacity-100');
                        }
                    });
                    toggleViewButton.textContent = 'Ver todos';
                }
                isExpanded = !isExpanded;
            });
        }
    });
</script>