@extends('layouts.admin')

@section('title', 'Gerenciar Nutrientes')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Nutrientes</h1>
        <a href="{{ route('web-admin.nutrientes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Adicionar Novo Nutriente
        </a>
    </div>

    <!-- Lista de Nutrientes -->
    @if($nutrientes->isEmpty())
    <p class="text-gray-600">Nenhum nutriente encontrado.</p>
    @else
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-3 px-6 text-left">Nome</th>
                <th class="py-3 px-6 text-left">Unidade de Medida</th>
                <th class="py-3 px-6 text-left">Tipo de Nutriente</th>
                <th class="py-3 px-6 text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nutrientes as $nutriente)
            <tr class="border-b">
                <td class="py-3 px-6">{{ $nutriente->nome }}</td>
                <td class="py-3 px-6">{{ $nutriente->unidade_medida }}</td>
                <td class="py-3 px-6">{{ $nutriente->tipo_nutriente }}</td>
                <td class="py-3 px-6 text-center">
                    <a href="{{ route('web-admin.nutrientes.edit', $nutriente->id) }}" class="text-blue-500 hover:underline">Editar</a>
                    <form action="{{ route('web-admin.nutrientes.destroy', $nutriente->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este nutriente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline ml-2">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection