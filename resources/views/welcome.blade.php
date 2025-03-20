@extends('layouts.app')

@section('content')
    <header class="header-container">
        <nav class="nav-container">
            <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                    alt=""></a>
            <ul class="nav-links">
                <li>
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#Home">Home</a>
                </li>
                <li>
                    <a href="#Home">Home</a>
                </li>
                <li>
                    <a href="#Home">Home</a>
                </li>
            </ul>
            <button class="button-login">
                @if(Auth::check())
                    <a href="{{ route('user.profile') }}">Perfil</a>
                @else
                    <a href="{{ route('login') }}">Entrar</a>
                @endif
            </button>
        </nav>

        <div class="banner-container">
            <h1>BookStyle</h1>
            <p>Encontre suas Histórias</p>
            <section class="search-container">
                <input type="text" placeholder="Pesquise por um livro">

            </section>
            <section class="books-down">
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
                <span class="book1"></span>
                <span class="book2"></span>
                <span class="book3"></span>
                <span class="book4"></span>
            </section>
        </div>
    </header>
    <main>
        <section class="book-container">
            <h1>Alguns livros</h1>
            <section class="book-content">
                <section class="pages-book">
                    @foreach ($books as $book)
                        <section class="book">
                            @if(!empty($book->images))
                                <img src="{{ asset($book->images) }}" alt="{{ $book->name }}">
                            @endif
                            <h2> {{ $book->name }} </h2>
                            <p> {{ $book->description }} </p>
                            <button><a href="{{ route('books.show', $book->id) }}">Comprar</a></button>
                        </section>
                    @endforeach
                </section>
                <button class="button-more">
                    <a href="{{ route('books.index') }}">Ver Mais</a>
                </button>
            </section>
            <button class="next">></button>
            <button class="prev">
                < </button>
        </section>


        <section class="about-container">
            <section class="about-left">
                <h1>QUEM SOMOS</h1>
                <p>BookStyle é uma plataforma de venda de livros online, onde você pode encontrar os melhores livros do
                    mercado, com os melhores preços e as melhores condições de pagamento. A BookStyle é uma empresa que visa
                    a satisfação do cliente, por isso, trabalhamos com os melhores fornecedores do mercado, para que você
                    tenha a melhor experiência de compra possível.</p>
                <a href="#">Nos conheça melhor</a>
            </section>
            <section class="about-right">

            </section>
        </section>


    </main>
@endsection