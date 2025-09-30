@extends('layouts.app')

@section('title', 'Pedido #' . $order->order_number . ' - BookStyle')

@section('content')
<style>
/* Estilos específicos para a página de detalhes do pedido */
.orders-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.orders-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.order-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.page-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #718096;
    font-size: 1.1rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: #fed7d7;
    color: #c53030;
}

.status-processing {
    background: #feebc8;
    color: #dd6b20;
}

.status-shipped {
    background: #bee3f8;
    color: #2b6cb0;
}

.status-delivered {
    background: #c6f6d5;
    color: #2f855a;
}

.status-cancelled {
    background: #fed7d7;
    color: #c53030;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #fafafa;
    transition: background 0.3s ease;
}

.order-item:hover {
    background: #f0f0f0;
}

.item-image {
    width: 80px;
    height: 100px;
    background: #e2e8f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.item-details {
    flex: 1;
}

.item-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.item-author {
    color: #718096;
    margin-bottom: 0.25rem;
}

.item-quantity {
    color: #4a5568;
    font-size: 0.9rem;
}

.item-price {
    text-align: right;
    flex-shrink: 0;
}

.price-main {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
}

.price-sub {
    color: #718096;
    font-size: 0.9rem;
}

.order-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 2rem;
    position: sticky;
    top: 2rem;
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
}

.summary-total {
    border-top: 2px solid rgba(255, 255, 255, 0.3);
    padding-top: 1rem;
    margin-top: 1rem;
    font-size: 1.25rem;
    font-weight: 700;
}

.btn-danger {
    background: #e53e3e;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: #c53030;
    transform: translateY(-1px);
}

.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    margin-bottom: 2rem;
}

.timeline-marker {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.timeline-content {
    flex: 1;
    padding-top: 0.5rem;
}

.timeline-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.timeline-date {
    color: #718096;
    font-size: 0.9rem;
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
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

@media (max-width: 768px) {
    .orders-content {
        padding: 0 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .item-image {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .item-price {
        text-align: center;
        margin-top: 1rem;
    }
    
    .order-summary {
        position: static;
        margin-top: 2rem;
    }
}
</style>

<div class="orders-container">
    <div class="orders-content">
        <!-- Header com breadcrumb -->
        <div class="page-header">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('orders.index') }}" class="hover:text-purple-600 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar para Meus Pedidos
                </a>
            </div>
            
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-receipt mr-3 text-purple-600"></i>
                        Pedido #{{ $order->order_number }}
                    </h1>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="status-badge
                            {{ $order->status === 'delivered' ? 'status-delivered' : '' }}
                            {{ $order->status === 'shipped' ? 'status-shipped' : '' }}
                            {{ $order->status === 'processing' ? 'status-processing' : '' }}
                            {{ $order->status === 'pending' ? 'status-pending' : '' }}
                            {{ $order->status === 'cancelled' ? 'status-cancelled' : '' }}">
                            <i class="fas fa-circle mr-2 text-xs"></i>
                            {{ $order->status_label }}
                        </span>
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            Pedido realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                        </span>
                    </div>
                </div>
                
                @if($order->canBeCancelled())
                    <div class="mt-4 lg:mt-0">
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                    class="btn-danger">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar Pedido
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Itens do pedido -->
            <div class="lg:col-span-2">
                <div class="order-card">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-box-open mr-3 text-blue-600"></i>
                            Itens do Pedido
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <i class="fas fa-book text-gray-400 text-2xl"></i>
                                </div>
                                
                                <div class="item-details">
                                    <h3 class="item-title">
                                        📚 {{ $item->book->title }}
                                    </h3>
                                    <p class="item-author">
                                        ✍️ {{ $item->book->author }}
                                    </p>
                                    <p class="item-quantity">
                                        📦 Quantidade: {{ $item->quantity }}
                                    </p>
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
            </div>

            <!-- Resumo e informações -->
            <div class="space-y-6">
                <!-- Resumo do pedido -->
                <div class="order-summary">
                    <h2 class="summary-title">
                        <i class="fas fa-calculator mr-2"></i>
                        Resumo Financeiro
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="summary-row">
                            <span>Subtotal ({{ $order->orderItems->sum('quantity') }} itens):</span>
                            <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Frete:</span>
                            <span>Grátis</span>
                        </div>
                        <div class="summary-total">
                            <div class="flex justify-between items-center">
                                <span>💰 Total Pago:</span>
                                <span class="text-2xl">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações de pagamento -->
                <div class="order-card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-credit-card mr-3 text-green-600"></i>
                            Pagamento
                        </h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 flex items-center">
                                    <i class="fas fa-money-bill mr-2"></i>
                                    Método:
                                </span>
                                <span class="text-gray-900 font-semibold">{{ $order->payment_method }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Status:
                                </span>
                                <span class="status-badge {{ $order->payment_status === 'paid' ? 'status-delivered' : 'status-pending' }}">
                                    {{ $order->payment_status === 'paid' ? '✅ Pago' : '⏳ Pendente' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Itens do pedido -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                        
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                <!-- Aqui você pode adicionar uma imagem do livro se tiver -->
                                <div class="flex-shrink-0 w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item->book->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->book->author }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Quantidade: {{ $item->quantity }}
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($item->price, 2, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Subtotal: R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Resumo e informações -->
                <div class="space-y-6">
                    <!-- Resumo do pedido -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $order->orderItems->sum('quantity') }} itens):</span>
                                <span class="text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                            </div>
                            <!-- Aqui você pode adicionar frete, desconto, etc. -->
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between font-semibold">
                                    <span>Total:</span>
                                    <span class="text-lg">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de pagamento -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pagamento</h2>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Método:</span>
                                <span class="text-gray-900">{{ $order->payment_method }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $order->payment_status === 'paid' ? 'Pago' : 'Pendente' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Endereços -->
                    @if($order->shipping_address || $order->billing_address)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Endereços</h2>
                        
                <!-- Endereços -->
                @if($order->shipping_address || $order->billing_address)
                <div class="order-card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-red-600"></i>
                            Endereços
                        </h2>
                        
                        @if($order->shipping_address)
                        <div class="mb-6">
                            <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-truck mr-2 text-blue-600"></i>
                                Endereço de Entrega
                            </h3>
                            <div class="text-sm text-gray-600 bg-blue-50 p-4 rounded-lg">
                                @if(is_array($order->shipping_address))
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-road mr-2"></i>
                                        {{ $order->shipping_address['street'] ?? '' }}
                                    </div>
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-city mr-2"></i>
                                        {{ $order->shipping_address['city'] ?? '' }} - {{ $order->shipping_address['state'] ?? '' }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-mail-bulk mr-2"></i>
                                        CEP: {{ $order->shipping_address['postal_code'] ?? '' }}
                                    </div>
                                @else
                                    {{ $order->shipping_address }}
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                        <div>
                            <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-green-600"></i>
                                Endereço de Cobrança
                            </h3>
                            <div class="text-sm text-gray-600 bg-green-50 p-4 rounded-lg">
                                @if(is_array($order->billing_address))
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-road mr-2"></i>
                                        {{ $order->billing_address['street'] ?? '' }}
                                    </div>
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-city mr-2"></i>
                                        {{ $order->billing_address['city'] ?? '' }} - {{ $order->billing_address['state'] ?? '' }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-mail-bulk mr-2"></i>
                                        CEP: {{ $order->billing_address['postal_code'] ?? '' }}
                                    </div>
                                @else
                                    {{ $order->billing_address }}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Timeline do pedido -->
                <div class="order-card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-history mr-3 text-purple-600"></i>
                            Histórico do Pedido
                        </h2>
                        
                        <div class="timeline">
                            <!-- Pedido criado -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-green-500 text-white">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">✅ Pedido Confirmado</div>
                                    <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>

                            <!-- Processando -->
                            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-blue-500 text-white">
                                    <i class="fas fa-cog text-sm"></i>
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
                                <div class="timeline-marker bg-yellow-500 text-white">
                                    <i class="fas fa-truck text-sm"></i>
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
                                <div class="timeline-marker bg-green-600 text-white">
                                    <i class="fas fa-home text-sm"></i>
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
                                <div class="timeline-marker bg-red-500 text-white">
                                    <i class="fas fa-times text-sm"></i>
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
        </div>
    </div>
</div>

<!-- Alertas modernos -->
@if(session('success'))
<div class="fixed bottom-6 right-6 bg-gradient-to-r from-green-400 to-green-600 text-white px-6 py-4 rounded-xl shadow-xl z-50 transform transition-all duration-500">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-3 text-xl"></i>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed bottom-6 right-6 bg-gradient-to-r from-red-400 to-red-600 text-white px-6 py-4 rounded-xl shadow-xl z-50 transform transition-all duration-500">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
        <span class="font-semibold">{{ session('error') }}</span>
    </div>
</div>
@endif

<script>
// Auto-hide alerts with smooth animation
setTimeout(() => {
    const alerts = document.querySelectorAll('.fixed.bottom-6.right-6');
    alerts.forEach(alert => {
        alert.style.transform = 'translateX(100%)';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Add fade-in animation to page content
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.order-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
});
</script>
@endsection