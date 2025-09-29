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

                                {{-- ========== SEÇÃO DE CUPONS INTELIGENTES ========== --}}
                                @auth
                                    {{-- Notificação de novo cupom --}}
                                    @if(session('new_coupon'))
                                        <div class="new-coupon-notification">
                                            <div class="new-coupon-content">
                                                <i class="fas fa-gift"></i>
                                                <div class="new-coupon-text">
                                                    <h4>🎉 Novo cupom disponível!</h4>
                                                    <p>{{ session('new_coupon.message') }}</p>
                                                </div>
                                                <button onclick="this.parentElement.style.display='none'" class="close-notification">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Cupons Sugeridos --}}
                                    @if(isset($suggestedCoupons) && count($suggestedCoupons) > 0)
                                        <div class="smart-coupons-section">
                                            <h4><i class="fas fa-magic"></i> Cupons Recomendados para Você</h4>
                                            <div class="smart-coupons-grid">
                                                @foreach($suggestedCoupons as $suggestion)
                                                    @php $coupon = $suggestion['coupon']; @endphp
                                                    <div class="smart-coupon-card">
                                                        <div class="smart-coupon-header">
                                                            <span class="coupon-type-badge">
                                                                @switch($coupon['trigger_type'])
                                                                    @case('first_purchase')
                                                                        <i class="fas fa-star"></i> Boas-vindas
                                                                        @break
                                                                    @case('birthday')
                                                                        <i class="fas fa-birthday-cake"></i> Aniversário
                                                                        @break
                                                                    @case('loyalty')
                                                                        <i class="fas fa-trophy"></i> Fidelidade
                                                                        @break
                                                                    @case('high_value_cart')
                                                                        <i class="fas fa-gem"></i> VIP
                                                                        @break
                                                                    @case('genre_based')
                                                                        <i class="fas fa-book"></i> Gênero
                                                                        @break
                                                                    @default
                                                                        <i class="fas fa-gift"></i> Especial
                                                                @endswitch
                                                            </span>
                                                            <div class="coupon-discount-display">
                                                                {{ $coupon['type'] == 'percent' ? $coupon['discount'] . '% OFF' : 'R$ ' . number_format($coupon['discount'], 2, ',', '.') . ' OFF' }}
                                                            </div>
                                                        </div>
                                                        <div class="smart-coupon-body">
                                                            <div class="coupon-code-display">{{ $coupon['code'] }}</div>
                                                            <p class="coupon-message">{{ $suggestion['message'] }}</p>
                                                            @if($coupon['expires_at'])
                                                                <div class="coupon-expires">
                                                                    <i class="fas fa-clock"></i>
                                                                    Válido até {{ date('d/m/Y', strtotime($coupon['expires_at'])) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="smart-coupon-actions">
                                                            <button class="apply-smart-coupon" onclick="applySuggestedCoupon('{{ $coupon['code'] }}')">
                                                                <i class="fas fa-check"></i>
                                                                Aplicar Cupom
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endauth
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

        // ========== FUNCIONALIDADES DE CUPONS INTELIGENTES ==========
        function applySuggestedCoupon(couponCode) {
            document.getElementById('coupon_code').value = couponCode;
            document.querySelector('.coupon-form').submit();
        }

        // Auto-ocultar notificação de novo cupom após 10 segundos
        setTimeout(function() {
            const notification = document.querySelector('.new-coupon-notification');
            if (notification) {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 500);
            }
        }, 10000);
    </script>

    {{-- ========== ESTILOS PARA CUPONS INTELIGENTES ========== --}}
    <style>
        /* Notificação de novo cupom */
        .new-coupon-notification {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            animation: slideInFromTop 0.5s ease-out;
            position: relative;
        }

        .new-coupon-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .new-coupon-content i {
            font-size: 2rem;
            color: #fff;
        }

        .new-coupon-text h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .new-coupon-text p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .close-notification {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            padding: 0.5rem;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .close-notification:hover {
            opacity: 1;
        }

        /* Seção de cupons inteligentes */
        .smart-coupons-section {
            margin-top: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            border: 1px solid #dee2e6;
        }

        .smart-coupons-section h4 {
            color: #495057;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .smart-coupons-section h4 i {
            color: #007bff;
        }

        .smart-coupons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }

        /* Cards de cupons sugeridos */
        .smart-coupon-card {
            background: white;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }

        .smart-coupon-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .smart-coupon-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .coupon-type-badge {
            font-size: 0.85rem;
            font-weight: 600;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .coupon-discount-display {
            font-size: 1.4rem;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .smart-coupon-body {
            padding: 1.5rem;
        }

        .coupon-code-display {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 1rem;
        }

        .coupon-message {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.4;
            margin-bottom: 1rem;
        }

        .coupon-expires {
            font-size: 0.85rem;
            color: #dc3545;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 500;
        }

        .smart-coupon-actions {
            padding: 0 1.5rem 1.5rem 1.5rem;
        }

        .apply-smart-coupon {
            width: 100%;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .apply-smart-coupon:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            transform: scale(1.02);
        }

        /* Animação */
        @keyframes slideInFromTop {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .smart-coupons-grid {
                grid-template-columns: 1fr;
            }
            
            .new-coupon-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    
    @include('components.footer')
@endsection
