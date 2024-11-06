@extends('layouts.admin')

@section('title', 'Gerenciamento de Receitas')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center sticky mb-6">
        <h1 class="text-2xl font-bold">Receitas</h1>

        <!-- Barra de busca e filtros -->
        <form method="GET" action="{{ route('web-admin.receitas.index') }}" class="flex space-x-4 items-center">
            <!-- Filtro por Categoria -->
            <select name="categoria" id="categoria" class="px-4 py-2 border rounded-lg">
                <option value="">Todas as Categorias</option>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nome }}
                </option>
                @endforeach
            </select>

            <!-- Barra de busca com ícone de lupa -->
            <div class="relative">
                <input type="text" name="busca" id="busca" placeholder="Buscar por nome"
                    class="pl-10 pr-4 py-2 border rounded-lg" value="{{ request('busca') }}">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Buscar</button>
            <a href="{{ route('web-admin.receitas.index') }}"
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Limpar Filtros</a>
        </form>

        <!-- Botão para abrir o modal -->
        <button id="openModalBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Nova Receita</button>
    </div>

    <!-- Lista de Receitas -->
    @if($receitas->isEmpty())
    <p class="text-gray-600">Nenhuma receita encontrada.</p>
    @else
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 text-left border-b">Nome</th>
                <th class="py-2 px-4 text-left border-b">Categoria</th>
                <th class="py-2 px-4 text-left border-b">Data de Criação</th>
                <th class="py-2 px-4 text-left border-b">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receitas as $receita)
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2">{{ $receita->nome }}</td>
                <td class="border px-4 py-2">{{ $receita->categoria->nome ?? 'Sem Categoria' }}</td>
                <td class="border px-4 py-2">{{ $receita->created_at->format('d/m/Y') }}</td>
                <td class="border px-4 py-2">
                    <div class="flex space-x-2">
                        <a href="{{ route('web-admin.receitas.edit', $receita->id) }}"
                            class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <form action="{{ route('web-admin.receitas.destroy', $receita->id) }}" method="POST"
                            class="inline-block"
                            onsubmit="return confirm('Tem certeza que deseja excluir esta receita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-2 rounded hover:bg-red-600">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $receitas->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal para criação de Receita -->
<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Criar Nova Receita</h2>
        <form id="recipeForm" action="{{ route('web-admin.receitas.store') }}" method="POST">
            @csrf
            <!-- Campo de Nome -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Receita:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end">
                <button type="submit" id="createAndEditBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Cadastrar e Editar</button>
                <button type="button" id="closeModalBtn" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Abrir o modal
    document.getElementById('openModalBtn').addEventListener('click', function() {
        const modal = document.getElementById('modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    // Fechar o modal
    document.getElementById('closeModalBtn').addEventListener('click', function() {
        const modal = document.getElementById('modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    // Enviar o formulário e redirecionar para a edição
    document.getElementById('createAndEditBtn').addEventListener('click', function(event) {
        event.preventDefault();
        const form = document.getElementById('recipeForm');
        form.action = "{{ route('web-admin.receitas.store') }}?redirect=edit";
        form.submit();
    });

    // Fechar o modal ao clicar fora
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
</script>
@endsection