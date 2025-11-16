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
                $cartCoupon = session('cart_coupon', null);
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
                                            <div class="quantity-control">
                                                <i class="fas fa-cube"></i>
                                                <span>Quantidade:</span>
                                                <div class="quantity-buttons">
                                                    <form action="{{ route('cart.updateQuantity', $book['id']) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action" value="decrease">
                                                        <button type="submit" class="qty-btn" {{ $book['quantity'] <= 1 ? 'disabled' : '' }}>
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </form>
                                                    <span class="qty-display">{{ $book['quantity'] }}</span>
                                                    <form action="{{ route('cart.updateQuantity', $book['id']) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="action" value="increase">
                                                        <button type="submit" class="qty-btn">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
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
                                <!-- Seção de Cupom -->
                                <div class="coupon-section" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e0e0e0;">
                                    @if($cartCoupon)
                                        <div class="applied-coupon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                                            <div class="coupon-info" style="display: flex; align-items: center; justify-content: space-between;">
                                                <div style="display: flex; align-items: center; gap: 0.75rem; color: white;">
                                                    <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                                                    <div class="coupon-details">
                                                        <span class="coupon-code" style="display: block; font-weight: 600; font-size: 1rem;">{{ $cartCoupon['code'] }}</span>
                                                        <span class="coupon-discount" style="display: block; font-size: 0.875rem; opacity: 0.9;">
                                                            {{ $cartCoupon['type'] == 'percent' ? $cartCoupon['discount'] . '% OFF' : 'R$ ' . number_format($cartCoupon['discount'], 2, ',', '.') . ' OFF' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <form action="{{ route('cart.removeCoupon') }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 0.5rem; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('cart.applyCoupon') }}" method="POST" style="margin-bottom: 0;">
                                            @csrf
                                            <div style="display: flex; gap: 0.5rem;">
                                                <div style="flex: 1; position: relative;">
                                                    <input 
                                                        type="text" 
                                                        name="coupon_code" 
                                                        placeholder="Digite o código do cupom (opcional)" 
                                                        style="width: 100%; padding: 0.75rem 1rem; padding-left: 2.5rem; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 0.875rem; transition: all 0.3s;"
                                                        onfocus="this.style.borderColor='#667eea'"
                                                        onblur="this.style.borderColor='#e0e0e0'">
                                                    <i class="fas fa-ticket-alt" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #999;"></i>
                                                </div>
                                                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; white-space: nowrap;">
                                                    <i class="fas fa-check"></i> Aplicar
                                                </button>
                                            </div>
                                        </form>
                                        
                                        @if(session('coupon_error'))
                                            <div style="margin-top: 0.75rem; padding: 0.75rem; background: #fee; border-left: 3px solid #f44; border-radius: 8px; color: #c33; font-size: 0.875rem;">
                                                <i class="fas fa-exclamation-circle"></i> {{ session('coupon_error') }}
                                            </div>
                                        @endif
                                        
                                        @if(session('coupon_success'))
                                            <div style="margin-top: 0.75rem; padding: 0.75rem; background: #efe; border-left: 3px solid #4a4; border-radius: 8px; color: #363; font-size: 0.875rem;">
                                                <i class="fas fa-check-circle"></i> {{ session('coupon_success') }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                
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
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="cart-actions-section">
                    <div class="primary-actions">
                        @auth
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-large">
                                <i class="fas fa-credit-card"></i>
                                Finalizar Compra
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-large">
                                <i class="fas fa-sign-in-alt"></i>
                                Fazer Login para Finalizar
                            </a>
                        @endauth
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
    
    <!-- Mensagens de alerta -->
    @if(session('success'))
        <div style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 20px; border-radius: 5px; z-index: 1000; max-width: 400px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle"></i>
                <div>
                    {{ session('success') }}
                    @if(session('order_created'))
                        <div style="margin-top: 5px;">
                            <a href="{{ route('orders.show', session('order_created')) }}" style="color: #fff; text-decoration: underline;">
                                Ver detalhes do pedido
                            </a>
                        </div>
                    @endif
                </div>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="position: fixed; top: 20px; right: 20px; background: #dc3545; color: white; padding: 15px 20px; border-radius: 5px; z-index: 1000; max-width: 400px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
            </div>
        </div>
    @endif
    
    <script>
        // Auto-ocultar alertas após 8 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('[style*="position: fixed"]');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            });
        }, 8000);
    </script>
    
    <style>
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-buttons {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.2rem;
        }

        .qty-btn {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #667eea;
            transition: all 0.2s ease;
            padding: 0;
        }

        .qty-btn:hover:not(:disabled) {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: scale(1.1);
        }

        .qty-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .qty-display {
            font-weight: 600;
            min-width: 30px;
            text-align: center;
            color: #495057;
        }
    </style>
    
    @include('components.footer')
@endsection

