@extends('layouts.admin')

@section('title', 'Adicionar Nutriente')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6">Adicionar Novo Nutriente</h2>

        <!-- Formulário de criação -->
        <form action="{{ route('web-admin.nutrientes.store') }}" method="POST">
            @csrf

            <!-- Nome do Nutriente -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome do Nutriente:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Unidade de Medida -->
            <div class="mb-4">
                <label for="unidade_medida" class="block text-gray-700 font-bold mb-2">Unidade de Medida:</label>
                <input type="text" name="unidade_medida" id="unidade_medida" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Tipo de Nutriente -->
            <div class="mb-4">
                <label for="tipo_nutriente" class="block text-gray-700 font-bold mb-2">Tipo de Nutriente:</label>
                <input type="text" name="tipo_nutriente" id="tipo_nutriente" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Botões -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('web-admin.nutrientes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection