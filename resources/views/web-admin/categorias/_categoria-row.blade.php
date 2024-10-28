<!-- _categoria-row.blade.php -->

@php
// Indentação visual com base no nível da subcategoria
$indent = str_repeat('&mdash; ', $level);
@endphp

<tr>
    <td class="border px-4 py-2">{!! $indent !!}{{ $categoria->nome }}</td>
    <td class="border px-4 py-2">{{ $categoria->slug }}</td>
    <td class="border px-4 py-2">{{ $categoria->tipo }}</td>
    <td class="border px-4 py-2">
        <a href="{{ route('web-admin.categorias.edit', $categoria->id) }}" class="text-blue-500 hover:underline">Editar</a>
        <form action="{{ route('web-admin.categorias.destroy', $categoria->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:underline ml-2">Excluir</button>
        </form>
    </td>
</tr>

<!-- Exibe as subcategorias recursivamente, caso existam -->
@foreach($categoria->subcategorias as $subcategoria)
@include('web-admin.categorias._categoria-row', ['categoria' => $subcategoria, 'level' => $level + 1])
@endforeach