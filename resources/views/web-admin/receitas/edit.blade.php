@extends('layouts.admin')

@section('title', 'Editar Receita')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-8">Editar Receita</h2>

        <form action="{{ route('web-admin.receitas.update', $receita->id) }}" method="POST" enctype="multipart/form-data" id="form-receita" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Grid de duas colunas com mais espaçamento -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Coluna Esquerda -->
                <div class="space-y-6"> <!-- Adicionado space-y-6 para espaçamento vertical consistente -->
                    <!-- Nome da Receita -->
                    <div class="mb-4">
                        <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Receita:</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome', $receita->nome) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <!-- Categoria -->
                    <div class="mb-4">
                        <label for="categoria_id" class="block text-gray-700 font-bold mb-2">Categoria:</label>
                        <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $receita->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 font-bold mb-2">Status:</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Selecione um status</option>
                            <option value="rascunho" {{ old('status', $receita->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ old('status', $receita->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                            <option value="arquivado" {{ old('status', $receita->status) == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                        </select>
                    </div>

                    <!-- Dificuldade -->
                    <div class="mb-4">
                        <label for="dificuldade" class="block text-gray-700 font-bold mb-2">Dificuldade:</label>
                        <select name="dificuldade" id="dificuldade" class="w-full px-4 py-2 border rounded-lg">
                            <option value="fácil" {{ old('dificuldade', $receita->dificuldade) == 'fácil' ? 'selected' : '' }}>Fácil</option>
                            <option value="médio" {{ old('dificuldade', $receita->dificuldade) == 'médio' ? 'selected' : '' }}>Médio</option>
                            <option value="difícil" {{ old('dificuldade', $receita->dificuldade) == 'difícil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                    </div>

                    <!-- Tempo de Preparo -->
                    <div class="mb-4">
                        <label for="tempo_preparo" class="block text-gray-700 font-bold mb-2">Tempo de Preparo:</label>
                        <input type="text" name="tempo_preparo" id="tempo_preparo"
                            value="{{ old('tempo_preparo', $receita->tempo_preparo) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                            placeholder="Ex: 30 minutos">
                    </div>

                    <!-- Chamada -->
                    <div class="mb-4">
                        <label for="chamada" class="block text-gray-700 font-bold mb-2">Chamada:</label>
                        <textarea
                            name="chamada"
                            id="chamada"
                            rows="4"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Digite uma breve descrição da receita">{{ old('chamada', $receita->chamada) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Breve descrição que aparecerá na listagem de receitas</p>
                    </div>

                    <!-- Imagem -->
                    <div class="mb-4">
                        <label for="imagem" class="block text-gray-700 font-bold mb-2">Imagem:</label>
                        <input type="file" name="imagem" id="imagem"
                            class="w-full px-4 py-2 border rounded-lg">
                        @if($receita->imagem)
                        <img src="{{ asset('storage/receitas/thumbnails/' . $receita->imagem) }}"
                            alt="Imagem da receita"
                            class="mt-2 w-32 h-32 object-cover rounded-lg shadow-sm"> <!-- Adicionado rounded-lg e shadow-sm -->
                        @endif
                    </div>

                    <!-- URL do Vídeo -->
                    <div class="mb-4">
                        <label for="video_url" class="block text-gray-700 font-bold mb-2">URL do Vídeo:</label>
                        <input type="url"
                            name="video_url"
                            id="video_url"
                            value="{{ old('video_url', $receita->video_url) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ex: https://www.youtube.com/watch?v=...">
                        <p class="text-sm text-gray-500 mt-1">Cole aqui o link do vídeo do YouTube</p>
                    </div>
                </div>

                <!-- Coluna Direita -->
                <div class="space-y-6"> <!-- Adicionado space-y-6 para espaçamento vertical consistente -->
                    <!-- Ingredientes -->
                    <div class="mb-4">
                        <label for="editor-ingredientes" class="block text-gray-700 font-bold mb-2">Ingredientes:</label>
                        <div id="editor-ingredientes" style="height: 250px;">{!! old('ingredientes', $receita->ingredientes) !!}</div>
                        <input type="hidden" name="ingredientes" id="input-ingredientes" value="{{ old('ingredientes', $receita->ingredientes) }}">
                    </div>

                    <!-- Modo de Preparo -->
                    <div class="mb-4">
                        <label for="editor-modo-preparo" class="block text-gray-700 font-bold mb-2">Modo de Preparo:</label>
                        <div id="editor-modo-preparo" style="height: 250px;">{!! old('modo_preparo', $receita->modo_preparo) !!}</div>
                        <input type="hidden" name="modo_preparo" id="input-modo-preparo" value="{{ old('modo_preparo', $receita->modo_preparo) }}">
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end mt-8"> <!-- Aumentado margin-top -->
                <a href="{{ route('web-admin.receitas.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-700 mr-4">Cancelar</a>
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/recipe-editor.js'])
@endsection