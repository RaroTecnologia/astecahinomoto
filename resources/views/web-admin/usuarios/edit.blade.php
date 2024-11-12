@extends('layouts.admin')

@section('title', 'Editar Usuário')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6">Editar Usuário</h2>

        <!-- Formulário de edição de usuário -->
        <form action="{{ route('web-admin.usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nome do Usuário -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nome do Usuário:</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror" value="{{ old('name', $usuario->name) }}" required>
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Papéis do Usuário -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Papéis:</label>
                @can('users.manage_roles')
                @foreach($roles as $role)
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        {{ $usuario->hasRole($role->name) ? 'checked' : '' }} class="mr-2">
                    <label>{{ ucfirst($role->name) }}</label>
                </div>
                @endforeach
                @else
                <div class="text-gray-600">
                    Papéis atuais: {{ $usuario->roles->pluck('name')->implode(', ') }}
                </div>
                @endcan
            </div>

            <!-- Campos sensíveis apenas visíveis para quem tem permissão -->
            @can('users.edit')
            <!-- Email do Usuário -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror" value="{{ old('email', $usuario->email) }}" required>
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Senha e outros campos sensíveis -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Senha (opcional):</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg">
                <small class="text-gray-500">Deixe em branco para manter a senha atual</small>
            </div>
            @endcan

            <!-- Avatar do Usuário -->
            <div class="mb-4">
                <label for="avatar" class="block text-gray-700 font-bold mb-2">Avatar (opcional):</label>
                @if($usuario->avatar)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $usuario->avatar) }}" alt="{{ $usuario->name }}" class="h-20 w-20 rounded-full">
                </div>
                @endif
                <input type="file" name="avatar" id="avatar" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Confirmação da Senha -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirmar Senha:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <!-- Botões -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('web-admin.usuarios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">Cancelar</a>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection