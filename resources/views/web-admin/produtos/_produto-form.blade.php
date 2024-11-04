<form action="{{ route('web-admin.produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    @method('PUT')

    <!-- Nome do Produto -->
    <div class="mb-4">
        <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $produto->nome) }}" class="w-full px-4 py-2 border rounded-lg" required>
    </div>

    <!-- Switch de Status do Produto -->
    <div class="mb-4">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox"
                name="is_active"
                class="sr-only peer status-switch"
                {{ old('is_active', $produto->is_active) ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
            <span class="ml-3 text-sm font-medium text-gray-700 status-label">
                {{ old('is_active', $produto->is_active) ? 'Produto Ativo' : 'Produto Inativo' }}
            </span>
        </label>
    </div>

    <!-- Descrição do Produto -->
    <div class="mb-4">
        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição do Produto</label>
        <textarea name="descricao" id="descricao" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('descricao', $produto->descricao) }}</textarea>
    </div>

    <!-- Ingredientes do Produto -->
    <div class="mb-4">
        <label for="ingredientes" class="block text-sm font-medium text-gray-700">Ingredientes</label>
        <textarea name="ingredientes" id="ingredientes" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('ingredientes', $produto->ingredientes) }}</textarea>
    </div>

    <!-- Categoria -->
    <div class="mb-4">
        <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 border rounded-lg">
            <option value="">Selecione uma categoria</option>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nome }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Tabela Nutricional -->
    <div class="mb-4">
        <label for="tabela_nutricional_id" class="block text-sm font-medium text-gray-700">Tabela Nutricional</label>
        <select name="tabela_nutricional_id" id="tabela_nutricional_id" class="w-full px-4 py-2 border rounded-lg">
            <option value="">Selecione uma tabela nutricional</option>
            @foreach($tabelasNutricionais as $tabela)
            <option value="{{ $tabela->id }}" {{ $produto->tabela_nutricional_id == $tabela->id ? 'selected' : '' }}>
                {{ $tabela->nome }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Imagem do Produto -->
    <div class="mb-4">
        <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem do Produto</label>
        <input type="file" name="imagem" id="imagem" class="w-full px-4 py-2 border rounded-lg">
        @if ($produto->imagem)
        <div class="mt-2">
            <img src="{{ asset('storage/produtos/' . $produto->imagem) }}" alt="{{ $produto->nome }}" class="w-32 h-32 object-cover rounded">
        </div>
        @endif
    </div>

    <!-- Botões de Salvar e Cancelar -->
    <div class="flex justify-end mt-6">
        <a href="{{ route('web-admin.produtos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
        <button type="submit" id="submitBtn" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
            Salvar Produto
        </button>
    </div>
</form>

<script>
    document.getElementById('submitBtn').addEventListener('click', function() {
        const form = document.getElementById('productForm');
        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST', // Mesmo sendo PUT, Laravel usa POST com _method PUT
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                }
            })
            .then(response => {
                console.log('Status de resposta:', response.status);

                // Se a resposta for 204 (No Content), consideramos sucesso sem conteúdo
                if (response.status === 204) {
                    return null; // Nada a processar
                }

                // Se a resposta for 200 (OK), verificamos se há conteúdo
                if (response.status === 200) {
                    return response.text().then(text => {
                        // Se houver texto, tentamos processar como JSON
                        return text ? JSON.parse(text) : {}; // Verifica se o texto é vazio
                    });
                }

                // Se houver erro de validação (422), processamos os erros
                if (response.status === 422) {
                    return response.json().then(errors => {
                        notyf.error('Erro de validação');
                        console.log(errors);
                    });
                }

                // Se não for nenhuma das condições acima, lançamos um erro
                throw new Error('Erro inesperado');
            })
            .then(data => {
                // Tratamos o caso onde recebemos JSON
                if (data && data.success) {
                    notyf.success('Produto atualizado com sucesso!');
                }
            })
            .catch(error => {
                // Tratamos todos os erros de comunicação
                notyf.error('Erro de comunicação com o servidor.');
                console.error(error);
            });
    });
</script>