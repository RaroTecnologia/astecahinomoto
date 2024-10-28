@extends('layouts.app')

@section('title', 'Receitas da Categoria {{ $categoria->nome }}')

@section('content')
<div class="relative bg-white pb-24">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb e Opção de Compartilhar -->
        <x-breadcrumb-share currentPage="{{ $categoria->nome }}" />

        <!-- Título da Página -->
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-900">Receitas: {{ $categoria->nome }}</h1>
        </div>

        <!-- Listagem de Receitas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($receitas as $receita)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/600x400?text={{ urlencode($receita->nome) }}" alt="{{ $receita->nome }}" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $receita->nome }}</h3>
                    <p class="text-gray-600 text-sm mt-2">{{ Str::limit($receita->ingredientes, 100) }}</p>
                    <a href="{{ route('receitas.show', ['categoria' => $categoria->slug, 'slug' => $receita->slug]) }}" class="text-red-600 font-semibold text-sm mt-4 inline-block">Ver Receita</a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-8">
            {{ $receitas->links() }}
        </div>
    </div>
</div>
@endsection