@extends('layouts.app')

@section('title', 'Meus Pedidos - BookStyle')

@section('content')
<style>
/* Layout moderno para meus pedidos */
* {
    box-sizing: border-box;
}

.orders-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

.orders-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
    pointer-events: none;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
    z-index: 1;
}

/* Header estilizado */
.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    text-align: center;
}

.page-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.page-subtitle {
    font-size: 1.25rem;
    color: #64748b;
    margin-bottom: 2rem;
}

/* Controles de filtro e busca */
.controls-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.controls-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.control-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.control-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-input {
    padding: 0.875rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.btn-filter {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

/* Estatísticas rápidas */
.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.25rem;
    color: white;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Lista de pedidos */
.orders-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.order-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.order-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.order-info {
    flex: 1;
    min-width: 250px;
}

.order-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-date {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-pending {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    color: white;
}

.status-processing {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    color: white;
}

.status-shipped {
    background: linear-gradient(45deg, #8b5cf6, #7c3aed);
    color: white;
}

.status-delivered {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
}

.status-cancelled {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
}

.order-summary {
    text-align: right;
}

.order-total {
    font-size: 2rem;
    font-weight: 800;
    color: #059669;
    margin-bottom: 0.5rem;
}

.order-payment {
    color: #64748b;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
}

.order-items {
    margin-bottom: 2rem;
}

.order-items-header {
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.item-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.item-card:hover {
    background: #f1f5f9;
    border-color: #667eea;
}

.item-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.item-details {
    color: #64748b;
    font-size: 0.75rem;
    display: flex;
    justify-content: space-between;
}

.order-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: white;
    color: #64748b;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    border-color: #667eea;
    color: #667eea;
}

.btn-danger {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(239, 68, 68, 0.4);
}

/* Estado vazio */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.empty-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-description {
    color: #64748b;
    margin-bottom: 2rem;
}

/* Timeline de status */
.order-timeline {
    margin: 1.5rem 0;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.timeline-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 0;
}

.timeline-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    color: white;
    font-weight: 600;
}

.timeline-content {
    flex: 1;
}

.timeline-title {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
}

.timeline-date {
    color: #64748b;
    font-size: 0.75rem;
}

/* Responsividade */
@media (max-width: 1024px) {
    .controls-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .order-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .order-summary {
        text-align: left;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
    }
    
    .controls-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-section {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .order-card {
        padding: 1.5rem;
    }
    
    .order-actions {
        justify-content: stretch;
    }
    
    .btn {
        flex: 1;
        justify-content: center;
    }
    
    .items-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .stats-section {
        grid-template-columns: 1fr;
    }
    
    .order-actions {
        flex-direction: column;
    }
}

/* Animações */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}

/* Loading state */
.loading-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.loading-spinner {
    border: 4px solid #f3f4f6;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="orders-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="page-header animate-fade-in">
            <h1 class="page-title">
                <i class="fas fa-shopping-bag"></i>
                Meus Pedidos
            </h1>
            <p class="page-subtitle">Acompanhe todos os seus pedidos com facilidade e praticidade</p>
        </div>

        <!-- Controles de filtro -->
        <div class="controls-section animate-fade-in">
            <form method="GET" action="{{ route('orders.index') }}" id="filter-form">
                <div class="controls-grid">
                    <div class="control-group">
                        <label class="control-label">
                            <i class="fas fa-filter"></i>
                            Status do Pedido
                        </label>
                        <select name="status" class="form-input" onchange="document.getElementById('filter-form').submit()">
                            <option value="">📋 Todos os Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                ⏳ Pendente
                            </option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>
                                ⚙️ Processando
                            </option>
                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>
                                🚚 Enviado
                            </option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                                ✅ Entregue
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                ❌ Cancelado
                            </option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">
                            <i class="fas fa-calendar"></i>
                            Data Inicial
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">
                            <i class="fas fa-calendar"></i>
                            Data Final
                        </label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
                    </div>
                    
                    <div class="control-group">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i>
                            Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Estatísticas rápidas -->
        <div class="stats-section animate-fade-in">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ $orders->count() }}</div>
                <div class="stat-label">Total de Pedidos</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $orders->where('status', 'delivered')->count() }}</div>
                <div class="stat-label">Pedidos Entregues</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #3b82f6, #2563eb);">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-value">{{ $orders->where('status', 'shipped')->count() }}</div>
                <div class="stat-label">Em Transporte</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #f59e0b, #d97706);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $orders->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Aguardando Pagamento</div>
            </div>
        </div>

        <!-- Lista de pedidos -->
        @if($orders->count() > 0)
            <div class="orders-grid">
                @foreach($orders as $order)
                    <div class="order-card animate-fade-in">
                        <!-- Header do pedido -->
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">
                                    <i class="fas fa-receipt"></i>
                                    Pedido #{{ $order->id }}
                                </div>
                                <div class="order-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                                </div>
                                <div class="order-status status-{{ $order->status }}">
                                    @switch($order->status)
                                        @case('pending')
                                            <i class="fas fa-clock"></i>
                                            Pendente
                                            @break
                                        @case('processing')
                                            <i class="fas fa-cog fa-spin"></i>
                                            Processando
                                            @break
                                        @case('shipped')
                                            <i class="fas fa-truck"></i>
                                            Enviado
                                            @break
                                        @case('delivered')
                                            <i class="fas fa-check-circle"></i>
                                            Entregue
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-times-circle"></i>
                                            Cancelado
                                            @break
                                        @default
                                            <i class="fas fa-question-circle"></i>
                                            {{ ucfirst($order->status) }}
                                    @endswitch
                                </div>
                            </div>
                            
                            <div class="order-summary">
                                <div class="order-total">
                                    R$ {{ number_format($order->total, 2, ',', '.') }}
                                </div>
                                <div class="order-payment">
                                    <i class="fas fa-credit-card"></i>
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                </div>
                            </div>
                        </div>

                        <!-- Itens do pedido -->
                        <div class="order-items">
                            <div class="order-items-header">
                                <i class="fas fa-list"></i>
                                {{ $order->orderItems->count() }} {{ $order->orderItems->count() === 1 ? 'item' : 'itens' }}
                            </div>
                            
                            <div class="items-grid">
                                @foreach($order->orderItems->take(4) as $item)
                                    <div class="item-card">
                                        <div class="item-name">
                                            <i class="fas fa-book"></i>
                                            {{ Str::limit($item->book->title, 30) }}
                                        </div>
                                        <div class="item-details">
                                            <span>Qtd: {{ $item->quantity }}</span>
                                            <span>R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->orderItems->count() > 4)
                                    <div class="item-card" style="background: #f1f5f9; border-style: dashed;">
                                        <div class="item-name" style="color: #64748b;">
                                            <i class="fas fa-plus"></i>
                                            Mais {{ $order->orderItems->count() - 4 }} {{ $order->orderItems->count() - 4 === 1 ? 'item' : 'itens' }}
                                        </div>
                                        <div class="item-details">
                                            <span style="color: #64748b;">Ver detalhes</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline de status (apenas para pedidos em andamento) -->
                        @if(in_array($order->status, ['processing', 'shipped']))
                            <div class="order-timeline">
                                <div class="timeline-item">
                                    <div class="timeline-icon" style="background: #10b981;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Pedido Confirmado</div>
                                        <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                                
                                @if($order->status === 'processing' || $order->status === 'shipped')
                                    <div class="timeline-item">
                                        <div class="timeline-icon" style="background: #3b82f6;">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-title">Em Preparação</div>
                                            <div class="timeline-date">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($order->status === 'shipped')
                                    <div class="timeline-item">
                                        <div class="timeline-icon" style="background: #8b5cf6;">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-title">Enviado</div>
                                            <div class="timeline-date">{{ $order->shipped_at ? $order->shipped_at->format('d/m/Y H:i') : 'Aguardando' }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Ações do pedido -->
                        <div class="order-actions">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Ver Detalhes
                            </a>
                            
                            @if($order->status === 'delivered')
                                <button class="btn btn-secondary" onclick="downloadInvoice('{{ $order->id }}')">
                                    <i class="fas fa-download"></i>
                                    Nota Fiscal
                                </button>
                            @endif
                            
                            @if(in_array($order->status, ['pending', 'processing']))
                                <form method="POST" action="{{ route('orders.cancel', $order) }}" style="display: inline;" 
                                      onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i>
                                        Cancelar
                                    </button>
                                </form>
                            @endif
                            
                            @if($order->status === 'delivered')
                                <button class="btn btn-secondary" onclick="reorderItems('{{ $order->id }}')">
                                    <i class="fas fa-redo"></i>
                                    Pedir Novamente
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            @if($orders->hasPages())
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Estado vazio -->
            <div class="empty-state animate-fade-in">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="empty-title">Nenhum pedido encontrado</h3>
                <p class="empty-description">
                    @if(request()->has('status') || request()->has('date_from') || request()->has('date_to'))
                        Não encontramos pedidos com os filtros aplicados. Tente ajustar os critérios de busca.
                    @else
                        Você ainda não fez nenhum pedido. Que tal começar explorando nossos livros?
                    @endif
                </p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-book"></i>
                    Explorar Livros
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação de entrada dos cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);
    
    // Observar cards para animação
    document.querySelectorAll('.animate-fade-in').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
        observer.observe(el);
    });
    
    // Auto-submit do formulário de filtro quando selecionar data
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });
});

// Função para baixar nota fiscal
function downloadInvoice(orderId) {
    // Simular download (implementar backend)
    alert('Funcionalidade de download de nota fiscal será implementada em breve!');
}

// Função para pedir novamente
function reorderItems(orderId) {
    if (confirm('Deseja adicionar todos os itens deste pedido ao carrinho?')) {
        // Implementar lógica de re-pedido
        fetch(`/orders/${orderId}/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Itens adicionados ao carrinho com sucesso!');
                window.location.href = '/cart';
            } else {
                alert('Erro ao adicionar itens ao carrinho. Tente novamente.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar solicitação.');
        });
    }
}

// Filtro dinâmico
function quickFilter(status) {
    const url = new URL(window.location);
    url.searchParams.set('status', status);
    window.location.href = url.toString();
}

// Função para limpar filtros
function clearFilters() {
    window.location.href = '{{ route("orders.index") }}';
}
</script>

@endsection