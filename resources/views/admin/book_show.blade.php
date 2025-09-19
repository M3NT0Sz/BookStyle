@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes do Livro</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ is_array($book) ? ($book['title'] ?? '') : $book->title }}</h4>
            <p class="card-text"><strong>Autor:</strong> {{ is_array($book) ? ($book['author'] ?? '') : $book->author }}</p>
            <p class="card-text"><strong>ID:</strong> {{ is_array($book) ? ($book['id'] ?? '') : $book->id }}</p>
            <p class="card-text"><strong>Cadastrado por:</strong> {{ $user ? (is_array($user) ? ($user['name'] ?? 'Desconhecido') : $user->name) : 'Desconhecido' }}</p>
            <a href="{{ route('admin.books') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</div>
@endsection
