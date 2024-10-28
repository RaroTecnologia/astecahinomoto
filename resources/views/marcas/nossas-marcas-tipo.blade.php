@extends('layouts.app')

@section('title', $tipo->nome)

@section('content')
<div class="container mx-auto py-16 px-4">
    <!-- Breadcrumb -->
    <x-breadcrumb-share currentPage="{{ $categoriaTipo->nome }}" parentPage="Nossas Marcas" />

    <!-- Título da Página -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-700">{{ $tipo->nome }}</h1>
        <p class="text-gray-600">{{ $tipo->descricao }}</p>
    </div>

    <!-- Listagem de Categorias -->
    <div class="space-y-8">
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($categorias as $categoria)
            <a href="{{ route('marcas.linhas', ['slugMarca' => $categoria->slug]) }}" class="block border p-6 rounded-lg shadow-lg hover:bg-gray-50 transition duration-200 w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] max-w-sm">
                <div class="flex flex-col h-full">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2 text-center">{{ $categoria->nome }}</h2>
                    <p class="text-gray-600 flex-grow text-center">{{ $categoria->descricao }}</p>
                    <span class="text-red-600 font-semibold hover:underline mt-4 text-center">Ver mais detalhes</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>


</div>
</div>
@endsection