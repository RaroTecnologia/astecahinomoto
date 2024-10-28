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

        // Salvamento AJAX
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
                            console.log('SKU salvo com sucesso!');
                            location.reload();
                        } else {
                            console.error('Erro ao salvar SKU: ', data.message);
                        }
                    })
                    .catch(error => console.error('Erro de comunicação ao salvar SKU:', error));
            }
        });

        // Duplicação de SKU
        document.addEventListener('click', function(e) {
            const duplicateButton = e.target.closest('.duplicate-sku');
            if (duplicateButton) {
                const skuId = duplicateButton.getAttribute('data-id');
                const skuItem = document.querySelector(`.sku-item[data-id="${skuId}"]`);
                const nome = skuItem.querySelector('input[name="nome"]').value;
                const quantidade = skuItem.querySelector('input[name="quantidade"]').value;
                const unidade = skuItem.querySelector('input[name="unidade"]').value;

                const skuIndex = Date.now();
                const skuTemplate = `
                <div class="sku-item mb-4 border p-4 rounded-md" data-id="new_${skuIndex}">
                    <div class="sku-header cursor-pointer bg-gray-200 p-4 rounded-md mb-2" data-target="#sku-form-new_${skuIndex}">
                        <span>SKU: Novo SKU (Duplicado)</span>
                    </div>
                    <div id="sku-form-new_${skuIndex}" class="sku-body hidden">
                        <form class="sku-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">

                            <div class="mb-4">
                                <label for="nome_new_${skuIndex}" class="block text-sm font-medium text-gray-700">Nome</label>
                                <input type="text" name="nome" id="nome_new_${skuIndex}" value="${nome}" class="w-full px-4 py-2 border rounded-lg">
                            </div>

                            <div class="mb-4">
                                <label for="quantidade_new_${skuIndex}" class="block text-sm font-medium text-gray-700">Quantidade</label>
                                <input type="text" name="quantidade" id="quantidade_new_${skuIndex}" value="${quantidade}" class="w-full px-4 py-2 border rounded-lg">
                            </div>

                            <div class="mb-4">
                                <label for="unidade_new_${skuIndex}" class="block text-sm font-medium text-gray-700">Unidade</label>
                                <input type="text" name="unidade" id="unidade_new_${skuIndex}" value="${unidade}" class="w-full px-4 py-2 border rounded-lg">
                            </div>

                            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg save-sku" data-id="new_${skuIndex}">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                        </form>
                    </div>
                </div>
                `;
                document.getElementById('sku-list').insertAdjacentHTML('beforeend', skuTemplate);
                toggleSkuExpansion(); // Atualiza a expansão para o novo SKU
            }
        });

        // Adicionar Novo SKU
        document.getElementById('add-sku').addEventListener('click', function() {
            const skuIndex = Date.now();
            const skuTemplate = `
            <div class="sku-item mb-4 border p-4 rounded-md" data-id="new_${skuIndex}">
                <div class="sku-header cursor-pointer bg-gray-200 p-4 rounded-md mb-2" data-target="#sku-form-new_${skuIndex}">
                    <span>SKU: Novo SKU</span>
                </div>
                <div id="sku-form-new_${skuIndex}" class="sku-body hidden">
                    <form class="sku-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">

                        <div class="mb-4">
                            <label for="nome_new_${skuIndex}" class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" name="nome" id="nome_new_${skuIndex}" class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div class="mb-4">
                            <label for="quantidade_new_${skuIndex}" class="block text-sm font-medium text-gray-700">Quantidade</label>
                            <input type="text" name="quantidade" id="quantidade_new_${skuIndex}" class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div class="mb-4">
                            <label for="unidade_new_${skuIndex}" class="block text-sm font-medium text-gray-700">Unidade</label>
                            <input type="text" name="unidade" id="unidade_new_${skuIndex}" class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg save-sku" data-id="new_${skuIndex}">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </form>
                </div>
            </div>
            `;
            document.getElementById('sku-list').insertAdjacentHTML('beforeend', skuTemplate);
            toggleSkuExpansion(); // Atualiza a expansão para o novo SKU
        });
    });
</script>
@endsection