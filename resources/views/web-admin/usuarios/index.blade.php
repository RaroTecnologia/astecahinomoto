@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-start space-x-8">
        <!-- Formulário de criação de usuário (lado esquerdo) -->
        <div class="w-1/3 bg-white p-4 rounded-lg shadow-md sticky top-4">
            <h2 class="text-xl font-bold mb-6">Adicionar Novo Usuário</h2>
            <form action="{{ route('web-admin.usuarios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nome do Usuário -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Nome:</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Senha -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Senha:</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Confirmação da Senha -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirmar Senha:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Avatar do Usuário -->
                <div class="mb-4">
                    <label for="avatar" class="block text-gray-700 font-bold mb-2">Avatar (opcional):</label>
                    <input type="file" name="avatar" id="avatar" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Papéis -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Papéis:</label>
                    @foreach($roles as $role)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="mr-2">
                        <label>{{ ucfirst($role->name) }}</label>
                    </div>
                    @endforeach
                </div>

                <!-- Botão de Submissão -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Adicionar Usuário
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Usuários (lado direito) -->
        <div class="w-2/3 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-6">Lista de Usuários</h2>

            @if($usuarios->isEmpty())
            <p class="text-gray-600">Nenhum usuário encontrado.</p>
            @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 text-left">Nome</th>
                        <th class="py-2 text-left">Email</th>
                        <th class="py-2 text-left">Papéis</th>
                        <th class="py-2 text-left">Avatar</th>
                        <th class="py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td class="border px-4 py-2">{{ $usuario->name }}</td>
                        <td class="border px-4 py-2">{{ $usuario->email }}</td>
                        <td class="border px-4 py-2">
                            {{ $usuario->roles->pluck('name')->implode(', ') }}
                        </td>
                        <td class="border px-4 py-2">
                            @if($usuario->avatar)
                            <img src="{{ asset('storage/' . $usuario->avatar) }}" alt="{{ $usuario->name }}" class="h-10 w-10 rounded-full">
                            @else
                            <span class="text-gray-600">Sem avatar</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('web-admin.usuarios.edit', $usuario->id) }}" class="text-blue-500 hover:underline">Editar</a>
                            <form action="{{ route('web-admin.usuarios.destroy', $usuario->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
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
    </div>
</div>
@endsection