@extends('layouts.app')

@section('content')
    @vite('resources/css/cart.css')
    @include('components.nav_bar')
    
    <!-- Teste para verificar se o novo CSS está sendo carregado -->
    <style>
        .cart-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 8rem 0 4rem !important;
            position: relative !important;
            overflow: hidden !important;
            margin-top: 80px !important;
        }
        
        .cart-hero-content {
            text-align: center !important;
            position: relative !important;
            z-index: 2 !important;
        }
        
        .cart-hero-title {
            font-family: 'Playfair Display', serif !important;
            font-size: 3.5rem !important;
            font-weight: 700 !important;
            margin-bottom: 1rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 1rem !important;
            color: white !important;
        }
        
        .container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 2rem !important;
        }
        
        /* Ocultar elementos antigos */
        .cart-container,
        .cart-title,
        .cart-table-wrapper,
        .cart-table {
            display: none !important;
        }
    </style>
    
    <div class="cart-hero">
        <div class="container">
            <div class="cart-hero-content">
                <h1 class="cart-hero-title">
                    <i class="fas fa-shopping-cart"></i>
                    Meu Carrinho
                </h1>
                <p class="cart-hero-subtitle">Finalize sua compra e desfrute dos seus livros preferidos</p>
            </div>
        </div>
    </div>

    <div class="cart-main">
        <div class="container">
            @php
                $cartCoupon = session('cart.coupon', null);
                $total = 0;
            @endphp
            
            @if(count($books) > 0)
                <div class="cart-layout">
                    <div class="cart-items-section">
                        <div class="cart-items-header">
                            <h2><i class="fas fa-list"></i> Itens do Carrinho</h2>
                            <span class="items-count">{{ count($books) }} {{ count($books) > 1 ? 'itens' : 'item' }}</span>
                        </div>
                        
                        <div class="cart-items-list">
                            @foreach($books as $book)
                                @php
                                    $images = is_array($book['images']) ? $book['images'] : json_decode($book['images'], true);
                                    $subtotal = $book['price'] * $book['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <div class="cart-item-card">
                                    <div class="cart-item-image">
                                        @if(is_array($images) && !empty($images))
                                            <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book['name'] }}">
                                        @elseif(!is_array($images) && !empty($images))
                                            <img src="{{ asset('storage/' . $images) }}" alt="{{ $book['name'] }}">
                                        @else
                                            <div class="placeholder-image">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="cart-item-details">
                                        <h3 class="cart-item-title">{{ $book['name'] }}</h3>
                                        <p class="cart-item-author">
                                            <i class="fas fa-user-edit"></i>
                                            {{ $book['author'] }}
                                        </p>
                                        <div class="cart-item-info">
                                            <div class="quantity-info">
                                                <i class="fas fa-cube"></i>
                                                <span>Quantidade: {{ $book['quantity'] }}</span>
                                            </div>
                                            <div class="price-info">
                                                <span class="unit-price">R$ {{ number_format($book['price'], 2, ',', '.') }}/un</span>
                                                <span class="subtotal-price">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="cart-item-actions">
                                        <form action="{{ route('cart.remove', $book['id']) }}" method="POST">
                                            @csrf
                                            <button class="remove-btn" type="submit" title="Remover item">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="cart-summary-section">
                        <div class="cart-summary-card">
                            <div class="summary-header">
                                <h3><i class="fas fa-calculator"></i> Resumo do Pedido</h3>
                            </div>
                            
                            <div class="summary-content">
                                @if($cartCoupon)
                                    <div class="applied-coupon">
                                        <div class="coupon-info">
                                            <i class="fas fa-tag"></i>
                                            <div class="coupon-details">
                                                <span class="coupon-code">{{ $cartCoupon['code'] }}</span>
                                                <span class="coupon-discount">
                                                    {{ $cartCoupon['type'] == 'percent' ? $cartCoupon['discount'] . '% OFF' : 'R$ ' . number_format($cartCoupon['discount'], 2, ',', '.') . ' OFF' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="summary-lines">
                                    <div class="summary-line">
                                        <span>Subtotal ({{ count($books) }} {{ count($books) > 1 ? 'itens' : 'item' }})</span>
                                        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                                    </div>
                                    
                                    @if($cartCoupon)
                                        @php
                                            $discount = $cartCoupon['type'] == 'percent' ? ($total * ($cartCoupon['discount'] / 100)) : $cartCoupon['discount'];
                                            $discountedTotal = max($total - $discount, 0);
                                        @endphp
                                        <div class="summary-line discount">
                                            <span><i class="fas fa-minus-circle"></i> Desconto</span>
                                            <span>-R$ {{ number_format($discount, 2, ',', '.') }}</span>
                                        </div>
                                        <div class="summary-line total">
                                            <span>Total</span>
                                            <span>R$ {{ number_format($discountedTotal, 2, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <div class="summary-line total">
                                            <span>Total</span>
                                            <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="coupon-section">
                                    <h4><i class="fas fa-percentage"></i> Cupom de Desconto</h4>
                                    <form class="coupon-form" action="{{ route('cart.applyCoupon') }}" method="POST">
                                        @csrf
                                        <div class="coupon-input-group">
                                            <input type="text" id="coupon_code" name="coupon_code" 
                                                   placeholder="Digite o código do cupom" required>
                                            <button type="submit">
                                                <i class="fas fa-check"></i>
                                                Aplicar
                                            </button>
                                        </div>
                                    </form>
                                    
                                    @if(session('coupon_error'))
                                        <div class="alert alert-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ session('coupon_error') }}
                                        </div>
                                    @endif
                                    
                                    @if(session('coupon_success'))
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            {{ session('coupon_success') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="cart-actions-section">
                    <div class="primary-actions">
                        <a href="#" class="btn btn-primary btn-large">
                            <i class="fas fa-credit-card"></i>
                            Finalizar Compra
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" class="clear-cart-form">
                            @csrf
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Tem certeza que deseja esvaziar o carrinho?')">
                                <i class="fas fa-trash"></i>
                                Esvaziar Carrinho
                            </button>
                        </form>
                    </div>
                    
                    <div class="secondary-actions">
                        <a href="{{ route('books.index') }}" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Continuar Comprando
                        </a>
                        <a href="{{ route('index') }}" class="btn btn-outline">
                            <i class="fas fa-home"></i>
                            Voltar ao Início
                        </a>
                    </div>
                </div>
                
            @else
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2>Seu carrinho está vazio</h2>
                    <p>Que tal explorar nossa coleção e encontrar alguns livros incríveis?</p>
                    <div class="empty-cart-actions">
                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                            <i class="fas fa-book"></i>
                            Explorar Livros
                        </a>
                        <a href="{{ route('index') }}" class="btn btn-outline">
                            <i class="fas fa-home"></i>
                            Página Inicial
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    @include('components.footer')
@endsection
