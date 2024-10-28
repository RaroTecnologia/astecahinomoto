@php
function renderCategoriaOptions($categorias, $level = 0, $selectedCategoria = null) {
$indent = str_repeat('&mdash; ', $level);

foreach ($categorias as $categoria) {
echo '<option value="' . $categoria->id . '" ' . ($categoria->id == $selectedCategoria ? ' selected' : '' ) . '>' . $indent . $categoria->nome . '</option>';

// Verifica se há subcategorias e as renderiza recursivamente
if ($categoria->subcategorias->count()) {
renderCategoriaOptions($categoria->subcategorias, $level + 1, $selectedCategoria);
}
}
}
@endphp

@extends('layouts.admin')

@section('title', 'Gerenciar Produtos')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center sticky mb-6">
        <h1 class="text-2xl font-bold">Produtos</h1>

        <!-- Barra de busca e filtros -->
        <form method="GET" action="{{ route('web-admin.produtos.index') }}" class="flex space-x-4 items-center">
            <!-- Filtro por Categoria -->
            <select name="categoria" id="categoria" class="px-4 py-2 border rounded-lg">
                <option value="">Todas as Categorias</option>
                {!! renderCategoriaOptions($categorias, 0, request('categoria')) !!}
            </select>

            <!-- Barra de busca com ícone de lupa -->
            <div class="relative">
                <input type="text" name="busca" id="busca" placeholder="Buscar por nome" class="pl-10 pr-4 py-2 border rounded-lg" value="{{ request('busca') }}">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
            </div>

            <!-- Botão de busca -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Buscar</button>

            <!-- Botão de limpar filtros -->
            <a href="{{ route('web-admin.produtos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Limpar Filtros</a>
        </form>

        <!-- Botão para abrir o modal -->
        <button id="openModalBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Adicionar Novo Produto</button>
    </div>

    <!-- Lista de Produtos -->
    @if($produtos->isEmpty())
    <p class="text-gray-600">Nenhum produto encontrado.</p>
    @else
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 text-left">Nome</th>
                <th class="py-2 text-left">Categoria</th>
                <th class="py-2 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
            <tr>
                <td class="border px-4 py-2">{{ $produto->nome }}</td>
                <td class="border px-4 py-2">{{ $produto->categoria->nome ?? 'Sem Categoria' }}</td>
                <td class="border px-4 py-2 flex space-x-2">
                    <!-- Botão Editar -->
                    <a href="{{ route('web-admin.produtos.edit', $produto->id) }}" class="bg-blue-500 text-white p-2 rounded">
                        <i class="fas fa-pencil-alt"></i>
                    </a>

                    <!-- Botão Excluir -->
                    <form action="{{ route('web-admin.produtos.destroy', $produto->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white p-2 rounded">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $produtos->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal para criação do Produto -->
<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Criar Novo Produto</h2>
        <form id="productForm" action="{{ route('web-admin.produtos.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome do Produto:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Categoria -->
            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 font-bold mb-2">Categoria (opcional):</label>
                <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <!-- Botão Cadastrar -->
                <button type="submit" id="createBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-2">Cadastrar</button>

                <!-- Botão Cadastrar e Editar -->
                <button type="submit" id="createAndEditBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Cadastrar e Editar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Abre o modal
    document.getElementById('openModalBtn').addEventListener('click', function() {
        const modal = document.getElementById('modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Adiciona flex ao abrir o modal
    });

    // Define a ação do botão Cadastrar e Editar
    document.getElementById('createAndEditBtn').addEventListener('click', function(event) {
        event.preventDefault();
        const form = document.getElementById('productForm');
        form.action = "{{ route('web-admin.produtos.store') }}?redirect=edit";
        form.submit();
    });

    // Fecha o modal ao clicar fora
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex'); // Remove flex ao fechar o modal
        }
    });
</script>
@endsection