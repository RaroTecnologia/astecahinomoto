@extends('layouts.app')

@section('title', 'Tipos de Produtos')

@section('content')
<div class="container">
    <h1>Tipos de Produtos</h1>
    <ul>
        @foreach($tipos as $tipo)
        <li><a href="{{ route('marcas.tipo', $tipo->slug) }}">{{ $tipo->nome }}</a></li>
        @endforeach
    </ul>
</div>
@endsection