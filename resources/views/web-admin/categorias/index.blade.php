<!-- Função recursiva para listar categorias no select -->
@php
function renderCategoriaOptions($categorias, $level = 0, $selectedParentId = null) {
// Define a indentação com base no nível da categoria
$indent = str_repeat('&mdash; ', $level);

foreach ($categorias as $categoria) {
echo '<option value="' . $categoria->id . '" ' . ($categoria->id == $selectedParentId ? ' selected' : '' ) . '>' . $indent . $categoria->nome . '</option>';

// Verifica se há subcategorias e as renderiza recursivamente
if ($categoria->subcategorias->count()) {
renderCategoriaOptions($categoria->subcategorias, $level + 1, $selectedParentId);
}
}
}
@endphp

@extends('layouts.admin')

@section('title', 'Gerenciar Categorias')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-start space-x-8">
        <!-- Formulário de criação de categoria (lado esquerdo) -->
        <div class="w-1/3 bg-white p-4 rounded-lg shadow-md sticky top-4">
            <h2 class="text-xl font-bold mb-6">Adicionar nova categoria</h2>
            <form action="{{ route('web-admin.categorias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nome da Categoria -->
                <div class="mb-4">
                    <label for="nome" class="block text-gray-700 font-bold mb-2">Nome:</label>
                    <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Categoria Ascendente (opcional) -->
                <div class="mb-4">
                    <label for="parent_id" class="block text-gray-700 font-bold mb-2">Categoria Ascendente:</label>
                    <select name="parent_id" id="parent_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Nenhuma</option>
                        {!! renderCategoriaOptions($categorias, 0, old('parent_id')) !!}
                    </select>
                    <small class="text-gray-500">Escolha uma categoria mãe se quiser criar uma subcategoria.</small>
                </div>

                <!-- Descrição da Categoria -->
                <div class="mb-4">
                    <label for="descricao" class="block text-gray-700 font-bold mb-2">Descrição:</label>
                    <textarea name="descricao" id="descricao" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                </div>

                <!-- Tipo de Categoria -->
                <div class="mb-4">
                    <label for="tipo" class="block text-gray-700 font-bold mb-2">Tipo:</label>
                    <select name="tipo" id="tipo" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Selecione</option>
                        <option value="noticia">Notícia</option>
                        <option value="receita">Receita</option>
                        <option value="produto">Produto</option>
                    </select>
                </div>

                <!-- Nível da Categoria -->
                <div class="mb-4">
                    <label for="nivel" class="block text-gray-700 font-bold mb-2">Nível:</label>
                    <select name="nivel" id="nivel" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Selecione</option>
                        <option value="marca">Marca</option>
                        <option value="produto">Produto</option>
                        <option value="linha">Linha</option>
                    </select>
                </div>

                <!-- Tipo ID (Relacionamento) -->
                <div class="mb-4" id="tipo_id_container">
                    <label for="tipo_id" class="block text-gray-700 font-bold mb-2">Tipo Relacionado:</label>
                    <select name="tipo_id" id="tipo_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Selecione o tipo</option>
                        @if(isset($tipos) && $tipos->count() > 0)
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                        @endforeach
                        @endif
                    </select>
                    <small class="text-gray-500">Selecione o tipo principal da marca</small>
                </div>

                <!-- Is Principal -->
                <div class="mb-4" id="is_principal_container">
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                            name="is_principal"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring-green-500"
                            value="1"
                            checked>
                        <span class="ml-2 text-sm text-gray-600">É tipo principal?</span>
                    </label>
                    <small class="block text-gray-500">Marque se este é o tipo principal da marca</small>
                </div>

                <!-- Botão de Submissão -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Adicionar Categoria
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Categorias (lado direito) -->
        <div class="w-2/3 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-6">Lista de Categorias</h2>

            @if($categorias->isEmpty())
            <p class="text-gray-600">Nenhuma categoria encontrada.</p>
            @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 text-left">Nome</th>
                        <th class="py-2 text-left">Slug</th>
                        <th class="py-2 text-left">Tipo</th>
                        <th class="py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Função recursiva para exibir categorias e subcategorias -->
                    @foreach($categorias as $categoria)
                    @include('web-admin.categorias._categoria-row', ['categoria' => $categoria, 'level' => 0])
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nivelSelect = document.getElementById('nivel');
        const tipoIdContainer = document.getElementById('tipo_id_container');
        const isPrincipalContainer = document.getElementById('is_principal_container');

        if (nivelSelect && tipoIdContainer && isPrincipalContainer) {
            function toggleTipoFields() {
                const isMarca = nivelSelect.value === 'marca';
                tipoIdContainer.style.display = isMarca ? 'block' : 'none';
                isPrincipalContainer.style.display = isMarca ? 'block' : 'none';
            }

            nivelSelect.addEventListener('change', toggleTipoFields);
            toggleTipoFields(); // Execute on page load
        } else {
            console.error('Um ou mais elementos não foram encontrados');
        }
    });
</script>
@endpush