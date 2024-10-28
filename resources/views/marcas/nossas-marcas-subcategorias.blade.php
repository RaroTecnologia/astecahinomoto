@extends('layouts.app')

@section('title', $linhaPrincipal->nome . ' - ' . $categoriaMarca->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share
        currentPage="{{ $linhaPrincipal->nome }}"
        parentPage="{{ $categoriaMarca->nome }}" />

    <!-- Título da Linha -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">{{ $linhaPrincipal->nome }}</h1>
        <p class="text-gray-600">{{ $linhaPrincipal->descricao }}</p>
    </div>

    <!-- Listagem de Subcategorias -->
    <div class="flex flex-wrap justify-center gap-8">
        @foreach($subcategorias as $subcategoria)
        <a href="{{ route('nossas-marcas.linhas.produtos', ['slugMarca' => $categoriaMarca->slug, 'slugLinha' => $linhaPrincipal->slug, 'subSlug' => $subcategoria->slug]) }}" class="border p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
            <div class="flex flex-col h-full">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $subcategoria->nome }}</h2>
                <p class="text-gray-600 flex-grow text-center">{{ $subcategoria->descricao }}</p>
                <span class="text-red-600 font-semibold hover:underline mt-4 block text-center">Ver produtos</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection