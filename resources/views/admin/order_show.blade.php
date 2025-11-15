@extends('layouts.app')

@section('title', 'Detalhes do Pedido #' . $order->id)

@section('content')
<style>
/* Estilos para detalhes do pedido */
.order-detail-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.order-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Header */
.order-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.order-id {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

/* Status atual */
.current-status {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
    margin-bottom: 1.5rem;
}

.status-label {
    font-size: 0.9rem;
    color: #64748b;
    font-weight: 600;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 700;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-processing { background: #dbeafe; color: #1e40af; }
.status-shipped { background: #e0e7ff; color: #3730a3; }
.status-delivered { background: #d1fae5; color: #065f46; }
.status-cancelled { background: #fee2e2; color: #991b1b; }

/* Alterar status */
.status-update-form {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.status-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9rem;
    cursor: pointer;
    flex: 1;
    max-width: 300px;
}

/* Grid de informações */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.info-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-label {
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.info-value {
    color: #1e293b;
    font-weight: 600;
}

/* Itens do pedido */
.order-items-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.items-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.item-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
    margin-bottom: 1rem;
    transition: all 0.3s;
}

.item-row:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}

.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.item-details {
    flex: 1;
}

.item-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.item-author {
    color: #64748b;
    font-size: 0.9rem;
}

.item-quantity {
    color: #64748b;
    font-weight: 600;
}

.item-price {
    font-size: 1.25rem;
    font-weight: 800;
    color: #10b981;
    text-align: right;
}

/* Resumo financeiro */
.financial-summary {
    background: #f8fafc;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.summary-row:last-child {
    border-bottom: none;
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 2px solid #e5e7eb;
}

.summary-label {
    color: #64748b;
    font-weight: 600;
}

.summary-value {
    font-weight: 700;
    color: #1e293b;
}

.summary-row:last-child .summary-label,
.summary-row:last-child .summary-value {
    font-size: 1.25rem;
    color: #10b981;
}

/* Timeline */
.timeline-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.625rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #667eea;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #667eea33;
}

.timeline-date {
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 600;
}

.timeline-content {
    color: #1e293b;
    font-weight: 600;
    margin-top: 0.25rem;
}

/* Botões */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    text-decoration: none;
    color: white;
}

.btn-secondary {
    background: white;
    color: #64748b;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    border-color: #667eea;
    color: #667eea;
    text-decoration: none;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    color: white;
}

/* Responsividade */
@media (max-width: 768px) {
    .order-detail-container {
        padding: 0 1rem;
    }
    
    .header-top {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .item-row {
        flex-direction: column;
        text-align: center;
    }
    
    .item-price {
        text-align: center;
    }
}
</style>

<div class="order-detail-wrapper">
    <div class="order-detail-container">
        <!-- Header -->
        <div class="order-header">
            <div class="header-top">
                <h1 class="order-id">
                    <i class="fas fa-shopping-bag"></i>
                    Pedido #{{ $order->id }}
                </h1>
                <div class="header-actions">
                    <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Status Atual -->
            <div class="current-status">
                <span class="status-label">Status Atual:</span>
                <span class="status-badge status-{{ $order->status }}">
                    @if($order->status === 'pending')
                        <i class="fas fa-clock"></i> Pendente
                    @elseif($order->status === 'processing')
                        <i class="fas fa-cog fa-spin"></i> Processando
                    @elseif($order->status === 'shipped')
                        <i class="fas fa-shipping-fast"></i> Enviado
                    @elseif($order->status === 'delivered')
                        <i class="fas fa-check-circle"></i> Entregue
                    @elseif($order->status === 'cancelled')
                        <i class="fas fa-times-circle"></i> Cancelado
                    @endif
                </span>
            </div>

            <!-- Formulário de Atualização de Status -->
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="status-update-form">
                @csrf
                @method('PUT')
                <span class="status-label">Alterar Status:</span>
                <select name="status" class="status-select" required>
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processando</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    Atualizar Status
                </button>
            </form>
        </div>

        <!-- Grid de Informações -->
        <div class="info-grid">
            <!-- Informações do Cliente -->
            <div class="info-card">
                <h2 class="card-title">
                    <i class="fas fa-user"></i>
                    Cliente
                </h2>
                <div class="info-item">
                    <div class="info-label">Nome</div>
                    <div class="info-value">{{ $order->user->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $order->user->email ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Telefone</div>
                    <div class="info-value">{{ $order->user->phone ?? 'Não informado' }}</div>
                </div>
            </div>

            <!-- Endereço de Entrega -->
            <div class="info-card">
                <h2 class="card-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Endereço de Entrega
                </h2>
                @if(is_array($order->shipping_address))
                    <div class="info-item">
                        <div class="info-label">Endereço</div>
                        <div class="info-value">
                            {{ $order->shipping_address['street'] ?? '' }}, 
                            {{ $order->shipping_address['number'] ?? '' }}
                            @if(isset($order->shipping_address['complement']))
                                - {{ $order->shipping_address['complement'] }}
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Bairro</div>
                        <div class="info-value">{{ $order->shipping_address['neighborhood'] ?? 'Não informado' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Cidade</div>
                        <div class="info-value">{{ $order->shipping_address['city'] ?? 'Não informado' }} - {{ $order->shipping_address['state'] ?? '' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CEP</div>
                        <div class="info-value">{{ $order->shipping_address['zip'] ?? 'Não informado' }}</div>
                    </div>
                @else
                    <div class="info-item">
                        <div class="info-label">Endereço</div>
                        <div class="info-value">{{ $order->shipping_city ?? 'Não informado' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CEP</div>
                        <div class="info-value">{{ $order->shipping_zip ?? 'Não informado' }}</div>
                    </div>
                @endif
            </div>

            <!-- Informações do Pedido -->
            <div class="info-card">
                <h2 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Informações
                </h2>
                <div class="info-item">
                    <div class="info-label">Data do Pedido</div>
                    <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total de Itens</div>
                    <div class="info-value">{{ $order->orderItems->count() }} item(ns)</div>
                </div>
                @if($order->shipped_at)
                <div class="info-item">
                    <div class="info-label">Data de Envio</div>
                    <div class="info-value">{{ $order->shipped_at->format('d/m/Y H:i') }}</div>
                </div>
                @endif
                @if($order->delivered_at)
                <div class="info-item">
                    <div class="info-label">Data de Entrega</div>
                    <div class="info-value">{{ $order->delivered_at->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Itens do Pedido -->
        <div class="order-items-card">
            <h2 class="card-title">
                <i class="fas fa-box-open"></i>
                Itens do Pedido
            </h2>
            <ul class="items-list">
                @foreach($order->orderItems as $item)
                <li class="item-row">
                    @php
                        // Processar imagens do livro
                        $bookImages = [];
                        if (isset($item->book->images)) {
                            if (is_string($item->book->images)) {
                                $decoded = json_decode($item->book->images, true);
                                $bookImages = is_array($decoded) ? $decoded : [$item->book->images];
                            } elseif (is_array($item->book->images)) {
                                $bookImages = $item->book->images;
                            }
                        }
                        $firstImage = !empty($bookImages) ? $bookImages[0] : null;
                        
                        // Processar URL da imagem
                        if ($firstImage) {
                            $firstImage = preg_replace('#^storage/#', '', trim($firstImage));
                            $imageUrl = (str_starts_with($firstImage, 'http') || str_starts_with($firstImage, 'https'))
                                ? $firstImage
                                : asset('storage/' . $firstImage);
                        } else {
                            $imageUrl = 'https://via.placeholder.com/80x120?text=Sem+Capa';
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $item->book->name }}" 
                         class="item-image"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/80x120?text=Sem+Capa';">
                    <div class="item-details">
                        <div class="item-title">{{ $item->book->name }}</div>
                        <div class="item-author">{{ $item->book->author }}</div>
                        <div class="item-quantity">Quantidade: {{ $item->quantity }}</div>
                    </div>
                    <div class="item-price">
                        R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                    </div>
                </li>
                @endforeach
            </ul>

            <!-- Resumo Financeiro -->
            <div class="financial-summary">
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">R$ {{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity), 2, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="summary-row">
                    <span class="summary-label">Desconto</span>
                    <span class="summary-value" style="color: #ef4444;">- R$ {{ number_format($order->discount_amount, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-row">
                    <span class="summary-label">TOTAL</span>
                    <span class="summary-value">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="timeline-card">
            <h2 class="card-title">
                <i class="fas fa-history"></i>
                Histórico do Pedido
            </h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="timeline-content">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        Pedido criado
                    </div>
                </div>
                @if($order->status !== 'pending')
                <div class="timeline-item">
                    <div class="timeline-date">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                    <div class="timeline-content">
                        <i class="fas fa-cog" style="color: #3b82f6;"></i>
                        Status alterado para: {{ ucfirst($order->status) }}
                    </div>
                </div>
                @endif
                @if($order->shipped_at)
                <div class="timeline-item">
                    <div class="timeline-date">{{ $order->shipped_at->format('d/m/Y H:i') }}</div>
                    <div class="timeline-content">
                        <i class="fas fa-shipping-fast" style="color: #8b5cf6;"></i>
                        Pedido enviado
                    </div>
                </div>
                @endif
                @if($order->delivered_at)
                <div class="timeline-item">
                    <div class="timeline-date">{{ $order->delivered_at->format('d/m/Y H:i') }}</div>
                    <div class="timeline-content">
                        <i class="fas fa-check-double" style="color: #10b981;"></i>
                        Pedido entregue
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif

@endsection
