@extends('layouts.admin')

@section('title', 'Gerenciar Notícias')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center sticky mb-6">
        <h1 class="text-2xl font-bold">Notícias</h1>

        <!-- Barra de busca -->
        <form method="GET" action="{{ route('web-admin.noticias.index') }}" class="flex space-x-4 items-center">
            <input type="text" name="busca" id="busca" placeholder="Buscar por título" class="pl-10 pr-4 py-2 border rounded-lg" value="{{ request('busca') }}">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Buscar</button>
        </form>

        <!-- Botão para abrir o modal de criação -->
        <button id="openModalBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Adicionar Nova Notícia</button>
    </div>

    @if($noticias->isEmpty())
    <p class="text-gray-600">Nenhuma notícia encontrada.</p>
    @else
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 text-left">Título</th>
                <th class="py-2 text-left">Slug</th>
                <th class="py-2 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($noticias as $noticia)
            <tr>
                <td class="border px-4 py-2">{{ $noticia->titulo }}</td>
                <td class="border px-4 py-2">{{ $noticia->slug }}</td>
                <td class="border px-4 py-2 flex space-x-2">
                    <a href="{{ route('web-admin.noticias.edit', $noticia->id) }}" class="bg-blue-500 text-white p-2 rounded">
                        <i class="fas fa-pencil-alt"></i>
                    </a>

                    <form action="{{ route('web-admin.noticias.destroy', $noticia->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta notícia?');">
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

    <div class="mt-6">
        {{ $noticias->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal para criação de Notícia (somente título) -->
<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Criar Nova Notícia</h2>

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="newsForm" action="{{ route('web-admin.noticias.store') }}" method="POST">
            @csrf
            <!-- Campo de Título -->
            <div class="mb-4">
                <label for="titulo" class="block text-gray-700 font-bold mb-2">Título:</label>
                <input type="text" name="titulo" id="titulo" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM carregado, iniciando setup do modal...');

        const modal = document.getElementById('modal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const createAndEditBtn = document.getElementById('createAndEditBtn');
        const newsForm = document.getElementById('newsForm');

        // Verificar se todos os elementos foram encontrados
        console.log('Elementos encontrados:', {
            modal: !!modal,
            openModalBtn: !!openModalBtn,
            closeModalBtn: !!closeModalBtn,
            createAndEditBtn: !!createAndEditBtn,
            newsForm: !!newsForm
        });

        // Abrir o modal
        openModalBtn.addEventListener('click', function() {
            console.log('Abrindo modal...');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        // Fechar o modal
        closeModalBtn.addEventListener('click', function() {
            console.log('Fechando modal...');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            newsForm.reset(); // Limpa o formulário ao fechar
        });

        // Enviar o formulário
        createAndEditBtn.addEventListener('click', function(event) {
            event.preventDefault();
            console.log('Tentando enviar formulário...');

            const titulo = document.getElementById('titulo').value;
            console.log('Título:', titulo);

            if (!titulo) {
                console.error('Título é obrigatório');
                alert('O título é obrigatório');
                return;
            }

            try {
                console.log('URL do formulário:', newsForm.action);
                newsForm.action = "{{ route('web-admin.noticias.store') }}?redirect=edit";
                console.log('Nova URL do formulário:', newsForm.action);

                newsForm.submit();
            } catch (error) {
                console.error('Erro ao submeter formulário:', error);
            }
        });

        // Fechar modal clicando fora
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                console.log('Fechando modal por clique externo...');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                newsForm.reset();
            }
        });
    });
</script>
@endsection