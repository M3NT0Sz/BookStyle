@extends('layouts.app')

@section('title', 'Gerenciar Pedidos - Admin')

@section('content')
<style>
/* Estilos modernos para gerenciamento de pedidos */
.orders-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.orders-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Header */
.orders-header {
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
    margin-bottom: 1rem;
}

.orders-title {
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

/* Cards de estatísticas */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
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
    font-size: 0.9rem;
}

/* Tabela de pedidos */
.orders-table-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.table-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
}

.table-controls {
    display: flex;
    gap: 1rem;
}

.search-box {
    position: relative;
}

.search-box input {
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    width: 300px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.search-box input:focus {
    outline: none;
    border-color: #667eea;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
}

.orders-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.5rem;
}

.orders-table thead th {
    padding: 1rem;
    text-align: left;
    font-size: 0.85rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #e5e7eb;
}

.orders-table tbody tr {
    background: #f8fafc;
    transition: all 0.3s;
}

.orders-table tbody tr:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.orders-table tbody td {
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
}

.orders-table tbody td:first-child {
    border-left: 1px solid #e5e7eb;
    border-radius: 12px 0 0 12px;
}

.orders-table tbody td:last-child {
    border-right: 1px solid #e5e7eb;
    border-radius: 0 12px 12px 0;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-processing {
    background: #dbeafe;
    color: #1e40af;
}

.status-shipped {
    background: #e0e7ff;
    color: #3730a3;
}

.status-delivered {
    background: #d1fae5;
    color: #065f46;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
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

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
}

.btn-view {
    background: #f8fafc;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-view:hover {
    background: #667eea;
    color: white;
}

.btn-export {
    background: #10b981;
    color: white;
}

.btn-export:hover {
    background: #059669;
}

/* Paginação */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination .page-link {
    padding: 0.75rem 1rem;
    border-radius: 10px;
    border: 2px solid #e5e7eb;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.pagination .page-link:hover {
    border-color: #667eea;
    color: #667eea;
}

.pagination .active .page-link {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border-color: transparent;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    font-size: 5rem;
    color: #e5e7eb;
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #9ca3af;
}

/* Responsividade */
@media (max-width: 768px) {
    .orders-container {
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
    
    .search-box input {
        width: 100%;
    }
    
    .orders-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<div class="orders-wrapper">
    <div class="orders-container">
        <!-- Header -->
        <div class="orders-header">
            <div class="header-top">
                <h1 class="orders-title">
                    <i class="fas fa-shopping-bag"></i>
                    Gerenciar Pedidos
                </h1>
                <div class="header-actions">
                    <a href="{{ route('admin.orders.export', 'csv') }}" class="btn btn-export btn-sm">
                        <i class="fas fa-file-csv"></i>
                        Exportar CSV
                    </a>
                    <a href="{{ route('admin.orders.export', 'json') }}" class="btn btn-export btn-sm">
                        <i class="fas fa-file-code"></i>
                        Exportar JSON
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #3b82f6, #2563eb);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ $orders->total() }}</div>
                <div class="stat-label">Total de Pedidos</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #fbbf24, #f59e0b);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $orders->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pendentes</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #8b5cf6, #7c3aed);">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-value">{{ $orders->where('status', 'processing')->count() }}</div>
                <div class="stat-label">Em Processamento</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $orders->where('status', 'delivered')->count() }}</div>
                <div class="stat-label">Entregues</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #14b8a6, #0d9488);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">R$ {{ number_format($orders->sum('total_amount'), 2, ',', '.') }}</div>
                <div class="stat-label">Valor Total</div>
            </div>
        </div>

        <!-- Tabela de Pedidos -->
        <div class="orders-table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-list"></i>
                    Lista de Pedidos
                </h2>
                <div class="table-controls">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Buscar por ID, usuário...">
                    </div>
                    <select class="filter-select" id="statusFilter">
                        <option value="">Todos os Status</option>
                        <option value="pending">Pendente</option>
                        <option value="processing">Processando</option>
                        <option value="shipped">Enviado</option>
                        <option value="delivered">Entregue</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
            </div>

            @if($orders->count() > 0)
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Itens</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        @foreach($orders as $order)
                        <tr data-status="{{ $order->status }}">
                            <td>
                                <strong>#{{ $order->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->user->name ?? 'N/A' }}</strong><br>
                                    <small style="color: #64748b;">{{ $order->user->email ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <strong style="color: #10b981;">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</strong>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <span style="color: #64748b;">{{ $order->orderItems->count() }} item(ns)</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-view btn-sm">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="pagination-wrapper">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="empty-state-title">Nenhum pedido encontrado</h3>
                    <p class="empty-state-text">Os pedidos realizados aparecerão aqui.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('ordersTableBody');
    const rows = tableBody ? tableBody.getElementsByTagName('tr') : [];

    // Função de filtro
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        for (let row of rows) {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        }
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterTable);
    }
});
</script>

@endsection
