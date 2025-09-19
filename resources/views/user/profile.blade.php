@extends('layouts.profile')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-banner">
            <img class="profile-banner-img" src="{{ Vite::asset('resources/img/fundoPerfil.png') }}" alt="Banner do perfil">
            <label for="image" class="profile-edit-icon">
                <ion-icon name="create-outline"></ion-icon>
            </label>
        </div>
        <div class="profile-info">
            <img class="profile-avatar" src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}">
            <h1 class="profile-name">{{ $user->name }}</h1>
            <p class="profile-email">{{ $user->email }}</p>
        </div>
    </div>
    <div class="profile-main">
        <nav class="profile-nav">
            <a href="#dados" class="profile-nav-link active">Dados</a>
            <a href="#livros" class="profile-nav-link">Livros</a>
            <a href="#pedidos" class="profile-nav-link">Pedidos</a>
            <a href="#config" class="profile-nav-link">Configurações</a>
        </nav>
        <section id="dados" class="profile-section">
            <h2>Meus Dados</h2>
            <ul class="profile-data-list">
                <li><strong>Nome:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
            </ul>
        </section>
        <section id="livros" class="profile-section">
            <h2>Meus Livros</h2>
            <div class="profile-books">
                @forelse ($books as $book)
                    <div class="profile-book-item">
                        @php
                            $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                        @endphp
                        @if(!empty($images))
                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}" class="profile-book-img">
                        @endif
                        <div class="profile-book-info">
                            <h3>{{ $book->name }}</h3>
                            <p>R$ {{ number_format($book->price, 2, ',', '.') }}</p>
                        </div>
                        <div class="profile-book-actions">
                            <a href="{{ route('books.edit', $book->id) }}" class="profile-book-edit">Editar</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="profile-book-delete">Deletar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Você ainda não cadastrou livros.</p>
                @endforelse
            </div>
            <a class="profile-btn" href="{{ route('books.create') }}">Cadastrar livro</a>
        </section>
        <section id="pedidos" class="profile-section">
            <h2>Meus Pedidos</h2>
            <p>Em breve você poderá acompanhar seus pedidos aqui.</p>
        </section>
        <section id="config" class="profile-section">
            <h2>Configurações</h2>
            <form class="profile-form" action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label for="name">Nome</label>
                <input type="text" name="name" value="{{ $user->name }}" placeholder="Nome">
                <label for="email">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" placeholder="Email">
                <label for="image">Foto de Perfil</label>
                <input type="file" name="image" id="image" accept="image/*">
                <button class="profile-btn">Atualizar</button>
            </form>
        </section>
    </div>
    <a class="profile-btn profile-btn-back" href="{{ route('index') }}">Voltar</a>
</div>
@endsection