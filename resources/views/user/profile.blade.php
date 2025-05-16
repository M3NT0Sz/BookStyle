@extends('layouts.profile')

@section('content')


<header>
    <section class="banner">
        <img class="fundo" src="{{ Vite::asset('resources/img/fundoPerfil.png') }}" alt="">
        <ion-icon class="icon-edit" name="create-outline"></ion-icon>
    </section>
    <section class="profile">
        <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}">
        <h1>{{ $user->name }}</h1>
    </section>
</header>
<main>

    <div class="nav_profile">
        <nav class="nav_links">
            <ul class="nav_itens">
                <span class="ball"></span>
                <li class="nav_objects active">
                    <a href="#perfil">
                        <i class="fa-regular fa-user icon_profile_user"></i>
                        <span class="text_profile">Perfil</span>
                    </a>
                </li>
                <li class="nav_objects ">
                    <a href="#livros">
                        <i class="fa-solid fa-book icon_profile_book"></i>
                        <span class="text_profile_book">Livros</span>
                    </a>
                </li>
                <li class="nav_objects">
                    <a href="#config">
                        <i class="fa-solid fa-wrench icon_profile"></i>
                        <span class="text_profile_config">Configurações</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <section id="perfil" class="my-perfil ">
        <h2>Deu certo Perfil</h2>
    </section>

    <section id="config" class="config hidden">
        <form action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ $user->name }}" placeholder="Nome">
            <input type="email" name="email" value="{{ $user->email }}" placeholder="Email">
            <label for="image">Foto de Perfil</label>
            <input type="file" name="image" id="image" accept="image/*">
            <button>Atualizar</button>
        </form>
    </section>

    <section id="livros" class="my-menu hidden">
        <section class="my-book">
            <h1 class="my-book-h1">Meus livros</h1>
            <button id="toggle-books" class="button-my-book"><i class="fas fa-book-open"></i></button>
        </section>
        <section class="my-book">
            <h1 class="my-book-h1">Meus Pedidos</h1>
            <button class="button-my-book"><i class="fa-solid fa-cart-shopping"></i></button>
        </section>
    </section>

    <section id="container-book" class="container-book">
        <section class="book-list">
            @foreach ($books as $book)
                <div class="book-item">
                    @php
                        $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                    @endphp
                    @if(!empty($images))
                        @foreach($images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $book->name }}">
                        @endforeach
                    @endif
                    <h2>{{ $book->name }}</h2>
                    <p> R$ {{ number_format($book->price, 2, ',', '.') }} </p>
                    <button class="editar-book"><a href="{{ route('books.edit', $book->id) }}">Editar</a></button>
                    <form action="{{ route('books.destroy', $book->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="button-delete">Deletar</button>
                    </form>
                </div>
            @endforeach
        </section>
        <a class="btn-more" href="{{ route('books.create') }}">Cadastrar livro</a>

        <a href="{{ route('coupons.index') }}">
            Cadastrar cupom
        </a>
    </section>
    <a class="btn-back" href="{{ route('index') }}">Voltar</a>
</main>