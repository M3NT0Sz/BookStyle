@extends('layouts.app')

@section('title', 'Pedido #' . $order->order_number . ' - BookStyle')

@section('content')
<style>
/* Reset e base */
* {
    box-sizing: border-box;
}

/* Container principal */
.orders-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    padding: 2rem 0;
}

.orders-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.orders-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 1;
}

/* Header da página */
.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2.5rem;
    box-shadow: 
        0 10px 30px rgba(0, 0, 0, 0.1),
        0 1px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: slideDown 0.6s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.breadcrumb-link {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.breadcrumb-link:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    transform: translateX(-2px);
}

.page-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    line-height: 1.2;
}

.page-title-icon {
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.order-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.order-date {
    color: #64748b;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.status-pending {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

.status-processing {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
}

.status-shipped {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
}

.status-delivered {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

.status-cancelled {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

/* Cards principais */
.main-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 
        0 10px 30px rgba(0, 0, 0, 0.08),
        0 1px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.main-card:hover {
    transform: translateY(-4px);
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.12),
        0 1px 8px rgba(0, 0, 0, 0.06);
}

.card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    background: rgba(248, 250, 252, 0.8);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.card-body {
    padding: 2rem;
}

/* Itens do pedido */
.order-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: rgba(248, 250, 252, 0.8);
    border-radius: 16px;
    margin-bottom: 1rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.order-item:hover {
    border-color: rgba(102, 126, 234, 0.2);
    background: rgba(102, 126, 234, 0.05);
    transform: translateX(4px);
}

.item-image {
    width: 80px;
    height: 100px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-weight: 700;
    color: #1e293b;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.item-author {
    color: #64748b;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.item-quantity {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.item-price {
    text-align: right;
    flex-shrink: 0;
}

.price-main {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.price-sub {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Sidebar - Resumo */
.summary-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    position: sticky;
    top: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-weight: 500;
}

.summary-total {
    border-top: 2px solid rgba(255, 255, 255, 0.2);
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    text-align: center;
}

.total-amount {
    font-size: 2rem;
    font-weight: 900;
    margin-top: 0.5rem;
}

/* Botão de cancelar */
.btn-cancel {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.timeline-marker {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
    box-shadow: 0 0 0 4px white;
}

.timeline-content {
    background: rgba(248, 250, 252, 0.8);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    flex: 1;
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.timeline-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.timeline-date {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Endereços */
.address-card {
    background: rgba(248, 250, 252, 0.8);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.address-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.address-content {
    color: #64748b;
    line-height: 1.6;
}

/* Responsividade */
@media (max-width: 768px) {
    .orders-content {
        padding: 0 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .order-meta {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .item-price {
        text-align: center;
    }
    
    .summary-card {
        position: static;
        margin-top: 2rem;
    }
    
    .timeline {
        padding-left: 1rem;
    }
    
    .timeline::before {
        left: 0.5rem;
    }
    
    .timeline-marker {
        width: 2rem;
        height: 2rem;
    }
}

/* Animações de entrada */
.fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slide-in-left {
    animation: slideInLeft 0.6s ease-out forwards;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<div class="orders-container">
    <div class="orders-content">
        <!-- Header da página -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <a href="{{ route('orders.index') }}" class="breadcrumb-link">
                    <i class="fas fa-arrow-left"></i>
                    Voltar para Meus Pedidos
                </a>
            </div>
            
            <!-- Título e informações principais -->
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <h1 class="page-title">
                        <div class="page-title-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <div>Pedido #{{ $order->order_number }}</div>
                            <div class="order-meta">
                                <span class="status-badge
                                    {{ $order->status === 'delivered' ? 'status-delivered' : '' }}
                                    {{ $order->status === 'shipped' ? 'status-shipped' : '' }}
                                    {{ $order->status === 'processing' ? 'status-processing' : '' }}
                                    {{ $order->status === 'pending' ? 'status-pending' : '' }}
                                    {{ $order->status === 'cancelled' ? 'status-cancelled' : '' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $order->status_label }}
                                </span>
                                <span class="order-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                                </span>
                            </div>
                        </div>
                    </h1>
                </div>
                
                @if($order->canBeCancelled())
                <div class="flex-shrink-0">
                    <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                class="btn-cancel">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar Pedido
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna principal - Itens e detalhes -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Itens do pedido -->
                <div class="main-card fade-in">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-box-open text-blue-500"></i>
                            Itens do Pedido
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                            <div class="order-item slide-in-left" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                <div class="item-image">
                                    <i class="fas fa-book"></i>
                                </div>
                                
                                <div class="item-details">
                                    <h3 class="item-title">
                                        📚 {{ $item->book->title }}
                                    </h3>
                                    <p class="item-author">
                                        ✍️ {{ $item->book->author }}
                                    </p>
                                    <span class="item-quantity">
                                        <i class="fas fa-cube"></i>
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                
                                <div class="item-price">
                                    <div class="price-main">
                                        R$ {{ number_format($item->price, 2, ',', '.') }}
                                    </div>
                                    <div class="price-sub">
                                        Subtotal: R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Informações de pagamento -->
                <div class="main-card fade-in" style="animation-delay: 0.2s">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-credit-card text-green-500"></i>
                            Informações de Pagamento
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Método de Pagamento</label>
                                <div class="text-lg font-bold text-gray-900">{{ $order->payment_method }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Status do Pagamento</label>
                                <span class="status-badge {{ $order->payment_status === 'paid' ? 'status-delivered' : 'status-pending' }}">
                                    <i class="fas fa-{{ $order->payment_status === 'paid' ? 'check' : 'clock' }}"></i>
                                    {{ $order->payment_status === 'paid' ? 'Pago' : 'Pendente' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereços -->
                @if($order->shipping_address || $order->billing_address)
                <div class="main-card fade-in" style="animation-delay: 0.3s">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-map-marker-alt text-red-500"></i>
                            Endereços
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @if($order->shipping_address)
                            <div class="address-card">
                                <h3 class="address-title">
                                    <i class="fas fa-truck text-blue-500"></i>
                                    Endereço de Entrega
                                </h3>
                                <div class="address-content">
                                    @if(is_array($order->shipping_address))
                                        <div>{{ $order->shipping_address['street'] ?? '' }}</div>
                                        <div>{{ $order->shipping_address['city'] ?? '' }} - {{ $order->shipping_address['state'] ?? '' }}</div>
                                        <div>CEP: {{ $order->shipping_address['postal_code'] ?? '' }}</div>
                                    @else
                                        {{ $order->shipping_address }}
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                            <div class="address-card">
                                <h3 class="address-title">
                                    <i class="fas fa-credit-card text-green-500"></i>
                                    Endereço de Cobrança
                                </h3>
                                <div class="address-content">
                                    @if(is_array($order->billing_address))
                                        <div>{{ $order->billing_address['street'] ?? '' }}</div>
                                        <div>{{ $order->billing_address['city'] ?? '' }} - {{ $order->billing_address['state'] ?? '' }}</div>
                                        <div>CEP: {{ $order->billing_address['postal_code'] ?? '' }}</div>
                                    @else
                                        {{ $order->billing_address }}
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Timeline do pedido -->
                <div class="main-card fade-in" style="animation-delay: 0.4s">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-history text-purple-500"></i>
                            Histórico do Pedido
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Pedido criado -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-green-500">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">✅ Pedido Confirmado</div>
                                    <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>

                            <!-- Processando -->
                            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-blue-500">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">⚙️ Pedido em Processamento</div>
                                    <div class="timeline-date">Preparando seus itens</div>
                                </div>
                            </div>
                            @endif

                            <!-- Enviado -->
                            @if(in_array($order->status, ['shipped', 'delivered']) && $order->shipped_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-yellow-500">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">🚚 Pedido Enviado</div>
                                    <div class="timeline-date">{{ $order->shipped_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            @endif

                            <!-- Entregue -->
                            @if($order->status === 'delivered' && $order->delivered_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-green-600">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">🏠 Pedido Entregue</div>
                                    <div class="timeline-date">{{ $order->delivered_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            @endif

                            <!-- Cancelado -->
                            @if($order->status === 'cancelled')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-red-500">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">❌ Pedido Cancelado</div>
                                    <div class="timeline-date">Cancelamento processado</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Resumo -->
            <div class="lg:col-span-1">
                <div class="summary-card fade-in" style="animation-delay: 0.1s">
                    <h2 class="summary-title">
                        <i class="fas fa-calculator"></i>
                        Resumo Financeiro
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="summary-row">
                            <span>Subtotal ({{ $order->orderItems->sum('quantity') }} itens)</span>
                            <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Frete</span>
                            <span class="text-green-300 font-bold">Grátis</span>
                        </div>
                        <div class="summary-row">
                            <span>Desconto</span>
                            <span>R$ 0,00</span>
                        </div>
                    </div>
                    
                    <div class="summary-total">
                        <div class="text-lg font-semibold mb-2">💰 Total Pago</div>
                        <div class="total-amount">R$ {{ number_format($order->total, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertas modernos -->
@if(session('success'))
<div class="fixed bottom-6 right-6 z-50">
    <div class="bg-green-500 text-white px-6 py-4 rounded-2xl shadow-lg backdrop-filter backdrop-blur-sm border border-white/20 transform transition-all duration-500">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-check text-sm"></i>
            </div>
            <div>
                <div class="font-bold">Sucesso!</div>
                <div class="text-sm opacity-90">{{ session('success') }}</div>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed bottom-6 right-6 z-50">
    <div class="bg-red-500 text-white px-6 py-4 rounded-2xl shadow-lg backdrop-filter backdrop-blur-sm border border-white/20 transform transition-all duration-500">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-sm"></i>
            </div>
            <div>
                <div class="font-bold">Erro!</div>
                <div class="text-sm opacity-90">{{ session('error') }}</div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.fixed.bottom-6.right-6');
        alerts.forEach(alert => {
            alert.style.transform = 'translateX(100%)';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Intersection Observer para animações on-scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observar elementos para animação
    const animatedElements = document.querySelectorAll('.main-card, .summary-card, .order-item, .timeline-item');
    animatedElements.forEach(element => {
        observer.observe(element);
    });

    // Smooth scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Adicionar pequeno delay para animações
    const cards = document.querySelectorAll('.fade-in, .slide-in-left');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection