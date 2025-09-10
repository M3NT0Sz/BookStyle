@extends('layouts.app')

@section('content')
    @vite('resources/css/bookShow.css')
    @include('components.nav_bar')
    
    <!-- Hero Section -->
    <div class="book-details-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('index') }}">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <i class="fas fa-chevron-right"></i>
                <a href="{{ route('books.index') }}">Livros</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ is_array($book) ? $book['name'] : $book->name }}</span>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Main Content -->
    <div class="book-details-main">
        <div class="container">
            <div class="book-details-layout">
                <!-- Image Gallery Section -->
                <div class="book-gallery-section">
                    @php
                        $images = is_array($book)
                            ? (isset($book['images']) ? (is_array($book['images']) ? $book['images'] : json_decode($book['images'], true)) : [])
                            : (isset($book->images) ? (is_array($book->images) ? $book->images : json_decode($book->images, true)) : []);
                        if (!is_array($images)) $images = [];
                        $images = array_filter(array_map(function($img) {
                            return trim($img);
                        }, $images), fn($img) => !empty($img));
                        
                        $bookName = is_array($book) ? $book['name'] : $book->name;
                        $bookId = is_array($book) ? $book['id'] : $book->id;
                    @endphp

                    <div class="gallery-container">
                        <div class="main-image">
                            @if(count($images) > 0)
                                <img id="mainBookImage" 
                                     src="{{ asset('storage/' . $images[0]) }}" 
                                     alt="{{ $bookName }}">
                            @else
                                <div class="placeholder-image">
                                    <i class="fas fa-book"></i>
                                    <p>Imagem não disponível</p>
                                </div>
                            @endif
                            
                            <div class="image-overlay">
                                <button class="zoom-btn" onclick="openImageModal()">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        @if(count($images) > 1)
                            <div class="thumbnail-gallery">
                                @foreach($images as $idx => $img)
                                    <div class="thumbnail{{ $idx === 0 ? ' active' : '' }}" 
                                         onclick="changeMainImage('{{ asset('storage/' . $img) }}', this)">
                                        <img src="{{ asset('storage/' . $img) }}" alt="Imagem {{ $idx + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Book Information Section -->
                <div class="book-info-section">
                    <div class="book-header">
                        <div class="book-category">
                            <i class="fas fa-tag"></i>
                            @php
                                $genre = is_array($book) ? $book['genre'] : $book->genre;
                                if (is_string($genre)) {
                                    $genres = json_decode($genre, true);
                                    if (!is_array($genres)) $genres = [$genre];
                                } else {
                                    $genres = is_array($genre) ? $genre : [$genre];
                                }
                            @endphp
                            {{ is_array($genres) ? implode(', ', array_map('ucfirst', $genres)) : ucfirst($genres) }}
                        </div>
                        
                        <h1 class="book-title">{{ $bookName }}</h1>
                        
                        <div class="book-author">
                            <i class="fas fa-user-edit"></i>
                            <span>por <strong>{{ is_array($book) ? $book['author'] : $book->author }}</strong></span>
                        </div>
                        
                        <div class="book-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="rating-text">(4.8) • 127 avaliações</span>
                        </div>
                    </div>
                    
                    <div class="book-condition">
                        @php $condition = is_array($book) ? $book['condition'] : $book->condition; @endphp
                        <div class="condition-badge {{ $condition }}">
                            <i class="fas {{ $condition === 'new' ? 'fa-star' : 'fa-recycle' }}"></i>
                            {{ $condition === 'new' ? 'Novo' : 'Usado' }}
                        </div>
                        @if($condition === 'used')
                            <div class="eco-badge">
                                <i class="fas fa-leaf"></i>
                                Escolha sustentável
                            </div>
                        @endif
                    </div>

                    <div class="book-description">
                        <h3><i class="fas fa-align-left"></i> Sobre o livro</h3>
                        <p>{{ is_array($book) ? $book['description'] : $book->description }}</p>
                    </div>

                    <!-- Product Specifications -->
                    <div class="book-specs">
                        <h3><i class="fas fa-info-circle"></i> Especificações</h3>
                        <div class="specs-grid">
                            <div class="spec-item">
                                <span class="spec-label">Autor:</span>
                                <span class="spec-value">{{ is_array($book) ? $book['author'] : $book->author }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Gênero:</span>
                                <span class="spec-value">{{ is_array($genres) ? implode(', ', array_map('ucfirst', $genres)) : ucfirst($genres) }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Condição:</span>
                                <span class="spec-value">{{ $condition === 'new' ? 'Novo' : 'Usado' }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Tipo:</span>
                                <span class="spec-value">
                                    @php $productType = is_array($book) ? ($book['product_type'] ?? 'fisico') : ($book->product_type ?? 'fisico'); @endphp
                                    {{ ucfirst($productType) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Section -->
                <div class="purchase-section">
                    <div class="purchase-card">
                        <div class="price-section">
                            <div class="current-price">
                                R$ {{ number_format(is_array($book) ? $book['price'] : $book->price, 2, ',', '.') }}
                            </div>
                            @if($condition === 'used')
                                <div class="price-savings">
                                    <i class="fas fa-leaf"></i>
                                    Economia sustentável
                                </div>
                            @endif
                        </div>

                        <div class="availability">
                            <i class="fas fa-check-circle"></i>
                            <span>Disponível em estoque</span>
                        </div>

                        <form class="purchase-form" action="{{ route('cart.add', $bookId) }}" method="POST">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $bookId }}">
                            
                            <div class="quantity-selector">
                                <label for="quantity">Quantidade:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn minus" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                                    <button type="button" class="qty-btn plus" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart"></i>
                                Adicionar ao Carrinho
                            </button>
                            
                            <button type="button" class="buy-now-btn">
                                <i class="fas fa-bolt"></i>
                                Comprar Agora
                            </button>
                        </form>

                        <div class="security-badges">
                            <div class="badge">
                                <i class="fas fa-shield-alt"></i>
                                <span>Compra Segura</span>
                            </div>
                            <div class="badge">
                                <i class="fas fa-undo"></i>
                                <span>7 dias para trocar</span>
                            </div>
                            <div class="badge">
                                <i class="fas fa-truck"></i>
                                <span>Entrega rápida</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('books.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Voltar aos Livros
                </a>
                <button class="btn btn-secondary" onclick="shareBook()">
                    <i class="fas fa-share"></i>
                    Compartilhar
                </button>
                <button class="btn btn-secondary" onclick="addToWishlist()">
                    <i class="fas fa-heart"></i>
                    Lista de Desejos
                </button>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <button class="modal-close" onclick="closeImageModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    @include('components.footer')
    @vite('resources/js/bookShow.js')
@endsection


