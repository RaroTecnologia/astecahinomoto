<!-- Função recursiva para listar categorias no select -->
@php
function renderCategoriaOptions($categorias, $level = 0, $parent_id = null) {
$indent = str_repeat('&mdash; ', $level);

foreach ($categorias as $categoria) {
echo '<option value="' . $categoria->id . '" ' . ($categoria->id == $parent_id ? ' selected' : '' ) . '>' . $indent . $categoria->nome . '</option>';

// Verifica se há subcategorias e as renderiza recursivamente
if ($categoria->subcategorias->count()) {
renderCategoriaOptions($categoria->subcategorias, $level + 1, $parent_id);
}
}
}
@endphp
@extends('layouts.admin')

@section('title', 'Editar Categoria')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6">Editar Categoria</h2>

        <!-- Formulário de edição de categoria -->
        <form action="{{ route('web-admin.categorias.update', $categoria->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nome da Categoria -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Categoria:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('nome') border-red-500 @enderror" value="{{ old('nome', $categoria->nome) }}" required>
                @error('nome')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoria Mãe -->
            <div class="mb-4">
                <label for="parent_id" class="block text-gray-700 font-bold mb-2">Categoria Mãe (Opcional):</label>
                <select name="parent_id" id="parent_id" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">-- Nenhuma --</option>
                    {!! renderCategoriaOptions($categorias, 0, $categoria->parent_id) !!}
                </select>
            </div>

            <!-- Descrição -->
            <div class="mb-4">
                <label for="descricao" class="block text-gray-700 font-bold mb-2">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="3" class="w-full px-4 py-2 border rounded-lg resize-none">{{ old('descricao', $categoria->descricao ?? '') }}</textarea>
            </div>

            <!-- Campo de Upload de Imagem -->
            <div class="mb-4">
                <label for="imagem" class="block text-gray-700 font-bold mb-2">Imagem:</label>
                <input type="file" name="imagem" id="imagem" class="w-full px-4 py-2 border rounded-lg @error('imagem') border-red-500 @enderror">
                @error('imagem')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Exibe a imagem atual se existir -->
                @if ($categoria->imagem)
                <img src="{{ asset('storage/categorias/' . $categoria->imagem) }}" alt="Imagem da Categoria" class="w-32 h-32 mt-4 rounded-lg object-cover">
                @endif
            </div>

            <!-- Tipo da Categoria -->
            <div class="mb-4">
                <label for="tipo" class="block text-gray-700 font-bold mb-2">Tipo de Categoria:</label>
                <select name="tipo" id="tipo" class="w-full px-4 py-2 border rounded-lg @error('tipo') border-red-500 @enderror">
                    <option value="produto" {{ old('tipo', $categoria->tipo) == 'produto' ? 'selected' : '' }}>Produto</option>
                    <option value="noticia" {{ old('tipo', $categoria->tipo) == 'noticia' ? 'selected' : '' }}>Notícia</option>
                    <option value="receita" {{ old('tipo', $categoria->tipo) == 'receita' ? 'selected' : '' }}>Receita</option>
                </select>
                @error('tipo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nível da Categoria -->
            <div class="mb-4">
                <label for="nivel" class="block text-gray-700 font-bold mb-2">Nível:</label>
                <select name="nivel" id="nivel" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione</option>
                    <option value="marca" {{ old('nivel', $categoria->nivel) == 'marca' ? 'selected' : '' }}>Marca</option>
                    <option value="produto" {{ old('nivel', $categoria->nivel) == 'produto' ? 'selected' : '' }}>Produto</option>
                    <option value="linha" {{ old('nivel', $categoria->nivel) == 'linha' ? 'selected' : '' }}>Linha</option>
                </select>
                @error('nivel')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('web-admin.categorias.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection