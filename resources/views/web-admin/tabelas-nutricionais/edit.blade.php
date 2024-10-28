@extends('layouts.admin')

@section('title', 'Editar Tabela Nutricional')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between space-x-8">
        <!-- Coluna da esquerda: Listagem de Nutrientes -->
        <div class="w-1/3 bg-white p-4 rounded-lg shadow-md sticky top-4 h-screen overflow-auto">
            <h2 class="text-xl font-bold mb-6">Nutrientes Disponíveis</h2>
            <ul class="space-y-4">
                @foreach($nutrientes as $nutriente)
                <li class="flex justify-between items-center">
                    <span>{{ $nutriente->nome }} ({{ $nutriente->unidade_medida }})</span>
                    <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded add-nutrient-btn" data-id="{{ $nutriente->id }}" data-nome="{{ $nutriente->nome }}" data-unidade="{{ $nutriente->unidade_medida }}">
                        Adicionar
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Coluna da direita: Formulário de Edição -->
        <div class="w-2/3 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Editar Tabela Nutricional</h2>

            <!-- Formulário de Edição -->
            <form id="nutrition-form" action="{{ route('web-admin.tabelas-nutricionais.update', $tabelaNutricional->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nome da Tabela -->
                <div class="mb-4">
                    <label for="nome" class="block text-gray-700 font-bold mb-2">Nome da Tabela:</label>
                    <input type="text" name="nome" id="nome" value="{{ $tabelaNutricional->nome }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Nutrientes Dinâmicos -->
                <h3 class="text-xl font-semibold mb-4">Nutrientes Selecionados</h3>
                <div id="nutrientes-container">
                    @foreach($tabelaNutricional->nutrientes as $nutriente)
                    <div class="flex items-center mb-4 nutrient-row" data-id="{{ $nutriente->id }}">
                        <input type="hidden" name="nutrientes[{{ $nutriente->id }}][id]" value="{{ $nutriente->id }}">
                        <div class="w-1/4">
                            <label class="block text-gray-700 font-bold">{{ $nutriente->nome }} ({{ $nutriente->unidade_medida }})</label>
                        </div>
                        <div class="w-1/4">
                            <input type="text" name="nutrientes[{{ $nutriente->id }}][valor_por_100g]" value="{{ number_format($nutriente->pivot->valor_por_100g, 2, ',', '.') }}" placeholder="Valor por 100g" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div class="w-1/4">
                            <input type="text" name="nutrientes[{{ $nutriente->id }}][valor_por_porção]" value="{{ number_format($nutriente->pivot->valor_por_porção, 2, ',', '.') }}" placeholder="Valor por Porção" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div class="w-1/4">
                            <input type="text" name="nutrientes[{{ $nutriente->id }}][valor_diario]" value="{{ number_format($nutriente->pivot->valor_diario, 2, ',', '.') }}" placeholder="%VD" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <button type="button" class="text-red-500 ml-4 remove-nutrient">Excluir</button>
                    </div>
                    @endforeach
                </div>

                <!-- Botões -->
                <div class="flex justify-end mt-6">
                    <a href="{{ route('web-admin.tabelas-nutricionais.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.add-nutrient-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const unidade = this.getAttribute('data-unidade');

            // Verifica se o nutriente já foi adicionado
            if (document.querySelector(`.nutrient-row[data-id="${id}"]`)) {
                alert('Este nutriente já foi adicionado.');
                return;
            }

            const container = document.querySelector('#nutrientes-container');
            const nutrientRow = `
                <div class="flex items-center mb-4 nutrient-row" data-id="${id}">
                    <input type="hidden" name="nutrientes[${id}][id]" value="${id}">
                    <div class="w-1/4">
                        <label class="block text-gray-700 font-bold">${nome} (${unidade})</label>
                    </div>
                    <div class="w-1/4">
                        <input type="text" name="nutrientes[${id}][valor_por_100g]" placeholder="Valor por 100g" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="w-1/4">
                        <input type="text" name="nutrientes[${id}][valor_por_porção]" placeholder="Valor por Porção" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="w-1/4">
                        <input type="text" name="nutrientes[${id}][valor_diario]" placeholder="%VD" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <button type="button" class="text-red-500 ml-4 remove-nutrient">Excluir</button>
                </div>`;
            container.insertAdjacentHTML('beforeend', nutrientRow);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-nutrient')) {
            e.target.closest('.nutrient-row').remove();
        }
    });

    // Função para substituir vírgulas por pontos nos campos numéricos antes do envio do formulário
    document.getElementById('nutrition-form').addEventListener('submit', function() {
        const numericFields = document.querySelectorAll('input[type="text"]');
        numericFields.forEach(function(input) {
            input.value = input.value.replace(',', '.');
        });
    });
</script>
@endsection