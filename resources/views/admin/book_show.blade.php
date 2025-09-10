@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes do Livro</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $book->title }}</h4>
            <p class="card-text"><strong>Autor:</strong> {{ $book->author }}</p>
            <p class="card-text"><strong>ID:</strong> {{ $book->id }}</p>
            <p class="card-text"><strong>Cadastrado por:</strong> {{ $user ? $user->name : 'Desconhecido' }}</p>
            <a href="{{ route('admin.books') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</div>
@endsection
