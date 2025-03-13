@extends('layouts.app')

@section('content')
    <header class="header-container">
        <nav class="nav-container">
            <img class="logo" src="{{ Vite::asset('resources/img/logo1.png') }}" alt="">
            <ul class="nav-links">
                <li>
                    <a href="#Home">Home</a>
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
                Entrar
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
            </section>
        </div>
    </header>
    <main>
        <section class="books-container">
            <h2>Livros</h2>
            <section class="categori-container">
                <h1 class="h1">Aventuras</h1>
                <section class="books">
                    <div class="book">
                        <a href="#"><img src="{{ Vite::asset('resources/img/book1.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                    <a href="#"><img src="{{ Vite::asset('resources/img/book1.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                    <a href="#"><img src="{{ Vite::asset('resources/img/book1.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                        <a href="#"><img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                        <a href="#"><img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                        <a href="#"><img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                        <a href="#"><img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>
                    <div class="book">
                        <a href="#"><img src="{{ Vite::asset('resources/img/book2.jpg') }}" alt=""></a>
                        <h1>Titulo</h1>
                        <p>Autor</p>
                    </div>

                    <button class="button-left"><</button>
                    <button class="button-right">></button>

                </section>
                <button class="SeeMore">Ver mais</button>

            </section>
    </main>
@endsection