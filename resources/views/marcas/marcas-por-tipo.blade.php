@extends('layouts.app')

@section('title', $tipo->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        :tipo="$tipo"
        currentPage="{{ $tipo->nome }}" />

    <!-- Título da Página -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">{{ $tipo->nome }}</h1>
        <p class="text-gray-600">{{ $tipo->descricao }}</p>
    </div>

    <!-- Listagem de Marcas -->
    <div class="space-y-8">
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($marcas as $marca)
            @php
            // Verifica produtos diretos e subcategorias
            $produtosDiretos = $marca->produtos()->count();
            $produtoDireto = $marca->produtos()->first();
            $temSubcategorias = $marca->children()->exists();

            // Define a rota com base na estrutura
            $rota = (!$temSubcategorias && $produtosDiretos === 1)
            ? route('produtos.show', ['slugMarca' => $marca->slug, 'slugProduto' => $produtoDireto->slug])
            : route('marcas.produtos', [$tipo->slug, $marca->slug]);
            @endphp

            <a href="{{ $rota }}"
                class="block border p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
                <div class="flex flex-col h-full">
                    <div class="mb-4 flex justify-center">
                        <img src="{{ $marca->imagem ? asset('storage/categorias/' . $marca->imagem) : asset('assets/sem_imagem.png') }}"
                            alt="{{ $marca->nome }}"
                            class="h-56 object-contain">
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">
                        {{ $marca->nome }}
                    </h2>
                    <p class="text-gray-600 flex-grow text-center">
                        {{ $marca->descricao ?? 'Descrição indisponível' }}
                    </p>

                    <!-- Tipos Relacionados -->
                    <div class="mt-4 flex flex-wrap gap-2 justify-center">
                        @foreach($marca->tipos as $tipoRelacionado)
                        <span class="px-2 py-1 text-sm rounded-full text-{{ $tipoRelacionado->cor_texto }}"
                            style="background-color: #{{ $tipoRelacionado->cor_bg }};">
                            {{ $tipoRelacionado->nome }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection