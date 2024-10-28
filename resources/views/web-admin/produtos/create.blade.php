@extends('layouts.admin')

@section('title', 'Criar Produto')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Criar Produto</h2>

    <form action="{{ route('web-admin.produtos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nome -->
        <div class="mb-4">
            <label for="nome" class="block text-gray-700 font-bold mb-2">Nome do Produto:</label>
            <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <!-- Descrição -->
        <div class="mb-4">
            <label for="descricao" class="block text-gray-700 font-bold mb-2">Descrição:</label>
            <textarea name="descricao" id="descricao" class="w-full px-4 py-2 border rounded-lg"></textarea>
        </div>

        <!-- Subcategoria -->
        <div class="mb-4">
            <label for="subcategoria_id" class="block text-gray-700 font-bold mb-2">Subcategoria:</label>
            <select name="subcategoria_id" id="subcategoria_id" class="w-full px-4 py-2 border rounded-lg">
                @foreach($subcategorias as $subcategoria)
                <option value="{{ $subcategoria->id }}">{{ $subcategoria->nome }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabela Nutricional -->
        <div class="mb-4">
            <label for="tabela_nutricional_id" class="block text-gray-700 font-bold mb-2">Tabela Nutricional:</label>
            <select name="tabela_nutricional_id" id="tabela_nutricional_id" class="w-full px-4 py-2 border rounded-lg">
                @foreach($tabelasNutricionais as $tabela)
                <option value="{{ $tabela->id }}">{{ $tabela->nome }}</option>
                @endforeach
            </select>
        </div>

        <!-- Imagem -->
        <div class="mb-4">
            <label for="imagem" class="block text-gray-700 font-bold mb-2">Imagem do Produto:</label>
            <input type="file" name="imagem" id="imagem" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Botão de submissão -->
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Criar Produto</button>
    </form>
</div>
@endsection