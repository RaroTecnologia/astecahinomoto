@extends('layouts.app')

@section('title', 'Produtos de ' . $produtoOuLinha->nome . ' - ' . $marca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share currentPage="{{ $produtoOuLinha->nome }}" parentPage="{{ $marca->nome }}" />

    <!-- Título da Página -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">Produtos de {{ $produtoOuLinha->nome }}</h1>
        <p class="text-gray-600">{{ $produtoOuLinha->descricao ?? 'Explore os produtos disponíveis.' }}</p>
    </div>

    <!-- Listagem de Produtos Finais -->
    <div class="space-y-8">
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($produtosFinais as $produtoFinal)
            @php
            // Verifica se o produto tem SKUs e usa a imagem do primeiro SKU, se disponível
            $skuImagem = $produtoFinal->skus->first()->imagem ?? null;
            @endphp
            <a href="{{ route('produtos.show', ['slugMarca' => $marca->slug, 'slugProduto' => $produtoFinal->slug]) }}"
                class="block border p-6 rounded-lg shadow-lg hover:shadow-xl bg-white transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
                <div class="flex flex-col h-full">
                    <!-- Se o SKU tiver imagem, usa ela; caso contrário, usa a imagem do produto ou uma padrão -->
                    <img src="{{ $skuImagem ? asset('storage/skus/' . $skuImagem) : ($produtoFinal->imagem ? asset('storage/produtos/' . $produtoFinal->imagem) : asset('assets/sem_imagem.png')) }}"
                        alt="{{ $produtoFinal->nome }}"
                        class="w-full h-56 object-contain mb-4 rounded">

                    <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $produtoFinal->nome }}</h2>
                    <p class="text-gray-600 flex-grow text-center">{{ $produtoFinal->descricao ?? 'Descrição indisponível' }}</p>
                    <span class="text-red-600 font-semibold hover:underline mt-4 block text-center">Ver mais detalhes</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection