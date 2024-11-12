@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-4 gap-4" id="kanban-board">
        <!-- Coluna: Novo -->
        <div class="bg-gray-100 rounded-lg p-4">
            <h2 class="font-bold text-lg mb-4 text-gray-700 flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                Novo
            </h2>
            <div class="space-y-3 kanban-column" id="novo" data-status="novo">
                @foreach($contatos->where('status', 'novo') as $contato)
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow" data-id="{{ $contato->id }}">
                    <!-- Área arrastável -->
                    <div class="cursor-move drag-handle mb-2">
                        <i class="fas fa-grip-lines text-gray-400"></i>
                    </div>

                    <!-- Área clicável -->
                    <div class="cursor-pointer" onclick="showContactDetails({{ $contato->id }})">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs rounded-full {{ 
                                match($contato->departamento) {
                                    'Atendimento ao Cliente' => 'bg-blue-100 text-blue-800',
                                    'Representantes Comerciais' => 'bg-green-100 text-green-800',
                                    'Imprensa' => 'bg-purple-100 text-purple-800',
                                    'Recursos Humanos' => 'bg-yellow-100 text-yellow-800',
                                    'Financeiro' => 'bg-indigo-100 text-indigo-800',
                                    default => 'bg-gray-100 text-gray-800'
                                }
                            }}">
                                {{ $contato->departamento }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $contato->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <h3 class="font-semibold">{{ $contato->nome }}</h3>
                        <p class="text-sm text-gray-600">{{ $contato->email }}</p>
                        <p class="text-sm text-gray-500">{{ $contato->telefone }}</p>

                        <!-- Prévia da última mensagem -->
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2">
                            {{ Str::limit($contato->mensagem, 100) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Coluna: Em Andamento -->
        <div class="bg-gray-100 rounded-lg p-4">
            <h2 class="font-bold text-lg mb-4 text-gray-700 flex items-center">
                <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                Em Andamento
            </h2>
            <div class="space-y-3 kanban-column" id="em_andamento" data-status="em_andamento">
                @foreach($contatos->where('status', 'em_andamento') as $contato)
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow cursor-move" data-id="{{ $contato->id }}">
                    <h3 class="font-semibold">{{ $contato->nome }}</h3>
                    <p class="text-sm text-gray-600">{{ $contato->email }}</p>
                    <p class="text-sm text-gray-500">{{ $contato->telefone }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Coluna: Respondido -->
        <div class="bg-gray-100 rounded-lg p-4">
            <h2 class="font-bold text-lg mb-4 text-gray-700 flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                Respondido
            </h2>
            <div class="space-y-3 kanban-column" id="respondido" data-status="respondido">
                @foreach($contatos->where('status', 'respondido') as $contato)
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow cursor-move" data-id="{{ $contato->id }}">
                    <h3 class="font-semibold">{{ $contato->nome }}</h3>
                    <p class="text-sm text-gray-600">{{ $contato->email }}</p>
                    <p class="text-sm text-gray-500">{{ $contato->telefone }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Coluna: Finalizado -->
        <div class="bg-gray-100 rounded-lg p-4">
            <h2 class="font-bold text-lg mb-4 text-gray-700 flex items-center">
                <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                Finalizado
            </h2>
            <div class="space-y-3 kanban-column" id="finalizado" data-status="finalizado">
                @foreach($contatos->where('status', 'finalizado') as $contato)
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow cursor-move" data-id="{{ $contato->id }}">
                    <h3 class="font-semibold">{{ $contato->nome }}</h3>
                    <p class="text-sm text-gray-600">{{ $contato->email }}</p>
                    <p class="text-sm text-gray-500">{{ $contato->telefone }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = document.querySelectorAll('.kanban-column');

        columns.forEach(column => {
            new Sortable(column, {
                group: 'shared',
                animation: 150,
                ghostClass: 'bg-gray-300',
                handle: '.drag-handle',
                onStart: function(evt) {
                    evt.item.classList.add('dragging');
                },
                onEnd: function(evt) {
                    const contactId = evt.item.dataset.id;
                    const newStatus = evt.to.dataset.status;

                    // Adiciona classe de loading
                    evt.item.classList.add('opacity-50');
                    evt.item.classList.remove('dragging');
                    evt.item.style.pointerEvents = 'none';

                    // Pega o token CSRF
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    // URL correta com prefixo web-admin
                    fetch(`/web-admin/crm/${contactId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(async response => {
                            const data = await response.json();

                            if (!response.ok) {
                                throw new Error(data.message || 'Erro ao atualizar status');
                            }

                            // Remove classes de loading
                            evt.item.classList.remove('opacity-50');
                            evt.item.style.pointerEvents = 'auto';

                            // Feedback visual de sucesso
                            evt.item.classList.add('update-success');
                            setTimeout(() => {
                                evt.item.classList.remove('update-success');
                            }, 1000);
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            // Reverte o card para a posição original
                            evt.from.appendChild(evt.item);

                            // Remove classes de loading
                            evt.item.classList.remove('opacity-50');
                            evt.item.style.pointerEvents = 'auto';

                            // Feedback visual de erro
                            evt.item.classList.add('update-error');
                            setTimeout(() => {
                                evt.item.classList.remove('update-error');
                            }, 1000);

                            alert('Erro ao atualizar status: ' + error.message);
                        });
                }
            });
        });
    });
</script>

<style>
    .dragging {
        cursor: grabbing !important;
    }

    .kanban-column {
        min-height: 50px;
    }

    .update-success {
        animation: successPulse 1s ease;
    }

    .update-error {
        animation: errorPulse 1s ease;
    }

    @keyframes successPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
    }

    @keyframes errorPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    .drag-handle {
        cursor: move;
        cursor: -webkit-grabbing;
    }

    .drag-handle:hover {
        opacity: 0.7;
    }
</style>

<!-- Modal de Detalhes do Contato -->
<div id="contactDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <button onclick="closeContactDetails()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div id="contactDetailsContent">
            <!-- Conteúdo será carregado via AJAX -->
        </div>
    </div>
</div>

<!-- Template do Modal de Detalhes -->
<template id="contactDetailsTemplate">
    <div class="space-y-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-start border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold">%nome%</h2>
                <span class="px-2 py-1 text-xs rounded-full %departamentoCor%">
                    %departamento%
                </span>
            </div>
            <div class="text-right text-sm text-gray-500">
                <p>Criado em: %dataCriacao%</p>
                <p>Última interação: %ultimaInteracao%</p>
            </div>
        </div>

        <!-- Informações do Contato -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Email:</p>
                <p class="font-medium">%email%</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Telefone:</p>
                <p class="font-medium">%telefone%</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Cidade:</p>
                <p class="font-medium">%cidade%</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status:</p>
                <p class="font-medium">%status%</p>
            </div>
        </div>

        <!-- Mensagem Original -->
        <div>
            <h3 class="font-semibold mb-2">Mensagem Original:</h3>
            <p class="text-gray-700 bg-gray-50 p-4 rounded">%mensagem%</p>
        </div>

        <!-- Anotações -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold">Anotações Internas:</h3>
                <button onclick="showAddNoteForm(%id%)"
                    class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                    Nova Anotação
                </button>
            </div>

            <div class="space-y-4" id="anotacoesList">
                %anotacoes%
            </div>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
    function showContactDetails(contactId) {
        const modal = document.getElementById('contactDetailsModal');
        const content = document.getElementById('contactDetailsContent');

        content.innerHTML = '<div class="flex justify-center items-center h-40"><i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i></div>';
        modal.classList.remove('hidden');

        // Adiciona o token CSRF no header
        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/web-admin/crm/${contactId}/details`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(async response => {
                const text = await response.text(); // Pega o texto bruto primeiro
                console.log('Response raw:', text); // Debug

                try {
                    const data = JSON.parse(text); // Tenta converter para JSON
                    if (!response.ok) {
                        throw new Error(data.message || 'Erro ao carregar detalhes do contato');
                    }
                    return data;
                } catch (e) {
                    console.error('Erro ao parsear JSON:', e);
                    throw new Error('Resposta inválida do servidor');
                }
            })
            .then(data => {
                const template = document.getElementById('contactDetailsTemplate').innerHTML;
                let html = template;

                // Substituir placeholders
                const replacements = {
                    '%nome%': data.nome,
                    '%email%': data.email,
                    '%telefone%': data.telefone,
                    '%cidade%': data.cidade,
                    '%departamento%': data.departamento,
                    '%status%': data.status,
                    '%mensagem%': data.mensagem,
                    '%dataCriacao%': data.created_at,
                    '%ultimaInteracao%': data.ultima_interacao,
                    '%id%': data.id
                };

                Object.keys(replacements).forEach(key => {
                    html = html.replace(new RegExp(key, 'g'), replacements[key] || '');
                });

                // Gerar HTML das anotações
                const anotacoesHtml = data.anotacoes.length > 0 ?
                    data.anotacoes.map(note => `
                    <div class="bg-gray-50 p-4 rounded">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-medium">${note.user.name}</span>
                            <span class="text-sm text-gray-500">${note.created_at}</span>
                        </div>
                        <p class="text-gray-700">${note.conteudo}</p>
                    </div>
                `).join('') :
                    '<p class="text-center text-gray-500 py-4">Nenhuma anotação registrada</p>';

                html = html.replace('%anotacoes%', anotacoesHtml);

                // Definir a cor do departamento
                const deptColors = {
                    'Atendimento ao Cliente': 'bg-blue-100 text-blue-800',
                    'Representantes Comerciais': 'bg-green-100 text-green-800',
                    'Imprensa': 'bg-purple-100 text-purple-800',
                    'Recursos Humanos': 'bg-yellow-100 text-yellow-800',
                    'Financeiro': 'bg-indigo-100 text-indigo-800'
                };

                html = html.replace('%departamentoCor%',
                    deptColors[data.departamento] || 'bg-gray-100 text-gray-800');

                content.innerHTML = html;
            })
            .catch(error => {
                console.error('Erro completo:', error);
                content.innerHTML = `
                <div class="text-red-500 text-center py-4">
                    <p class="font-bold mb-2">Erro ao carregar detalhes do contato</p>
                    <p class="text-sm">${error.message}</p>
                    <button onclick="closeContactDetails()" 
                            class="mt-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Fechar
                    </button>
                </div>`;
            });
    }

    function closeContactDetails() {
        document.getElementById('contactDetailsModal').classList.add('hidden');
    }

    function showAddNoteForm(contactId) {
        const noteForm = `
        <div id="noteForm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
            <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-lg rounded-md bg-white">
                <button onclick="closeNoteForm()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-lg font-semibold mb-4">Nova Anotação Interna</h3>
                
                <textarea id="noteContent" 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4"
                    placeholder="Digite sua anotação..."></textarea>
                
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="closeNoteForm()" 
                        class="px-4 py-2 rounded text-gray-600 hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button onclick="submitNote(${contactId})" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    `;

        document.body.insertAdjacentHTML('beforeend', noteForm);
    }

    function closeNoteForm() {
        document.getElementById('noteForm').remove();
    }

    function submitNote(contactId) {
        const content = document.getElementById('noteContent').value;
        if (!content.trim()) {
            alert('Por favor, digite uma anotação');
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/web-admin/crm/${contactId}/anotacoes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    conteudo: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const noteHtml = `
                <div class="bg-gray-50 p-4 rounded">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-medium">${data.anotacao.user.name}</span>
                        <span class="text-sm text-gray-500">${data.anotacao.created_at}</span>
                    </div>
                    <p class="text-gray-700">${data.anotacao.conteudo}</p>
                </div>
            `;

                    const anotacoesList = document.getElementById('anotacoesList');
                    const emptyMessage = anotacoesList.querySelector('p.text-center');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }

                    anotacoesList.insertAdjacentHTML('afterbegin', noteHtml);
                    closeNoteForm();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao salvar anotação');
            });
    }
</script>
@endsection