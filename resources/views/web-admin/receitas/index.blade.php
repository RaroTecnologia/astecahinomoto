@extends('layouts.admin')

@section('title', 'Gerenciamento de Receitas')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Gerenciamento de Receitas</h1>
        <a href="{{ route('web-admin.receitas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Nova Receita
        </a>
    </div>

    <!-- Tabela de Receitas -->
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-max w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Título</th>
                    <th class="py-3 px-6 text-left">Categoria</th>
                    <th class="py-3 px-6 text-left">Criado em</th>
                    <th class="py-3 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($receitas as $receita)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        {{ $receita->nome }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $receita->categoria->nome }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $receita->created_at->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('web-admin.receitas.edit', $receita->id) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L5.414 11.758a1 1 0 00-.293.707v2.121a1 1 0 001 1h2.121a1 1 0 00.707-.293l9.172-9.172a2 2 0 000-2.828zM16.414 5l-9 9H7v-.414l9-9L16.414 5zM3 17h12v1H3a1 1 0 01-1-1v-3h1v3z" />
                                </svg>
                            </a>
                            <form action="{{ route('web-admin.receitas.destroy', $receita->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" onclick="return confirm('Tem certeza que deseja excluir esta receita?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414L11.414 13l2.293 2.293a1 1 0 01-1.414 1.414L10 14.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 13 6.293 10.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $receitas->links() }}
    </div>
</div>
@endsection