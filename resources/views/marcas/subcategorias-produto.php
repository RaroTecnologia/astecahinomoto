@extends('layouts.app')

@section('title', $produto->nome . ' - ' . $marca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <x-breadcrumb-share :currentPage="$produto->nome" :parentPage="$marca->nome" />

    <!-- Título do Produto -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">{{ $produto->nome }}</h1>
        <p class="text-gray-600">{{ $produto->descricao }}</p>
    </div>

    <!-- Listagem de Subcategorias -->
    <div class="flex flex-wrap justify-center gap-8">
        @foreach($subcategorias as $subcategoria)
        <a href="{{ route('nossas-marcas.produtos.linhas', ['tipoSlug' => $tipo->slug, 'slugMarca' => $marca->slug, 'slugProduto' => $subcategoria->slug]) }}" class="border p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
            <div class="flex flex-col h-full">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $subcategoria->nome }}</h2>
                <p class="text-gray-600 flex-grow text-center">{{ $subcategoria->descricao }}</p>
                <span class="text-red-600 font-semibold hover:underline mt-4 block text-center">Ver mais</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection