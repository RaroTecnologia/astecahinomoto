<!-- _categoria-row.blade.php -->

@php
// Indentação visual com base no nível da subcategoria
$indent = str_repeat('&mdash; ', $level);
@endphp

<tr>
    <td class="py-2">
        {{ str_repeat('—', $level) }} {{ $categoria->nome }}
    </td>
    <td class="py-2">{{ $categoria->slug }}</td>
    <td class="py-2">
        <!-- Mostra o tipo da categoria -->
        {{ $categoria->tipo }}

        @if($categoria->nivel === 'marca' && method_exists($categoria, 'tipos'))
        <!-- Se for marca e o relacionamento existir, mostra os tipos relacionados -->
        @foreach($categoria->tipos ?? [] as $tipo)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipo->pivot->is_principal ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
            {{ $tipo->nome }}
            @if($tipo->pivot->is_principal)
            <span class="ml-1 text-xs">(Principal)</span>
            @endif
        </span>
        @endforeach
        @endif
    </td>
    <td class="py-2">
        <div class="flex space-x-2">
            <a href="{{ route('web-admin.categorias.edit', $categoria->id) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('web-admin.categorias.destroy', $categoria->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>

@if($categoria->subcategorias->count())
@foreach($categoria->subcategorias as $subcategoria)
@include('web-admin.categorias._categoria-row', ['categoria' => $subcategoria, 'level' => $level + 1])
@endforeach
@endif