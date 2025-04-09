@extends('layouts.app')

@section('content')
    <header class="header-container">
        <nav class="nav-container">
            <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                    alt=""></a>
            <ul class="nav-links">
                <li>
                    <a href="{{ route('index') }}">Home</a>
                </li>
                <li>
                    <a href="{{ route('about') }}">Quem Somos</a>
                </li>
                <li>
                    <a href="{{route('books.index') }}">Livros</a>
                </li>
            </ul>

            @if(Auth::check())
                <a class="button-login" href="{{ route('user.profile') }}">Perfil</a>
            @else
                <a class="button-login" href="{{ route('login') }}">Entrar</a>
            @endif

        </nav>

        <div class="banner-container">
            <h1>BookStyle</h1>
            <p>Encontre suas Histórias </p>
            
            <section class="search-container">
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
            <h1>Livros Novos</h1>
            <section class="book-content">
                <section class="pages-book">
                    @foreach ($booksNew as $book)
                    <section class="book">
                    <div class="book_img">
                        @if(!empty($book->images))
                        @php
                            $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                        @endphp
                        @if(is_array($images) && !empty($images))
                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}">
                        @elseif(!is_array($images))
                            <img src="{{ asset($book->images) }}" alt="{{ $book->name }}">
                        @endif
                        @endif
                    </div>
                        
                        <h2>{{ $book->name }}</h2>
                        <p>R${{ $book->price }}</p>
                        <button><a href="{{ route('books.show', $book->id) }}">Comprar</a></button>
                    </section>
                    @endforeach
                </section>
                <a class="button-more" href="{{ route('books.index') }}">Ver Mais</a>
            </section>
            <button class="B1">></button>
            <button class="B2"><</button>
        </section>
        
        <section class="book-container">
            <h1>Livros Antigos</h1>
            <section class="book-content">
                <section class="pages-book">
                    @foreach ($booksOld as $book)
                    <section class="book">
                        <div class="book_img">
                                @if(!empty($book->images))
                            @php
                                $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                            @endphp
                            @if(is_array($images) && !empty($images))
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}">
                            @elseif(!is_array($images))
                                <img src="{{ asset($book->images) }}" alt="{{ $book->name }}">
                            @endif
                            @endif
                        </div>
                        
                        <h2>{{ $book->name }}</h2>
                        <p>R${{ $book->price }}</p>
                        <button><a href="{{ route('books.show', $book->id) }}">Comprar</a></button>
                    </section>
                    @endforeach
                </section>
                <a class="button-more" href="{{ route('books.index') }}">Ver Mais</a>
                
            </section>
            <button class="B3">></button>
            <button class="B4"><</button>
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

    <footer class="footer-container">
        <section class="footer-content">
            <div class="footer-left">
                <img src="{{ Vite::asset('resources/img/favicon.png') }}" alt="">
                <h1>BookStyle</h1>
                <p>Encontre suas Histórias</p>
            </div>
            <div class="footer-center">
                <div class="footer-list">
                    <h2>Blogs</h2>
                    <ul class="footer-links">
                        <li>
                            <a href="#">Comunidade</a>
                        </li>
                        <li>
                            <a href="#">Livros</a>
                        </li>
                        <li>
                            <a href="#">Historias</a>
                        </li>
                    </ul>
                </div>

                <div class="footer-list">
                    <h2>Products</h2>
                    <ul class="footer-links">
                        <li>Livros</li>
                        <li>HQs</li>
                        <li>Books</li>
                    </ul>
                </div>

            </div>
            <div class="footer-right">
                <h1>Contato</h1>
                <p>Caso exista alguma duviva ou problema entra em contato com nós atraves do E-mail</p>
                
                <a href="mailto:contato@bookstyle.com" class="footer-email">contato@bookstyle.com</a>

            </div>
        </section>

        <p class="footer-bottom">@2025 BookStyle. Todos os direitos reservados.</p>

    </footer>
@endsection