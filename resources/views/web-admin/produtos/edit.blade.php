@extends('layouts.admin')

@section('title', 'Editar Produto')

@section('content')
<div class="container mx-auto py-12 sticky">
    <div class="flex">
        <!-- Coluna da Edição do Produto (Esquerda) -->
        <div class="w-1/2 bg-white shadow-lg rounded-lg p-8 mr-4">
            @component('web-admin.produtos._produto-form', ['produto' => $produto, 'categorias' => $categorias, 'tabelasNutricionais' => $tabelasNutricionais])
            @endcomponent
        </div>

        <!-- Coluna de Gestão de SKUs (Direita) -->
        <div class="w-1/2 bg-white shadow-lg rounded-lg p-8">
            <h3 class="text-lg font-bold mb-6">Variações de SKU</h3>

            <div id="sku-list">
                @foreach($produto->skus as $sku)
                <div class="mb-4 border p-4 rounded-md sku-item" data-id="{{ $sku->id }}">
                    @component('web-admin.produtos._sku-form', ['sku' => $sku, 'produtoId' => $produto->id, 'method' => 'PUT'])
                    @endcomponent
                </div>
                @endforeach
            </div>

            <!-- Botão para adicionar novo SKU -->
            <button type="button" id="add-sku" class="bg-green-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus"></i> Adicionar Novo SKU
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Função para Expandir/Recolher SKU
        function toggleSkuExpansion() {
            document.querySelectorAll('.sku-header').forEach(header => {
                header.removeEventListener('click', toggleSku);
                header.addEventListener('click', toggleSku);
            });
        }

        function toggleSku() {
            const target = this.getAttribute('data-target');
            const skuForm = document.querySelector(target);
            if (skuForm) {
                skuForm.classList.toggle('hidden');
            }
        }

        toggleSkuExpansion(); // Vincula eventos de expansão/recolhimento

        // Evento de exclusão de SKU
        document.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.delete-sku');
            if (deleteButton) {
                const skuId = deleteButton.getAttribute('data-id');
                const token = document.querySelector('input[name="_token"]').value;

                if (confirm('Tem certeza que deseja excluir este SKU?')) {
                    const formData = new URLSearchParams();
                    formData.append('_token', token);
                    formData.append('_method', 'DELETE');

                    fetch(`/web-admin/skus/${skuId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const skuElement = document.querySelector(`.sku-item[data-id="${skuId}"]`);
                                if (skuElement) {
                                    skuElement.remove();
                                }
                                notyf.success(data.message);
                            } else {
                                notyf.error('Erro ao excluir SKU');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            notyf.error('Erro ao excluir SKU');
                        });
                }
            }
        });

        // Evento de duplicar SKU
        document.addEventListener('click', function(e) {
            const duplicateButton = e.target.closest('.duplicate-sku');
            if (duplicateButton) {
                const skuId = duplicateButton.getAttribute('data-id');
                const token = document.querySelector('input[name="_token"]').value;
                const form = duplicateButton.closest('form');

                const formData = new FormData(form);
                formData.delete('_method');
                formData.set('nome', formData.get('nome') + ' (Cópia)');
                formData.delete('codigo_sku');
                formData.delete('dun');
                formData.delete('ean');

                fetch('/web-admin/skus', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recarrega a página para mostrar o novo SKU
                            window.location.reload();
                        } else {
                            notyf.error('Erro ao duplicar SKU');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        notyf.error('Erro ao duplicar SKU');
                    });
            }
        });

        // Evento de adicionar SKU
        document.getElementById('add-sku').addEventListener('click', function() {
            const produtoId = "{{ $produto->id }}";
            const token = document.querySelector('input[name="_token"]').value;

            const formData = new FormData();
            formData.append('produto_id', produtoId);
            formData.append('nome', 'Novo SKU');
            formData.append('_token', token);

            fetch('/web-admin/skus', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Template do novo SKU com os dados retornados
                        const skuTemplate = `
                <div class="sku-item mb-4 border p-4 rounded-md" data-id="${data.sku.id}">
                    <div class="sku-header cursor-pointer bg-gray-200 p-4 rounded-md mb-2" data-target="#sku-form-${data.sku.id}">
                        <span>SKU: ${data.sku.nome}</span>
                    </div>

                    <div id="sku-form-${data.sku.id}" class="sku-body">
                        <form class="sku-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="produto_id" value="${produtoId}">

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <div class="mb-4">
                                        <label for="nome_${data.sku.id}" class="block text-sm font-medium text-gray-700">Nome</label>
                                        <input type="text" name="nome" id="nome_${data.sku.id}" value="${data.sku.nome}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>
                                </div>

                                <!-- Coluna Esquerda -->
                                <div>
                                    <div class="mb-4">
                                        <label for="quantidade_${data.sku.id}" class="block text-sm font-medium text-gray-700">Quantidade</label>
                                        <input type="text" name="quantidade" id="quantidade_${data.sku.id}" value="${data.sku.quantidade || ''}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>

                                    <div class="mb-4">
                                        <label for="porcao_tabela_${data.sku.id}" class="block text-sm font-medium text-gray-700">Porção por embalagem</label>
                                        <input type="text" name="porcao_tabela" id="porcao_tabela_${data.sku.id}" value="${data.sku.porcao_tabela || ''}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>

                                    <div class="mb-4">
                                        <label for="codigo_sku_${data.sku.id}" class="block text-sm font-medium text-gray-700">Código SKU</label>
                                        <input type="text" name="codigo_sku" id="codigo_sku_${data.sku.id}" value="${data.sku.codigo_sku || ''}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>
                                </div>

                                <!-- Coluna Direita -->
                                <div>
                                    <div class="mb-4">
                                        <label for="quantidade_inner_${data.sku.id}" class="block text-sm font-medium text-gray-700">Quantidade inner</label>
                                        <input type="text" name="quantidade_inner" id="quantidade_inner_${data.sku.id}" value="${data.sku.quantidade_inner || ''}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>

                                    <div class="mb-4">
                                        <label for="dun_${data.sku.id}" class="block text-sm font-medium text-gray-700">Código DUN</label>
                                        <input type="text" name="dun" id="dun_${data.sku.id}" value="${data.sku.dun || ''}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>

                                    <div class="mb-4">
                                        <label for="ean_${data.sku.id}" class="block text-sm font-medium text-gray-700">Código EAN</label>
                                        <input type="text" name="ean" id="ean_${data.sku.id}" value="${data.sku.ean || ''}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>
                                </div>

                                <!-- Imagem do SKU -->
                                <div class="col-span-2">
                                    <div class="mb-4">
                                        <label for="imagem_${data.sku.id}" class="block text-sm font-medium text-gray-700">Imagem do SKU</label>
                                        <input type="file" name="imagem" id="imagem_${data.sku.id}" class="w-full px-4 py-2 border rounded-lg">
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de ação -->
                            <div class="flex space-x-4 mt-4">
                                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg save-sku" data-id="${data.sku.id}">
                                    <i class="fas fa-save"></i> Salvar
                                </button>

                                <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded-lg duplicate-sku" data-id="${data.sku.id}">
                                    <i class="fas fa-copy"></i> Duplicar
                                </button>

                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg delete-sku" data-id="${data.sku.id}">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                `;

                        document.getElementById('sku-list').insertAdjacentHTML('beforeend', skuTemplate);
                        toggleSkuExpansion(); // Atualiza a expansão para o novo SKU
                        notyf.success('SKU criado com sucesso');
                    } else {
                        notyf.error('Erro ao criar SKU');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    notyf.error('Erro ao criar SKU');
                });
        });

        // Evento de salvar SKU (se existir)
        document.addEventListener('click', function(e) {
            const saveButton = e.target.closest('.save-sku');
            if (saveButton) {
                const skuId = saveButton.getAttribute('data-id');
                const form = document.querySelector(`.sku-item[data-id="${skuId}"] form.sku-form`);

                if (!form) return;

                const formData = new FormData(form);
                let url = skuId.includes('new') ? '/web-admin/skus' : `/web-admin/skus/${skuId}`;
                const httpMethod = skuId.includes('new') ? 'POST' : 'POST';

                // Método PUT simulado
                if (!skuId.includes('new')) {
                    formData.append('_method', 'PUT');
                }

                fetch(url, {
                        method: httpMethod,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notyf.success('SKU salvo com sucesso');
                        } else {
                            notyf.error('Erro ao salvar SKU');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        notyf.error('Erro ao salvar SKU');
                    });
            }
        });
    });
</script>
@endsection