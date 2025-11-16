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
                        // Processar imagens - pode vir como array, string JSON ou string vazia
                        $rawImages = is_array($book) ? ($book['images'] ?? null) : ($book->images ?? null);
                        
                        $images = [];
                        if (!empty($rawImages)) {
                            if (is_string($rawImages)) {
                                // Tentar decodificar JSON
                                $decoded = json_decode($rawImages, true);
                                if (is_array($decoded)) {
                                    $images = $decoded;
                                } else {
                                    // Se não for JSON válido, pode ser uma URL única
                                    $images = [$rawImages];
                                }
                            } elseif (is_array($rawImages)) {
                                $images = $rawImages;
                            }
                        }
                        
                        // Limpar e normalizar imagens
                        $images = array_filter(array_map(function($img) {
                            if (!is_string($img)) return null;
                            $img = trim($img);
                            if (empty($img)) return null;
                            // Remover prefixo 'storage/' se existir
                            $img = preg_replace('#^storage/#', '', $img);
                            return $img;
                        }, $images), fn($img) => !empty($img));
                        
                        $bookName = is_array($book) ? $book['name'] : $book->name;
                        $bookId = is_array($book) ? $book['id'] : $book->id;
                    @endphp

                    <div class="gallery-container">
                        <div class="main-image">
                            @if(count($images) > 0)
                                @php
                                    $firstImage = $images[0];
                                    // Se começar com http, usar diretamente, senão usar asset
                                    $imageUrl = (str_starts_with($firstImage, 'http') || str_starts_with($firstImage, 'https')) 
                                        ? $firstImage 
                                        : asset('storage/' . $firstImage);
                                @endphp
                                <img id="mainBookImage" 
                                     src="{{ $imageUrl }}" 
                                     alt="{{ $bookName }}"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x600?text=Imagem+Indisponível';">
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
                                    @php
                                        $thumbUrl = (str_starts_with($img, 'http') || str_starts_with($img, 'https')) 
                                            ? $img 
                                            : asset('storage/' . $img);
                                    @endphp
                                    <div class="thumbnail{{ $idx === 0 ? ' active' : '' }}" 
                                         onclick="changeMainImage('{{ $thumbUrl }}', this)">
                                        <img src="{{ $thumbUrl }}" 
                                             alt="Imagem {{ $idx + 1 }}"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/100x150?text=Erro';">
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
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($averageRating) ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">({{ number_format($averageRating, 1) }}) • {{ $totalReviews }} {{ $totalReviews == 1 ? 'avaliação' : 'avaliações' }}</span>
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
                            
                            <button type="button" class="buy-now-btn" onclick="buyNow()">
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

    <!-- Seção de Avaliações -->
    <div class="container" style="margin-top: 2rem; margin-bottom: 3rem;">
        @include('reviews.partials.reviews-list', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'ratingDistribution' => $ratingDistribution
        ])
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
    
    <script>
        // Wishlist functionality
        const BOOK_ID = {{ $bookId ?? 'null' }};
        let isInWishlist = false;
        let isProcessing = false;
        
        // Criar função de toast simples
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#f59e0b'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Substituir a função original
        window.addToWishlist = async function() {
            console.log('Clique detectado! BOOK_ID:', BOOK_ID);
            
            @guest
                showToast('Faça login para adicionar aos favoritos', 'error');
                setTimeout(() => window.location.href = '/login', 1500);
                return;
            @endguest
            
            if (!BOOK_ID || isProcessing) {
                console.log('Bloqueado - BOOK_ID:', BOOK_ID, 'isProcessing:', isProcessing);
                return;
            }
            
            isProcessing = true;
            const btn = document.querySelector('.btn.btn-secondary[onclick="addToWishlist()"]');
            const icon = btn?.querySelector('i');
            
            console.log('Botão encontrado:', btn);
            console.log('Estado atual - isInWishlist:', isInWishlist);
            
            try {
                if (isInWishlist) {
                    // Remover
                    console.log('Tentando remover...');
                    const response = await fetch(`/wishlist/${BOOK_ID}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    console.log('Resposta remover:', response.status, data);
                    
                    if (data.success) {
                        isInWishlist = false;
                        btn?.classList.remove('favorited');
                        if (icon) icon.style.color = '';
                        showToast(data.message || 'Removido dos favoritos');
                    } else {
                        throw new Error(data.message);
                    }
                } else {
                    // Adicionar
                    console.log('Tentando adicionar...');
                    const response = await fetch('/wishlist/add', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ book_id: BOOK_ID })
                    });
                    
                    const data = await response.json();
                    console.log('Resposta adicionar:', response.status, data);
                    
                    if (data.success) {
                        isInWishlist = true;
                        btn?.classList.add('favorited');
                        if (icon) icon.style.color = '#ff4757';
                        showToast(data.message || 'Adicionado aos favoritos!');
                    } else {
                        throw new Error(data.message);
                    }
                }
            } catch (error) {
                console.error('Erro completo:', error);
                showToast(error.message || 'Erro ao processar', 'error');
            } finally {
                isProcessing = false;
            }
        };
        
        // Verificar estado inicial
        window.addEventListener('DOMContentLoaded', async function() {
            console.log('Página carregada, verificando estado...');
            
            if (!BOOK_ID) {
                console.log('BOOK_ID não definido');
                return;
            }
            
            @guest
                console.log('Usuário não está logado');
                return;
            @endguest
            
            try {
                const response = await fetch(`/wishlist/check/${BOOK_ID}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                console.log('Estado inicial:', data);
                
                isInWishlist = data.inWishlist || false;
                
                const btn = document.querySelector('.btn.btn-secondary[onclick="addToWishlist()"]');
                const icon = btn?.querySelector('i');
                
                if (isInWishlist && btn) {
                    btn.classList.add('favorited');
                    if (icon) icon.style.color = '#ff4757';
                }
            } catch (error) {
                console.error('Erro ao verificar estado:', error);
            }
        });
        
        // Função Comprar Agora
        async function buyNow() {
            @guest
                showToast('Faça login para comprar', 'error');
                setTimeout(() => window.location.href = '/login', 1500);
                return;
            @endguest
            
            const quantityInput = document.getElementById('quantity');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
            const bookId = BOOK_ID;
            
            if (!bookId) {
                showToast('Erro ao identificar o livro', 'error');
                return;
            }
            
            // Desabilitar botão
            const buyBtn = document.querySelector('.buy-now-btn');
            if (buyBtn) {
                buyBtn.disabled = true;
                buyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
            }
            
            try {
                console.log('Enviando requisição para adicionar ao carrinho:', {
                    url: `/cart/add/${bookId}`,
                    bookId: bookId,
                    quantity: quantity
                });
                
                // Adicionar ao carrinho
                const response = await fetch(`/cart/add/${bookId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        book_id: bookId,
                        quantity: quantity
                    })
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                // Verificar se a resposta é JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    console.error('Resposta não é JSON:', contentType);
                    const text = await response.text();
                    console.error('Conteúdo da resposta:', text.substring(0, 500));
                    throw new Error('Erro no servidor. Por favor, tente novamente.');
                }
                
                const data = await response.json();
                console.log('Resposta do servidor:', data);
                
                if (response.ok && data.success) {
                    // Redirecionar para o carrinho
                    window.location.href = '/cart';
                } else {
                    throw new Error(data.message || 'Erro ao adicionar ao carrinho');
                }
            } catch (error) {
                console.error('Erro completo:', error);
                showToast(error.message || 'Erro ao processar compra', 'error');
                
                // Reabilitar botão
                if (buyBtn) {
                    buyBtn.disabled = false;
                    buyBtn.innerHTML = '<i class="fas fa-bolt"></i> Comprar Agora';
                }
            }
        }
        
        // CSS para animações
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection


