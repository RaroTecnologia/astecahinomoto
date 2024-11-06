@extends('layouts.admin')

@section('title', isset($noticia) ? 'Editar Notícia' : 'Criar Nova Notícia')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">{{ isset($noticia) ? 'Editar Notícia' : 'Criar Nova Notícia' }}</h1>

    <form action="{{ route('web-admin.noticias.update', $noticia->id) }}" method="POST" enctype="multipart/form-data" id="form-noticia">
        @csrf
        @method('PUT')

        <!-- Campo de Título -->
        <div class="mb-4">
            <label for="titulo" class="block text-gray-700 font-bold mb-2">Título da Notícia:</label>
            <input type="text" name="titulo" id="titulo" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('titulo', $noticia->titulo ?? '') }}" required>
        </div>

        <!-- Campo do Editor -->
        <div class="mb-4">
            <label for="editor-conteudo" class="block text-gray-700 font-bold mb-2">Conteúdo:</label>
            <div id="editor-conteudo" style="height: 300px;">{!! old('conteudo', $noticia->conteudo) !!}</div>
            <input type="hidden" name="conteudo" id="input-conteudo">
        </div>

        <!-- Campo de Categoria -->
        <div class="mb-4">
            <label for="categoria_id" class="block text-gray-700 font-bold mb-2">Categoria:</label>
            <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Selecione uma categoria</option>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ old('categoria_id', $noticia->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nome }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Campo de Imagem -->
        <div class="mb-4">
            <label for="imagem" class="block text-gray-700 font-bold mb-2">Imagem da Notícia:</label>
            <input type="file" name="imagem" id="imagem" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            @if(isset($noticia) && $noticia->imagem)
            <img src="{{ asset('storage/noticias/thumbnails/' . $noticia->imagem) }}" alt="Imagem da notícia" class="mt-4 w-32 h-32 object-cover">
            @endif
        </div>

        <!-- Campo de Status -->
        <div class="mb-4">
            <label for="status" class="block text-gray-700 font-bold mb-2">Status da Notícia:</label>
            <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg">
                <option value="rascunho" {{ (old('status', $noticia->status ?? '') == 'rascunho') ? 'selected' : '' }}>Rascunho</option>
                <option value="publicado" {{ (old('status', $noticia->status ?? '') == 'publicado') ? 'selected' : '' }}>Publicado</option>
                <option value="arquivado" {{ (old('status', $noticia->status ?? '') == 'arquivado') ? 'selected' : '' }}>Arquivado</option>
            </select>
        </div>

        <!-- Campo de Data de Publicação -->
        <div class="mb-4">
            <label for="publicado_em" class="block text-gray-700 font-bold mb-2">Data de Publicação:</label>
            <input
                type="datetime-local"
                name="publicado_em"
                id="publicado_em"
                value="{{ old('publicado_em', $noticia->publicado_em ? date('Y-m-d\TH:i', strtotime($noticia->publicado_em)) : '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-2">Salvar Notícia</button>
            @if(isset($noticia))
            <a href="{{ route('web-admin.noticias.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancelar</a>
            @endif
        </div>
    </form>
</div>
@endsection

@section('scripts')
@vite('resources/js/news-editor.js')
@endsection