@extends('layouts.admin')

@section('content')
<!-- Mensagens de sessão -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        notyf.success("{{ session('success') }}");
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        notyf.error("{{ session('error') }}");
    });
</script>
@endif

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Gerenciar Home</h1>

    <!-- Banners -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Banners</h2>

        <!-- Formulário para adicionar banner -->
        <form action="{{ route('web-admin.home.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 bg-white p-6 rounded-lg shadow">
            @csrf
            <input type="hidden" name="type" value="banner">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Link</label>
                    <input type="text" name="link" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Ordem</label>
                    <input type="number" name="ordem" class="w-full px-3 py-2 border rounded" value="0">
                </div>
                <div>
                    <label class="block mb-2">Imagem Desktop (1920x600)</label>
                    <input type="file" name="imagem_desktop" class="w-full" accept="image/*">
                </div>
                <div>
                    <label class="block mb-2">Imagem Mobile (768x800)</label>
                    <input type="file" name="imagem_mobile" class="w-full" accept="image/*">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Adicionar Banner</button>
        </form>

        <!-- Lista de banners -->
        <div id="banners-list" class="space-y-4">
            @foreach($banners as $banner)
            <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between" data-id="{{ $banner->id }}">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/thumbnails/' . $banner->imagem) }}" class="w-24 h-16 object-cover rounded">
                    <div>
                        <h3 class="font-semibold">{{ $banner->titulo }}</h3>
                        <p class="text-sm text-gray-600">Ordem: {{ $banner->ordem }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="edit-banner text-blue-500" data-id="{{ $banner->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete-banner text-red-500" data-id="{{ $banner->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Produtos em Destaque -->
    <div>
        <h2 class="text-xl font-semibold mb-4">Produtos em Destaque</h2>

        <!-- Formulário para adicionar destaque -->
        <form action="{{ route('web-admin.home.store') }}" method="POST" class="mb-6 bg-white p-6 rounded-lg shadow">
            @csrf
            <input type="hidden" name="type" value="destaque">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Produto</label>
                    <select name="produto_id" class="w-full px-3 py-2 border rounded">
                        @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2">Ordem</label>
                    <input type="number" name="ordem" class="w-full px-3 py-2 border rounded" value="0">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Adicionar Destaque</button>
        </form>

        <!-- Lista de destaques -->
        <div id="destaques-list" class="space-y-4">
            @foreach($destaques as $destaque)
            <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between" data-id="{{ $destaque->id }}">
                <div class="flex items-center space-x-4">
                    @if($destaque->produto->imagem)
                    <img src="{{ asset('storage/produtos/thumb_' . $destaque->produto->imagem) }}" class="w-24 h-16 object-cover rounded">
                    @endif
                    <div>
                        <h3 class="font-semibold">{{ $destaque->produto->nome }}</h3>
                        <p class="text-sm text-gray-600">Ordem: {{ $destaque->ordem }}</p>
                    </div>
                </div>
                <button class="delete-destaque text-red-500" data-id="{{ $destaque->id }}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div id="editBannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-8 rounded-lg w-full max-w-2xl">
        <h2 class="text-xl font-bold mb-4">Editar Banner</h2>

        <form id="editBannerForm" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="banner_id" id="edit_banner_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Título</label>
                    <input type="text" name="titulo" id="edit_titulo" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Subtítulo</label>
                    <input type="text" name="subtitulo" id="edit_subtitulo" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Link</label>
                    <input type="text" name="link" id="edit_link" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Ordem</label>
                    <input type="number" name="ordem" id="edit_ordem" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="col-span-2">
                    <label class="block mb-2">Nova Imagem</label>
                    <input type="file" name="imagem" class="w-full">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Sortable para banners
    new Sortable(document.getElementById('banners-list'), {
        animation: 150,
        onEnd: function() {
            const items = Array.from(this.el.children).map(el => el.dataset.id);
            updateOrdem('banner', items);
        }
    });

    // Sortable para destaques
    new Sortable(document.getElementById('destaques-list'), {
        animation: 150,
        onEnd: function() {
            const items = Array.from(this.el.children).map(el => el.dataset.id);
            updateOrdem('destaque', items);
        }
    });

    // Função para atualizar ordem
    function updateOrdem(type, items) {
        fetch('/web-admin/home/1', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    type,
                    items
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.success('Ordem atualizada com sucesso');
                } else {
                    notyf.error('Erro ao atualizar ordem');
                }
            })
            .catch(error => {
                notyf.error('Erro ao atualizar ordem');
            });
    }

    // Delete banner
    document.querySelectorAll('.delete-banner').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja excluir este banner?')) {
                const id = this.dataset.id;
                fetch(`/web-admin/home/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            type: 'banner'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('[data-id]').remove();
                            notyf.success(data.message);
                        } else {
                            notyf.error(data.message);
                        }
                    })
                    .catch(error => {
                        notyf.error('Erro ao excluir banner');
                    });
            }
        });
    });

    // Delete destaque
    document.querySelectorAll('.delete-destaque').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja excluir este destaque?')) {
                const id = this.dataset.id;
                fetch(`/web-admin/home/${id}?type=destaque`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('[data-id]').remove();
                            notyf.success(data.message || 'Destaque excluído com sucesso');
                        } else {
                            notyf.error(data.message || 'Erro ao excluir destaque');
                        }
                    })
                    .catch(error => {
                        notyf.error('Erro ao excluir destaque');
                    });
            }
        });
    });

    // Função para abrir modal de edição
    function openEditModal(id) {
        fetch(`/web-admin/home/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const banner = data.banner;
                    document.getElementById('edit_banner_id').value = banner.id;
                    document.getElementById('edit_titulo').value = banner.titulo || '';
                    document.getElementById('edit_subtitulo').value = banner.subtitulo || '';
                    document.getElementById('edit_link').value = banner.link || '';
                    document.getElementById('edit_ordem').value = banner.ordem || 0;

                    document.getElementById('editBannerModal').classList.remove('hidden');
                    document.getElementById('editBannerModal').classList.add('flex');
                }
            });
    }

    // Função para fechar modal
    function closeEditModal() {
        document.getElementById('editBannerModal').classList.add('hidden');
        document.getElementById('editBannerModal').classList.remove('flex');
    }

    // Submit do formulário de edição
    document.getElementById('editBannerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const bannerId = document.getElementById('edit_banner_id').value;

        fetch(`/web-admin/home/${bannerId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.success(data.message);
                    closeEditModal();
                    window.location.reload();
                } else {
                    notyf.error(data.message);
                }
            })
            .catch(error => {
                notyf.error('Erro ao atualizar banner');
            });
    });

    // Atualizar botão de editar na lista de banners
    document.querySelectorAll('.edit-banner').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            openEditModal(id);
        });
    });
</script>
@endpush
@endsection