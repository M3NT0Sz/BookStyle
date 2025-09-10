@extends('layouts.app')

@section('content')
    <header class="header-container">
        <nav class="nav-container">
            <a href="{{ route('index') }}" class="logo-link">
                <img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt="BookStyle Logo">
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('index') }}" class="active">Home</a></li>
                <li><a href="{{ route('about') }}">Quem Somos</a></li>
                <li><a href="{{route('books.index') }}">Livros</a></li>
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

            <div class="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>

        <div class="hero-banner">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">BookStyle</h1>
                    <p class="hero-subtitle">Conectando histórias, conectando pessoas</p>
                    <p class="hero-description">Descubra seu próximo livro favorito em nossa coleção cuidadosamente selecionada</p>
                    
                    <div class="hero-search">
                        <form action="{{ route('books.index') }}" method="GET" class="search-form">
                            <div class="search-input-group">
                                <input type="text" name="search" placeholder="Pesquisar livros, autores ou categorias..." class="search-input">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Livros</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">200+</span>
                            <span class="stat-label">Usuários</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">Avaliações</span>
                        </div>
                    </div>
                </div>
                
                <div class="hero-visual">
                    <div class="floating-books">
                        <div class="book-float book-1">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="book-float book-2">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="book-float book-3">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        <div class="book-float book-4">
                            <i class="fas fa-book-reader"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hero-scroll-indicator">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </header>
    <main class="main-content">
        <!-- Seção de Categorias -->
        <section class="categories-section">
            <div class="container">
                <h2 class="section-title">
                    <i class="fas fa-th-large"></i>
                    Explore por Categoria
                </h2>
                <div class="categories-grid">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Romance</h3>
                        <p>Histórias de amor que tocam o coração</p>
                        <a href="{{ route('books.index', ['genre' => 'romance']) }}" class="category-link">Explorar</a>
                    </div>
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Ficção Científica</h3>
                        <p>Aventuras em mundos futuristas</p>
                        <a href="{{ route('books.index', ['genre' => 'ficcao']) }}" class="category-link">Explorar</a>
                    </div>
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-dragon"></i>
                        </div>
                        <h3>Fantasia</h3>
                        <p>Mundos mágicos e criaturas míticas</p>
                        <a href="{{ route('books.index', ['genre' => 'fantasia']) }}" class="category-link">Explorar</a>
                    </div>
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-mask"></i>
                        </div>
                        <h3>Mistério</h3>
                        <p>Enigmas e suspense envolvente</p>
                        <a href="{{ route('books.index', ['genre' => 'misterio']) }}" class="category-link">Explorar</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Livros Novos -->
        <section class="books-showcase new-books">
            <div class="container">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            <i class="fas fa-star"></i>
                            Livros Novos
                        </h2>
                        <p class="section-subtitle">Descobertas recentes para sua biblioteca</p>
                    </div>
                    <a href="{{ route('books.index') }}" class="view-all-btn">
                        Ver todos
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="books-carousel" id="newBooksCarousel">
                    <div class="books-grid">
                        @foreach ($booksNew as $book)
                            @php $book = (object) $book; @endphp
                            <div class="book-card">
                                <div class="book-image">
                                    @if(!empty($book->images))
                                        @php
                                            $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                                        @endphp
                                        @if(is_array($images) && !empty($images))
                                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}" loading="lazy">
                                        @elseif(!is_array($images))
                                            <img src="{{ asset($book->images) }}" alt="{{ $book->name }}" loading="lazy">
                                        @endif
                                    @else
                                        <div class="placeholder-image">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                    <div class="book-badge">Novo</div>
                                    <div class="book-overlay">
                                        <button class="quick-view-btn" onclick="quickView({{ $book->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="add-to-cart-btn" onclick="addToCart({{ $book->id }})">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="book-info">
                                    <h3 class="book-title">{{ $book->name }}</h3>
                                    <p class="book-author">{{ $book->author ?? 'Autor não informado' }}</p>
                                    <div class="book-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="rating-text">(4.5)</span>
                                    </div>
                                    <div class="book-price">
                                        <span class="current-price">R$ {{ $book->price }}</span>
                                    </div>
                                    <a href="{{ route('books.show', $book->id) }}" class="book-btn">
                                        <i class="fas fa-shopping-bag"></i>
                                        Comprar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button class="carousel-btn prev" onclick="moveCarousel('newBooksCarousel', -1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-btn next" onclick="moveCarousel('newBooksCarousel', 1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Seção Livros Usados -->
        <section class="books-showcase used-books">
            <div class="container">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            <i class="fas fa-recycle"></i>
                            Livros Usados
                        </h2>
                        <p class="section-subtitle">Tesouros literários com preços especiais</p>
                    </div>
                    <a href="{{ route('books.index', ['condition' => 'used']) }}" class="view-all-btn">
                        Ver todos
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="books-carousel" id="usedBooksCarousel">
                    <div class="books-grid">
                        @foreach ($booksOld as $book)
                            @php $book = (object) $book; @endphp
                            <div class="book-card">
                                <div class="book-image">
                                    @if(!empty($book->images))
                                        @php
                                            $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                                        @endphp
                                        @if(is_array($images) && !empty($images))
                                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}" loading="lazy">
                                        @elseif(!is_array($images))
                                            <img src="{{ asset($book->images) }}" alt="{{ $book->name }}" loading="lazy">
                                        @endif
                                    @else
                                        <div class="placeholder-image">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                    <div class="book-badge used">Usado</div>
                                    <div class="book-overlay">
                                        <button class="quick-view-btn" onclick="quickView({{ $book->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="add-to-cart-btn" onclick="addToCart({{ $book->id }})">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="book-info">
                                    <h3 class="book-title">{{ $book->name }}</h3>
                                    <p class="book-author">{{ $book->author ?? 'Autor não informado' }}</p>
                                    <div class="book-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="rating-text">(4.2)</span>
                                    </div>
                                    <div class="book-price">
                                        <span class="current-price">R$ {{ $book->price }}</span>
                                        <span class="savings">Economia sustentável!</span>
                                    </div>
                                    <a href="{{ route('books.show', $book->id) }}" class="book-btn">
                                        <i class="fas fa-shopping-bag"></i>
                                        Comprar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button class="carousel-btn prev" onclick="moveCarousel('usedBooksCarousel', -1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-btn next" onclick="moveCarousel('usedBooksCarousel', 1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>

                <!-- Seção Sobre -->
        <section class="about-section">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2 class="section-title">
                            <i class="fas fa-book-heart"></i>
                            Por que escolher o BookStyle?
                        </h2>
                        <div class="about-description">
                            <p>Somos mais que uma livraria online - somos uma <strong>comunidade apaixonada por literatura</strong>. No BookStyle, conectamos leitores e histórias de forma única e sustentável.</p>
                            
                            <div class="features-grid">
                                <div class="feature-item">
                                    <i class="fas fa-shipping-fast"></i>
                                    <div>
                                        <h4>Entrega Rápida</h4>
                                        <p>Receba seus livros em casa rapidamente</p>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <div>
                                        <h4>Compra Segura</h4>
                                        <p>Transações protegidas e confiáveis</p>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-leaf"></i>
                                    <div>
                                        <h4>Sustentável</h4>
                                        <p>Promovemos a reutilização de livros</p>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <h4>Comunidade</h4>
                                        <p>Conectamos leitores apaixonados</p>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('about') }}" class="about-cta-btn">
                                <i class="fas fa-arrow-right"></i>
                                Conheça nossa história
                            </a>
                        </div>
                    </div>
                    <div class="about-visual">
                        <div class="about-image">
                            <div class="floating-elements">
                                <div class="float-item float-1">📚</div>
                                <div class="float-item float-2">❤️</div>
                                <div class="float-item float-3">🌱</div>
                                <div class="float-item float-4">✨</div>
                            </div>
                            <div class="main-visual">
                                <i class="fas fa-book-reader"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Newsletter -->
        <section class="newsletter-section">
            <div class="container">
                <div class="newsletter-content">
                    <div class="newsletter-header">
                        <div class="newsletter-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h2 class="newsletter-title">📖 Fique por dentro das novidades!</h2>
                        <p class="newsletter-subtitle">Receba as últimas atualizações sobre novos livros, promoções e eventos especiais diretamente no seu e-mail</p>
                    </div>
                    
                    <form class="newsletter-form" action="#" method="POST">
                        @csrf
                        <div class="newsletter-input-group">
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" placeholder="Digite seu melhor e-mail" required class="newsletter-input">
                            </div>
                            <button type="submit" class="newsletter-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span>Inscrever-se</span>
                            </button>
                        </div>
                        <p class="newsletter-disclaimer">
                            <i class="fas fa-shield-alt"></i>
                            Prometemos não enviar spam. Você pode cancelar a qualquer momento.
                        </p>
                    </form>
                    
                    <div class="newsletter-benefits">
                        <div class="benefit-item">
                            <i class="fas fa-star"></i>
                            <span>Novos lançamentos</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-percentage"></i>
                            <span>Promoções exclusivas</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Eventos especiais</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção de Depoimentos -->
        <section class="testimonials-section">
            <div class="container">
                <div class="testimonials-header">
                    <h2 class="section-title">
                        <i class="fas fa-quote-left"></i>
                        O que nossos leitores dizem
                    </h2>
                    <p class="section-subtitle">Descubra por que milhares de leitores confiam na BookStyle</p>
                </div>
                
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left quote-icon"></i>
                        </div>
                        <div class="testimonial-content">
                            <div class="stars-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="rating-text">5.0</span>
                            </div>
                            <p class="testimonial-text">"Plataforma incrível! Encontrei livros que procurava há anos com preços justos e entrega super rápida. A curadoria dos livros é excepcional!"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar maria">
                                <span>M</span>
                            </div>
                            <div class="author-info">
                                <h4>Maria Silva</h4>
                                <span>Leitora assídua • São Paulo</span>
                                <div class="author-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Cliente verificado
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card featured">
                        <div class="featured-badge">
                            <i class="fas fa-crown"></i>
                            Destaque
                        </div>
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left quote-icon"></i>
                        </div>
                        <div class="testimonial-content">
                            <div class="stars-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="rating-text">5.0</span>
                            </div>
                            <p class="testimonial-text">"Adoro a ideia de dar nova vida aos livros! Comprei vários usados em ótimo estado e economizei muito. Sustentabilidade e economia juntas!"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar joao">
                                <span>J</span>
                            </div>
                            <div class="author-info">
                                <h4>João Santos</h4>
                                <span>Estudante universitário • Rio de Janeiro</span>
                                <div class="author-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Cliente verificado
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left quote-icon"></i>
                        </div>
                        <div class="testimonial-content">
                            <div class="stars-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="rating-text">5.0</span>
                            </div>
                            <p class="testimonial-text">"Interface moderna e fácil de usar. O atendimento é excepcional e sempre me ajudam com tudo. Recomendo para todos os meus alunos!"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar ana">
                                <span>A</span>
                            </div>
                            <div class="author-info">
                                <h4>Ana Costa</h4>
                                <span>Professora • Belo Horizonte</span>
                                <div class="author-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Cliente verificado
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonials-stats">
                    <div class="stat-item">
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Avaliação média</div>
                        <div class="stat-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">1.2k+</div>
                        <div class="stat-label">Avaliações</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Recomendariam</div>
                    </div>
                </div>
            </div>
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

@push('scripts')
@vite('resources/js/home.js')
@endpush

@endsection