@extends('layouts.admin')

@section('title', 'Criar Nova Receita')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Criar Nova Receita</h2>

        <!-- Formulário de criação de receita -->
        <form action="{{ route('web-admin.receitas.store') }}" method="POST">
            @csrf

            <!-- Nome da Receita -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Receita:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Categoria da Receita -->
            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 font-bold mb-2">Categoria:</label>
                <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Selecione a Categoria</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Outros campos relevantes (ingredientes, modo de preparo, etc) -->

            <!-- Botão de Submissão -->
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Salvar Receita
                </button>
            </div>
        </form>
    </div>
</div>
@endsection