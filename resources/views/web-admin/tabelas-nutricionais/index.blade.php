@extends('layouts.admin')

@section('title', 'Tabelas Nutricionais')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tabelas Nutricionais</h1>
        <!-- Botão para abrir o modal -->
        <button id="openModalBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Adicionar Nova Tabela</button>
    </div>

    <!-- Lista de Tabelas Nutricionais -->
    @if($tabelasNutricionais->isEmpty())
    <p class="text-gray-600">Nenhuma tabela nutricional encontrada.</p>
    @else
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 text-left">Nome</th>
                <th class="py-2 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabelasNutricionais as $tabela)
            <tr>
                <td class="border px-4 py-2">{{ $tabela->nome }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('web-admin.tabelas-nutricionais.edit', $tabela->id) }}" class="text-blue-500 hover:underline">Editar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<!-- Modal para criação da Tabela Nutricional -->
<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Criar Nova Tabela Nutricional</h2>
        <form id="nutritionTableForm" action="{{ route('web-admin.tabelas-nutricionais.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Tabela:</label>
                <input type="text" name="nome" id="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
        document.getElementById('modal').classList.remove('hidden');
    });

    // Define a ação do botão Cadastrar e Editar
    document.getElementById('createAndEditBtn').addEventListener('click', function(event) {
        event.preventDefault();
        const form = document.getElementById('nutritionTableForm');
        form.action = "{{ route('web-admin.tabelas-nutricionais.store') }}?redirect=edit";
        form.submit();
    });
</script>
@endsection