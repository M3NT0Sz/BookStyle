@extends('layouts.booksSell')

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
        <section class="filter_containers">
            <button class="toggle-button" id="toggleFilter">
                <i class="fas fa-book" id="toggleIcon"></i>
            </button>
            <form action="" method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="genre">Gênero:</label>
                    <select id="genre" name="genre">
                        <option value="">Selecione</option>
                        <option value="ficcao">Ficção</option>
                        <option value="romance">Romance</option>
                        <option value="aventura">Aventura</option>
                        <option value="fantasia">Fantasia</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="author">Autor:</label>
                    <input type="text" id="author" name="author" placeholder="Digite o nome do autor">
                </div>
                <div class="filter-group">
                    <label for="price">Preço Máximo:</label>
                    <input type="range" id="price" name="price" min="0" max="1000" step="10"
                        oninput="priceOutput.value = this.value">
                    <p>R$ <output id="priceOutput">500</output></p>
                </div>
                <div class="filter-group">
                    <label for="condition">Condição:</label>
                    <select id="condition" name="condition">
                        <option value="">Selecione</option>
                        <option value="novo">Novo</option>
                        <option value="usado">Usado</option>
                    </select>
                </div>
                <button type="submit" class="filter-button">Filtrar</button>
            </form>
        </section>
        <section class="books_container">
            @foreach ($books as $book)
                <div>
                    @if(!empty($book->images))
                            @php
                                $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                            @endphp
                            @if(is_array($images) && !empty($images))
                                <img width="200" src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}">
                            @elseif(!is_array($images))
                                <img width="200" src="{{ asset($book->images) }}" alt="{{ $book->name }}">
                            @endif
                    @endif
                    <h2>{{ $book->name }}</h2>
                    <p>{{ $book->author }}</p>
                    <p>{{ $book->condition }}</p>
                    <p>R$ {{ $book->price }}</p>
                    <a href="{{ route('books.show', $book->id) }}">
                        <button>Comprar</button>
                    </a>
                </div>
            @endforeach
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