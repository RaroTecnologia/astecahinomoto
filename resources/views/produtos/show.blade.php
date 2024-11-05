@extends('layouts.app')

@section('title', $produto->nome)

@section('content')
<div class="relative bg-white pb-24">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb -->
        <x-breadcrumb-share
            :tipo="$tipo"
            :marca="$categoriaMarca"
            :linha="$categoriaProduto"
            :produto="$categoriaLinha"
            currentPage="" />

        <!-- Conteúdo da página: imagem à esquerda, informações à direita -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-16">
            <!-- Coluna da Imagem do Produto -->
            <div class="flex justify-center">
                <!-- Container principal da imagem com altura fixa -->
                <div class="relative w-[450px] h-[450px] bg-white">
                    <!-- Imagem atual -->
                    <img id="product-image"
                        src="{{ $skus->first()->imagem ? asset('storage/skus/' . $skus->first()->imagem) : asset('assets/sem_imagem.png') }}"
                        alt="{{ $produto->nome }}"
                        class="absolute inset-0 w-full h-full object-contain rounded transition-opacity duration-200 ease-in-out">

                    <!-- Div de loading -->
                    <div id="image-loading" class="absolute inset-0 flex items-center justify-center bg-white">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                    </div>
                </div>
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
                            data-image-url="{{ asset('storage/skus/' . $sku->imagem) }}"
                            data-porcao-tabela="{{ $sku->porcao_tabela }}">
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
                    <p class="text-gray-600 text-justify whitespace-pre-line">{{ $produto->ingredientes }}</p>
                </div>
                @endif

                <!-- Accordion para a Tabela Nutricional com animação -->
                @if(isset($produto->tabelaNutricional) && $produto->tabelaNutricional)
                <div class="mt-6">
                    <button class="accordion-toggle font-semibold text-lg flex justify-between items-center w-full">
                        Informação Nutricional
                        <i class="fas fa-chevron-down transition-transform duration-300"></i>
                    </button>
                    <div class="accordion-content overflow-hidden transition-all duration-300 ease-in-out" style="height: 0;">
                        <div class="tabela-nutricional py-4">
                            <table class="w-full table-fixed">
                                <colgroup>
                                    <col width="40%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <td colspan="4" class="text-center font-bold text-2xl py-2">INFORMAÇÃO NUTRICIONAL</td>
                                    </tr>
                                    <tr class="tabela-linha1 border-t border-b-[5px] border-black">
                                        <td colspan="4" class="py-2 text-sm">
                                            Porções por embalagem: {{ $skus->first()->porcao_tabela ?? '' }}<br>
                                            Porção: {{ $produto->tabelaNutricional->porcao_caseira ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="height: 8px; padding: 0;"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"></td>
                                        <td class="text-center font-bold border-l border-black">{{ $produto->tabelaNutricional->primeiro_valor ?? '100 g' }}</td>
                                        <td class="text-center font-bold border-l border-r border-black">{{ $produto->tabelaNutricional->segundo_valor ?? '' }}</td>
                                        <td class="text-center font-bold">%VD*</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produto->tabelaNutricional->nutrientes ?? [] as $nutriente)
                                    <tr>
                                        <td class="text-left border-t border-b border-black py-2">
                                            {!! $nutriente->nivel == 2 ? '&nbsp;' : ($nutriente->nivel == 3 ? '&nbsp;&nbsp;' : '') !!}{{ $nutriente->nome }}
                                        </td>
                                        <td class="text-center border-t border-b border-l border-black">{{ $nutriente->pivot->valor_por_100g }}</td>
                                        <td class="text-center border-t border-b border-l border-r border-black">{{ $nutriente->pivot->valor_por_porção }}</td>
                                        <td class="text-center border-t border-b border-black">{{ $nutriente->pivot->valor_diario }}</td>
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
                @php
                // Verifica se tem SKUs antes de tentar acessar
                $primeiroSku = $relacionado->skus->first();

                // Define a imagem principal com verificações de null
                $imagemPrincipal = $relacionado->imagem
                ? asset('storage/produtos/' . $relacionado->imagem)
                : ($primeiroSku && $primeiroSku->imagem
                ? asset('storage/skus/' . $primeiroSku->imagem)
                : asset('assets/sem_imagem.png'));
                @endphp

                <div class="border p-4 rounded-lg @if($index >= 4) hidden @endif related-product">
                    <a href="{{ url('/produto/' . $categoriaMarca->slug . '/' . $relacionado->slug) }}">
                        <img src="{{ $imagemPrincipal }}"
                            alt="{{ $relacionado->nome }}"
                            class="w-full h-48 object-contain mb-4">
                        <h4 class="text-xl font-semibold text-gray-800">{{ $relacionado->nome }}</h4>
                        <p class="text-gray-600">{{ $relacionado->descricao ?? 'Descrição indisponível' }}</p>

                        <!-- Badges de SKUs -->
                        @if($relacionado->skus && $relacionado->skus->isNotEmpty())
                        <div class="flex flex-wrap gap-2 justify-start mt-3">
                            @foreach($relacionado->skus as $sku)
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">
                                {{ $sku->quantidade }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Botão para Exibir Todos (apenas se houver mais de 5 produtos) -->
            @if($produtosRelacionados->count() > 5)
            <div class="text-center mt-8">
                <button id="toggleViewButton" class="px-6 py-2 border border-gray-600 text-gray-600 font-semibold rounded-lg hover:text-gray-700 hover:bg-gray-50 transition duration-300">Ver mais</button>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productImage = document.getElementById('product-image');
        const loadingElement = document.getElementById('image-loading');
        const preloadImages = document.querySelectorAll('.preload-image');
        const imageCache = new Map();

        // Pré-carrega todas as imagens
        preloadImages.forEach(img => {
            const src = img.src;
            const image = new Image();
            image.src = src;
            image.onload = () => {
                imageCache.set(src, true);
            };
        });

        // Função para atualizar o título e a imagem do produto
        function updateProductTitle(skuName, skuSlug, skuPorcaoTabela) {
            const skuTitleElement = document.getElementById('sku-title');
            const porcaoTabelaElement = document.querySelector('.tabela-linha1 td');

            fadeOutElement(skuTitleElement, function() {
                skuTitleElement.textContent = skuName;
                if (porcaoTabelaElement) {
                    const porcaoCaseira = porcaoTabelaElement.innerHTML.split('<br>')[1] || '';
                    porcaoTabelaElement.innerHTML = `Porções por embalagem: ${skuPorcaoTabela}<br>${porcaoCaseira}`;
                }
                fadeInElement(skuTitleElement);
            });
            window.location.hash = skuSlug;
        }

        // Funções de fade
        function fadeOutElement(element, callback) {
            element.classList.remove('opacity-100');
            element.classList.add('opacity-0');
            setTimeout(callback, 200);
        }

        function fadeInElement(element) {
            element.classList.remove('opacity-0');
            element.classList.add('opacity-100');
        }

        // Função para trocar a imagem com loading
        function changeImage(imageUrl) {
            // Mostra loading e esconde imagem atual
            loadingElement.classList.remove('hidden');
            productImage.style.opacity = '0';

            // Se a imagem já está em cache, troca imediatamente
            if (imageCache.has(imageUrl)) {
                productImage.src = imageUrl;
                setTimeout(() => {
                    loadingElement.classList.add('hidden');
                    productImage.style.opacity = '1';
                }, 200); // Ajustado para 200ms
                return;
            }

            // Se não está em cache, carrega primeiro
            const tempImage = new Image();
            tempImage.onload = function() {
                imageCache.set(imageUrl, true);
                productImage.src = imageUrl;
                setTimeout(() => {
                    loadingElement.classList.add('hidden');
                    productImage.style.opacity = '1';
                }, 200); // Ajustado para 200ms
            };
            tempImage.src = imageUrl;
        }

        // Lógica para alterar o SKU e a imagem
        const skuOptions = document.querySelectorAll('.sku-option');
        skuOptions.forEach(option => {
            option.addEventListener('click', function() {
                const skuName = this.getAttribute('data-sku-name');
                const imageUrl = this.getAttribute('data-image-url');
                const skuPorcaoTabela = this.getAttribute('data-porcao-tabela');
                updateProductTitle(skuName, this.getAttribute('data-sku-slug'), skuPorcaoTabela);
                changeImage(imageUrl);
            });
        });

        // Carrega o SKU baseado no hash da URL ou o primeiro SKU
        function loadSkuFromHash() {
            const hash = window.location.hash.substring(1);
            const skuButton = hash ?
                document.querySelector(`button[data-sku-slug="${hash}"]`) :
                document.querySelector('.sku-option');

            if (skuButton) {
                const skuName = skuButton.getAttribute('data-sku-name');
                const imageUrl = skuButton.getAttribute('data-image-url');
                const skuPorcaoTabela = skuButton.getAttribute('data-porcao-tabela');

                updateProductTitle(skuName, skuButton.getAttribute('data-sku-slug'), skuPorcaoTabela);
                changeImage(imageUrl);
            }
        }

        // Inicialização
        loadSkuFromHash();

        // Se não houver hash na URL, carrega o primeiro SKU
        if (!window.location.hash) {
            const firstSkuButton = document.querySelector('.sku-option');
            if (firstSkuButton) {
                const skuName = firstSkuButton.getAttribute('data-sku-name');
                const imageUrl = firstSkuButton.getAttribute('data-image-url');
                const skuPorcaoTabela = firstSkuButton.getAttribute('data-porcao-tabela');

                updateProductTitle(skuName, firstSkuButton.getAttribute('data-sku-slug'), skuPorcaoTabela);
                changeImage(imageUrl);
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

        // Lógica do accordion melhorada
        const accordionToggle = document.querySelector('.accordion-toggle');
        const accordionContent = document.querySelector('.accordion-content');
        let isOpen = false;

        if (accordionToggle && accordionContent) {
            accordionToggle.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const content = accordionContent;

                if (!isOpen) {
                    // Expandir
                    content.style.height = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    // Recolher
                    content.style.height = content.scrollHeight + 'px';
                    // Força um reflow
                    content.offsetHeight;
                    content.style.height = '0';
                    icon.style.transform = 'rotate(0)';
                }

                isOpen = !isOpen;
            });
        }
    });
</script>