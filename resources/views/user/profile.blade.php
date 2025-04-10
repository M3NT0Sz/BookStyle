@extends('layouts.profile')

@section('content')


<header>
    <section class="banner">
        <img class="fundo" src="{{ Vite::asset('resources/img/fundoPerfil.png') }}" alt="">
        <ion-icon class="icon-edit" name="create-outline"></ion-icon>
    </section>
    <section class="profile">
        <img src="{{ Vite::asset('resources/img/sueli.png') }}" alt="">
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
        <h1>Deu certo config</h1>
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
                @if(!empty($book->images))
                @php
                $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                @endphp
                @if(is_array($images))
                @foreach($images as $image)
                <img src="{{ asset('storage/' . $image) }}" alt="{{ $book->name }}">
                @endforeach
                @else
                <img src="{{ asset($book->images) }}" alt="{{ $book->name }}">
                @endif
                @endif
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
    </section>



    <a class="btn-back" href="{{ route('index') }}">Voltar</a>





</main>