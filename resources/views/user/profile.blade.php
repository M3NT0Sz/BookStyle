@extends('layouts.profile')

@section('content')


<header>
    <section class="banner">
        <img class="fundo" src="{{ Vite::asset('resources/img/fundoPerfil.png') }}" alt="">
        <ion-icon class="icon-edit" name="create-outline"></ion-icon>
    </section>
    <section class="profile">
        <img src="{{ Vite::asset('resources/img/sueli.png') }}" alt="">
        <h1>Sueli Silva</h1>
    </section>

</header>
<main>
    <section class="my-menu"> 
        <section class="my-book">
            <h1 class="my-book-h1">Meus livros</h1>
            <button id="toggle-books" class="button-my-book"><i class="fas fa-book-open"></i></button>

        </section>
        <section class="my-book">
            <h1 class="my-book-h1">Meus Pedidos</h1>
            <button class="button-my-book"><ion-icon name="cart-outline"></ion-icon></button>
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
    </section>


    <a href="{{ route('books.create') }}">Cadastrar livro</a>
    <a href="{{ route('index') }}">Voltar</a>





</main>