@extends('layouts.admin')

@section('title', 'Gerenciamento de Papéis')

@section('content')
<h2>Papéis</h2>

<a href="{{ route('roles.create') }}" class="btn btn-primary">Adicionar Novo Papel</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->name }}</td>
            <td>
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection