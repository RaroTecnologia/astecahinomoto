@extends('layouts.admin')

@section('title', 'Criar Nova Tabela Nutricional')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6">Criar Nova Tabela Nutricional</h2>

        <!-- Formulário de Criação -->
        <form action="{{ route('web-admin.tabelas-nutricionais.store') }}" method="POST">
            @csrf

            <!-- Nome da Tabela -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Tabela:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Nutrientes Dinâmicos -->
            <h3 class="text-xl font-semibold mb-4">Nutrientes</h3>
            <div id="nutrientes-container">
                @foreach($nutrientes as $nutriente)
                <div class="flex items-center mb-4 nutrient-row">
                    <div class="w-1/3">
                        <label class="block text-gray-700 font-bold">{{ $nutriente->nome }} ({{ $nutriente->unidade_medida }})</label>
                    </div>
                    <div class="w-1/3">
                        <input type="text" name="nutrientes[{{ $nutriente->id }}][valor_por_100g]" placeholder="Valor por 100g" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="w-1/3">
                        <input type="text" name="nutrientes[{{ $nutriente->id }}][valor_por_porção]" placeholder="Valor por Porção" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Botões -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('web-admin.tabelas-nutricionais.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Salvar Tabela</button>
            </div>
        </form>
    </div>
</div>
@endsection