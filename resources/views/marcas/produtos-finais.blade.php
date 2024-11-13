@extends('layouts.app')

@section('title', 'Produtos de ' . $produtoOuLinha->nome . ' - ' . $marca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        :tipo="$tipo"
        :marca="$marca"
        :linha="$produtoOuLinha->categoria"
        :produto="$produtoOuLinha"
        currentPage="" />

    <!-- Título da Página -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">Produtos de {{ $produtoOuLinha->nome }}</h1>
        <p class="text-gray-600">{{ $produtoOuLinha->descricao ?? 'Explore os produtos disponíveis.' }}</p>
    </div>

    <!-- Listagem de Produtos Finais -->
    <div class="space-y-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
            @forelse($produtosFinais as $produtoFinal)
            @if($produtoFinal)
            @php
            $imagemPrincipal = asset('assets/sem_imagem.png');
            if ($produtoFinal && $produtoFinal->imagem) {
            $imagemPrincipal = asset('storage/produtos/thumbnails/' . $produtoFinal->imagem);
            } elseif ($produtoFinal && $produtoFinal->skus && $produtoFinal->skus->first() && $produtoFinal->skus->first()->imagem) {
            $imagemPrincipal = asset('storage/skus/thumbnails/' . $produtoFinal->skus->first()->imagem);
            }
            @endphp

            <a href="{{ route('produtos.show', ['slugMarca' => $marca->slug, 'slugProduto' => $produtoFinal->slug]) }}"
                class="block border p-4 md:p-6 rounded-lg shadow-lg hover:shadow-xl bg-white transition duration-200">
                <div class="flex flex-col h-full">
                    <!-- Imagem Principal -->
                    <img src="{{ $imagemPrincipal }}"
                        alt="{{ $produtoFinal->nome ?? 'Produto sem nome' }}"
                        class="w-full h-40 md:h-48 lg:h-56 object-contain mb-4 rounded">

                    <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-2 text-center">
                        {{ $produtoFinal->nome ?? 'Produto sem nome' }}
                    </h2>
                    <p class="text-sm md:text-base text-gray-600 flex-grow text-center line-clamp-2">
                        {{ $produtoFinal->descricao ?? 'Descrição indisponível' }}
                    </p>

                    <!-- Badges de SKUs -->
                    @if($produtoFinal->skus && $produtoFinal->skus->isNotEmpty())
                    <div class="flex flex-wrap gap-1 md:gap-2 justify-center mt-3 mb-3">
                        @foreach($produtoFinal->skus as $sku)
                        @if($sku)
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs md:text-sm rounded-full">
                            {{ $sku->quantidade ?? 'N/A' }}
                        </span>
                        @endif
                        @endforeach
                    </div>
                    @endif

                    <span class="text-vermelho-asteca text-sm md:text-base font-semibold rounded-full px-3 py-1.5 md:px-4 md:py-2 border border-vermelho-asteca hover:text-white hover:bg-vermelho-asteca transition-colors duration-200 mt-4 block text-center">
                        Ver mais detalhes
                    </span>
                </div>
            </a>
            @endif
            @empty
            <div class="col-span-full text-center text-gray-600 py-8">
                Nenhum produto encontrado.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection