@extends('layouts.admin')

@section('title', 'Editar Receita')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6">Editar Receita</h2>

        <form action="{{ route('web-admin.receitas.update', $receita->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Receita:</label>
                <input type="text" name="nome" id="nome" value="{{ $receita->nome }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 font-bold mb-2">Categoria:</label>
                <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 border rounded-lg">
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $receita->categoria_id == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="dificuldade" class="block text-gray-700 font-bold mb-2">Dificuldade:</label>
                <select name="dificuldade" id="dificuldade" class="w-full px-4 py-2 border rounded-lg">
                    <option value="fácil" {{ $receita->dificuldade == 'fácil' ? 'selected' : '' }}>Fácil</option>
                    <option value="médio" {{ $receita->dificuldade == 'médio' ? 'selected' : '' }}>Médio</option>
                    <option value="difícil" {{ $receita->dificuldade == 'difícil' ? 'selected' : '' }}>Difícil</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tempo_preparo" class="block text-gray-700 font-bold mb-2">Tempo de Preparo:</label>
                <input type="text" name="tempo_preparo" id="tempo_preparo" value="{{ $receita->tempo_preparo }}" class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: 30 minutos">
            </div>

            <div class="mb-4">
                <label for="ingredientes" class="block text-gray-700 font-bold mb-2">Ingredientes:</label>
                <div id="editor-ingredientes" class="quill-editor"></div>
            </div>

            <div class="mb-4">
                <label for="modo_preparo" class="block text-gray-700 font-bold mb-2">Modo de Preparo:</label>
                <div id="editor-modo-preparo" class="quill-editor"></div>
            </div>

            <input type="hidden" name="ingredientes" id="ingredientes">
            <input type="hidden" name="modo_preparo" id="modo_preparo">

            <div class="flex justify-end mt-6">
                <a href="{{ route('web-admin.receitas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection