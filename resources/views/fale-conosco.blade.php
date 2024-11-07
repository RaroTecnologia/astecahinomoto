@extends('layouts.app')

@section('title', 'Fale Conosco')

@section('content')
<div class="bg-white">
    <div class="container mx-auto py-16 px-4">
        <!-- Breadcrumb e Opção de Compartilhar -->
        <x-breadcrumb-share currentPage="Fale Conosco" />

        <!-- Título e Subtítulo -->
        <div class="text-center mb-4">
            <h1 class="text-4xl font-bold text-gray-900">Fale Conosco</h1>
            <p class="text-lg text-gray-700 p-4 mt-4">Estamos sempre prontos para ouvir você, tirar suas dúvidas e fornecer as informações de que você precisa.</p>
        </div>

        <!-- Números de Telefone -->
        <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-8 mb-12">
            <div class="flex items-center space-x-3">
                <div class="bg-yellow-500 p-3 rounded-full w-10 h-10 flex items-center justify-center">
                    <i class="fas fa-phone text-white text-lg"></i>
                </div>
                <a href="tel:+551821014000" class="text-lg font-semibold text-gray-700 hover:text-yellow-500 transition duration-300">
                    +55 18 2101-4000
                </a>
            </div>

            <div class="hidden md:block h-8 w-px bg-gray-300"></div> <!-- Divisor vertical -->

            <div class="flex items-center space-x-3">
                <div class="bg-yellow-500 p-3 rounded-full w-10 h-10 flex items-center justify-center">
                    <i class="fas fa-phone text-white text-lg"></i>
                </div>
                <a href="tel:+551135779300" class="text-lg font-semibold text-gray-700 hover:text-yellow-500 transition duration-300">
                    +55 11 3577-9300
                </a>
            </div>
        </div>

        <p class="text-center text-gray-700">Escolha abaixo com qual setor você quer falar.</p>
    </div>
</div>

<!-- Blocos para Seleção de Setores -->
<div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Setor 1 -->
    <a href="javascript:void(0)" onclick="openModal('Atendimento ao Cliente')"
        class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
        <div class="text-center flex-grow">
            <i class="fas fa-headset text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Atendimento ao Cliente</h3>
            <p class="text-gray-600 mb-6">Entre em contato para dúvidas gerais e suporte técnico.</p>
        </div>
        <div class="text-center mt-auto">
            <span class="text-yellow-500 font-semibold inline-block border-t border-gray-200 pt-4 w-full">
                Entrar em contato
            </span>
        </div>
    </a>

    <!-- Setor 2 -->
    <a href="javascript:void(0)" onclick="openModal('Representantes Comerciais')"
        class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
        <div class="text-center flex-grow">
            <i class="fas fa-handshake text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Representantes Comerciais</h3>
            <p class="text-gray-600 mb-6">Quer vender nossos produtos? Fale com nosso time comercial.</p>
        </div>
        <div class="text-center mt-auto">
            <span class="text-yellow-500 font-semibold inline-block border-t border-gray-200 pt-4 w-full">
                Entrar em contato
            </span>
        </div>
    </a>

    <!-- Setor 3 -->
    <a href="javascript:void(0)" onclick="openModal('Imprensa')"
        class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
        <div class="text-center flex-grow">
            <i class="fas fa-newspaper text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Imprensa</h3>
            <p class="text-gray-600 mb-6">Para jornalistas e veículos de comunicação.</p>
        </div>
        <div class="text-center mt-auto">
            <span class="text-yellow-500 font-semibold inline-block border-t border-gray-200 pt-4 w-full">
                Entrar em contato
            </span>
        </div>
    </a>

    <!-- Setor 4 -->
    <a href="javascript:void(0)" onclick="openModal('Recursos Humanos')"
        class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
        <div class="text-center flex-grow">
            <i class="fas fa-users text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Recursos Humanos</h3>
            <p class="text-gray-600 mb-6">Gostaria de fazer parte do nosso time? Converse com o RH.</p>
        </div>
        <div class="text-center mt-auto">
            <span class="text-yellow-500 font-semibold inline-block border-t border-gray-200 pt-4 w-full">
                Entrar em contato
            </span>
        </div>
    </a>

    <!-- Setor 5 -->
    <a href="javascript:void(0)" onclick="openModal('Financeiro')"
        class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
        <div class="text-center flex-grow">
            <i class="fas fa-file-invoice-dollar text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Financeiro</h3>
            <p class="text-gray-600 mb-6">Para questões financeiras e faturamento.</p>
        </div>
        <div class="text-center mt-auto">
            <span class="text-yellow-500 font-semibold inline-block border-t border-gray-200 pt-4 w-full">
                Entrar em contato
            </span>
        </div>
    </a>

    <!-- Setor 6 -->
    <a href="javascript:void(0)" onclick="openModal('Outros Assuntos')"
        class="border border-gray-300 p-6 rounded-lg shadow hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
        <div class="text-center flex-grow">
            <i class="fas fa-question-circle text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Outros Assuntos</h3>
            <p class="text-gray-600 mb-6">Para qualquer outro tipo de solicitação.</p>
        </div>
        <div class="text-center mt-auto">
            <span class="text-yellow-500 font-semibold inline-block border-t border-gray-200 pt-4 w-full">
                Entrar em contato
            </span>
        </div>
    </a>
</div>

<!-- Modal do Formulário -->
<div id="contactModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-md bg-white my-8">
        <!-- Botão Fechar -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="mt-3">
            <h2 class="text-2xl font-bold text-center mb-6" id="modalTitle">Contato - <span id="departmentName"></span></h2>

            <form action="{{ route('fale-conosco.enviar') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="department" id="departmentInput">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-yellow-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">E-mail</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-yellow-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telefone</label>
                        <input type="tel" name="phone" id="phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-yellow-500"
                            placeholder="(00) 00000-0000">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cidade</label>
                        <input type="text" name="city"
                            class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-yellow-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Mensagem</label>
                    <textarea name="message" rows="8" required
                        class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-yellow-500 resize-y min-h-[200px]"></textarea>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-3 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-200">
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body.modal-open {
        overflow: hidden;
    }
</style>

<!-- Scripts do Modal -->
<script>
    function openModal(department) {
        document.getElementById('departmentName').textContent = department;
        document.getElementById('departmentInput').value = department;
        document.getElementById('contactModal').classList.remove('hidden');
        document.body.classList.add('modal-open');
    }

    function closeModal() {
        document.getElementById('contactModal').classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    // Fechar modal quando clicar fora dele
    window.onclick = function(event) {
        let modal = document.getElementById('contactModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<!-- jQuery e jQuery Mask -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- Script da máscara de telefone -->
<script>
    $(document).ready(function() {
        var behavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };

        var options = {
            onKeyPress: function(val, e, field, options) {
                field.mask(behavior.apply({}, arguments), options);
            }
        };

        $('#phone').mask(behavior, options);
    });
</script>

@endsection