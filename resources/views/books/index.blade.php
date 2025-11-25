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
                                <div class="book-listing-card" onclick="window.location.href='{{ route('books.show', is_array($book) ? $book['id'] : $book->id) }}'" style="cursor: pointer;">
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

                                        @auth
                                            <button onclick="toggleWishlist({{ $bookData['id'] }}); event.stopPropagation();" 
                                                    data-wishlist-btn="{{ $bookData['id'] }}"
                                                    class="wishlist-btn-listing"
                                                    title="Adicionar aos favoritos">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        @endauth
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
                                            <a href="{{ route('books.show', $bookData['id']) }}" class="book-listing-btn primary" onclick="event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                                Ver Detalhes
                                            </a>
                                            <form action="{{ route('cart.add', ['bookId' => $bookData['id']]) }}" method="POST" class="add-to-cart-form" style="display: inline;" onclick="event.stopPropagation();">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="book-listing-btn secondary add-to-cart-btn" data-book-id="{{ $bookData['id'] }}" data-book-name="{{ $bookData['name'] }}">
                                                    <i class="fas fa-cart-plus"></i>
                                                    
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

    <style>
        .book-listing-badge {
            position: absolute;
            top: 60px;
            right: 12px;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 1;
        }

        .book-listing-badge.new {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .book-listing-badge.used {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }

        .wishlist-btn-listing {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(239, 68, 68, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .wishlist-btn-listing:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .wishlist-btn-listing i {
            font-size: 18px;
            color: #6b7280;
            transition: all 0.3s;
        }

        .wishlist-btn-listing.favorited i {
            color: #ef4444;
        }

        .wishlist-btn-listing:active {
            transform: scale(0.95);
        }

        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.2); }
            50% { transform: scale(1); }
        }

        .wishlist-btn-listing.favorited {
            animation: heartBeat 0.5s ease-in-out;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease-out;
        }

        .toast-notification.success {
            border-left: 4px solid #10b981;
        }

        .toast-notification.error {
            border-left: 4px solid #ef4444;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    <script>
        // Gerenciador de Wishlist
        function toggleWishlist(bookId) {
            const btn = document.querySelector(`[data-wishlist-btn="${bookId}"]`);
            const icon = btn.querySelector('i');
            const isFavorited = icon.classList.contains('fas');

            if (isFavorited) {
                // Remover
                fetch(`/wishlist/${bookId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        btn.classList.remove('favorited');
                        showToast('Removido dos favoritos', 'success');
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showToast('Erro ao remover dos favoritos', 'error');
                });
            } else {
                // Adicionar
                fetch('/wishlist/add', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ book_id: bookId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        btn.classList.add('favorited');
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showToast('Erro ao adicionar aos favoritos', 'error');
                });
            }
        }

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="color: ${type === 'success' ? '#10b981' : '#ef4444'}; font-size: 20px;"></i>
                <span style="color: #374151; font-weight: 500;">${message}</span>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Verificar quais livros estão na wishlist ao carregar a página
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('[data-wishlist-btn]');
            buttons.forEach(btn => {
                const bookId = btn.getAttribute('data-wishlist-btn');
                fetch(`/wishlist/check/${bookId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.inWishlist) {
                            const icon = btn.querySelector('i');
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            btn.classList.add('favorited');
                        }
                    })
                    .catch(error => console.error('Erro ao verificar wishlist:', error));
            });
        });
        @endauth
    </script>

@push('scripts')
@vite('resources/js/books.js')
@endpush

@endsection