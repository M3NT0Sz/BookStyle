@extends('layouts.app')

@section('content')
    <header class="header-container">
        <nav class="nav-container">
            <a href="{{route('index')}}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt=""></a>
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
                <a href="#">Entrar</a>
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
            </section>
        </div>
    </header>
    <main>
        <section class="book-container">
            <h1>Alguns livros</h1>
            <section class="book-content">
                <section class="pages-book">
                    <section class="book">
                        <img src="{{ Vite::asset('resources/img/book1.jpg') }}" alt="">
                        <h2>Nome do Livro</h2>
                        <p>Descrição do livro</p>
                        <button><a href="">Comprar</a></button>
                    </section>
                    <section class="book">
                        <img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt="">
                        <h2>Nome do Livro</h2>
                        <p>Descrição do livro</p>
                        <button><a href="">Comprar</a></button>
                    </section>
                    <section class="book">
                        <img src="{{ Vite::asset('resources/img/book1.jpg') }}" alt="">
                        <h2>Nome do Livro</h2>
                        <p>Descrição do livro</p>
                        <button><a href="">Comprar</a></button>
                    </section>
                    <section class="book">
                        <img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt="">
                        <h2>Nome do Livro</h2>
                        <p>Descrição do livro</p>
                        <button><a href="">Comprar</a></button>
                    </section>
                </section>
                <button class="button-more">
                    <a href="">Ver Mais</a>
                </button>
            </section>
            <button class="next">></button>
            <button class="prev"><</button>
        </section>
    </main>
@endsection