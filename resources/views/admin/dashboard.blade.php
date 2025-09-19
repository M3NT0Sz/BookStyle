@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('index') }}" class="btn btn-secondary mb-3">Voltar para o Início</a>
    <h1>Painel do Administrador</h1>
    <ul>
        <li><a href="{{ route('admin.books') }}">Gerenciar Livros</a></li>
        <li><a href="{{ route('admin.users') }}">Gerenciar Usuários</a></li>
        <li><a href="{{ route('admin.coupons') }}">Gerenciar Cupons</a></li>
    </ul>
</div>
@endsection
