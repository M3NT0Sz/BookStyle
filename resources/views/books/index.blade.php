@extends('layouts.app')

@section('content')
    <!-- Header/Navigation -->
    <header class="header-container">
        <nav class="nav-container">
            <a href="{{ route('index') }}" class="logo-link">
                <img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt="BookStyle Logo">
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('index') }}">Home</a></li>
                <li><a href="{{ route('about') }}">Quem Somos</a></li>
                <li><a href="{{ route('books.index') }}" class="active">Livros</a></li>
                <li><a href="{{ route('cart.index') }}">Carrinho</a></li>
            </ul>

            <div class="nav-actions">
                @if(Auth::check())
                    <a class="button-login" href="{{ route('user.profile') }}">
                        <i class="fas fa-user"></i>
                        Perfil
                    </a>
                @else
                    <a class="button-login" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        Entrar
                    </a>
                @endif
            </div>

            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>

        <!-- Hero Section for Books Page -->
        <div class="books-hero">
            <div class="container">
                <div class="books-hero-content">
                    <h1 class="books-hero-title">
                        <i class="fas fa-book-open"></i>
                        Nossa Biblioteca
                    </h1>
                    <p class="books-hero-subtitle">Descubra histórias incríveis e encontre seu próximo livro favorito</p>
                    
                    <!-- Search Section -->
                    <div class="books-search-section">
                        <form action="{{ route('books.index') }}" method="GET" class="books-search-form">
                            <div class="search-input-group">
                                <i class="fas fa-search search-icon"></i>
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Pesquisar por título, autor ou gênero..." 
                                    value="{{ request('search') }}" 
                                    class="search-input"
                                >
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                    Buscar
                                </button>
                            </div>
                        </form>
                        
                        <!-- Quick Filters -->
                        <div class="quick-filters">
                            <a href="{{ route('books.index', ['condition' => 'new']) }}" class="quick-filter {{ request('condition') == 'new' ? 'active' : '' }}">
                                <i class="fas fa-star"></i>
                                Novos
                            </a>
                            <a href="{{ route('books.index', ['condition' => 'used']) }}" class="quick-filter {{ request('condition') == 'used' ? 'active' : '' }}">
                                <i class="fas fa-recycle"></i>
                                Usados
                            </a>
                            <a href="{{ route('books.index', ['genre' => 'romance']) }}" class="quick-filter {{ request('genre') == 'romance' ? 'active' : '' }}">
                                <i class="fas fa-heart"></i>
                                Romance
                            </a>
                            <a href="{{ route('books.index', ['genre' => 'fantasia']) }}" class="quick-filter {{ request('genre') == 'fantasia' ? 'active' : '' }}">
                                <i class="fas fa-dragon"></i>
                                Fantasia
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Filters Navigation Bar -->
    <div class="filters-nav-container">
        <div class="container">
            <div class="filters-nav-content">
                <div class="filters-nav-title">
                    <i class="fas fa-filter"></i>
                    Filtros
                </div>
                
                <div class="filters-nav-links">
                    <a href="{{ route('books.index') }}" class="filter-nav-link {{ !request()->hasAny(['condition', 'genre']) ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        Todos
                    </a>
                    <a href="{{ route('books.index', ['condition' => 'new']) }}" class="filter-nav-link {{ request('condition') == 'new' ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        Novos
                    </a>
                    <a href="{{ route('books.index', ['condition' => 'used']) }}" class="filter-nav-link {{ request('condition') == 'used' ? 'active' : '' }}">
                        <i class="fas fa-recycle"></i>
                        Usados
                    </a>
                    <a href="{{ route('books.index', ['genre' => 'romance']) }}" class="filter-nav-link {{ request('genre') == 'romance' ? 'active' : '' }}">
                        <i class="fas fa-heart"></i>
                        Romance
                    </a>
                    <a href="{{ route('books.index', ['genre' => 'fantasia']) }}" class="filter-nav-link {{ request('genre') == 'fantasia' ? 'active' : '' }}">
                        <i class="fas fa-dragon"></i>
                        Fantasia
                    </a>
                    <a href="{{ route('books.index', ['genre' => 'ficcao']) }}" class="filter-nav-link {{ request('genre') == 'ficcao' ? 'active' : '' }}">
                        <i class="fas fa-rocket"></i>
                        Ficção
                    </a>
                    <a href="{{ route('books.index', ['genre' => 'aventura']) }}" class="filter-nav-link {{ request('genre') == 'aventura' ? 'active' : '' }}">
                        <i class="fas fa-mountain"></i>
                        Aventura
                    </a>
                </div>
                
                <div class="filters-nav-search">
                    <form action="{{ route('books.index') }}" method="GET" class="inline-search-form">
                        <div class="search-group">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Buscar livros..." 
                                value="{{ request('search') }}"
                                class="inline-search-input"
                            >
                            <button type="submit" class="inline-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="books-main">
        <div class="container">
            <div class="books-content">
                <!-- Books Results Section -->
                <section class="books-section">
                    <div class="books-header">
                        <h2>
                            @if(request('search'))
                                Resultados para "{{ request('search') }}"
                            @elseif(request('condition'))
                                Livros {{ request('condition') == 'new' ? 'Novos' : 'Usados' }}
                            @elseif(request('genre'))
                                Gênero: {{ ucfirst(request('genre')) }}
                            @else
                                Todos os Livros
                            @endif
                        </h2>
                        <p class="books-count">{{ count($books) }} livros encontrados</p>
                    </div>

                    @if(count($books) > 0)
                        <div class="books-listing-grid" id="booksGrid">
                            @foreach ($books as $book)
                                <div class="book-listing-card">
                                    <div class="book-listing-image">
                                        @php
                                            $images = is_array($book)
                                                ? (isset($book['images']) ? (is_array($book['images']) ? $book['images'] : json_decode($book['images'], true)) : [])
                                                : (isset($book->images) ? (is_array($book->images) ? $book->images : json_decode($book->images, true)) : []);
                                            $bookData = is_array($book) ? $book : $book->toArray();
                                        @endphp
                                        @if(is_array($images) && !empty($images))
                                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $bookData['name'] }}" loading="lazy">
                                        @elseif(!is_array($images) && !empty($images))
                                            <img src="{{ asset('storage/' . $images) }}" alt="{{ $bookData['name'] }}" loading="lazy">
                                        @else
                                            <div class="book-listing-placeholder">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="book-listing-badge {{ $bookData['condition'] == 'new' ? 'new' : 'used' }}">
                                            {{ $bookData['condition'] == 'new' ? 'Novo' : 'Usado' }}
                                        </div>
                                    </div>
                                    
                                    <div class="book-listing-info">
                                        <h3 class="book-listing-title">{{ $bookData['name'] }}</h3>
                                        <p class="book-listing-author">{{ $bookData['author'] ?? 'Autor não informado' }}</p>
                                        
                                        <div class="book-listing-genre">
                                            <i class="fas fa-tag"></i>
                                            {{ ucfirst($bookData['genre'] ?? 'Gênero não informado') }}
                                        </div>
                                        
                                        <div class="book-listing-rating">
                                            <div class="stars">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <span class="rating-text">(4.5)</span>
                                        </div>
                                        
                                        <div class="book-listing-price">
                                            <span class="current-price">R$ {{ number_format($bookData['price'], 2, ',', '.') }}</span>
                                            @if($bookData['condition'] == 'used')
                                                <span class="savings">Economia sustentável!</span>
                                            @endif
                                        </div>
                                        
                                        <div class="book-listing-actions">
                                            <a href="{{ route('books.show', $bookData['id']) }}" class="book-listing-btn primary">
                                                <i class="fas fa-eye"></i>
                                                Ver Detalhes
                                            </a>
                                            <form action="{{ route('cart.add', ['bookId' => $bookData['id']]) }}" method="POST" class="add-to-cart-form" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="book-listing-btn secondary add-to-cart-btn" data-book-id="{{ $bookData['id'] }}" data-book-name="{{ $bookData['name'] }}">
                                                    <i class="fas fa-cart-plus"></i>
                                                    <span class="btn-text">Carrinho</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-results">
                            <div class="no-results-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Nenhum livro encontrado</h3>
                            <p>Não encontramos livros que correspondam aos seus critérios de busca.</p>
                            <a href="{{ route('books.index') }}" class="reset-search-btn">
                                <i class="fas fa-refresh"></i>
                                Ver todos os livros
                            </a>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->

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

@push('scripts')
@vite('resources/js/books.js')
@endpush

@endsection