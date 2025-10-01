@extends('layouts.app')

{{-- Incluir CSS do formulário de cupons --}}
@section('head')
    {{-- <link rel="stylesheet" href="{{ asset('css/coupons_form.css') }}"> --}}
@endsection

@section('content')
<style>
/* Design moderno para sistema de cupons inteligentes */
* {
    box-sizing: border-box;
}

.admin-coupons-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

.admin-coupons-wrapper::before {
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

/* Header moderno */
.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 0;
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 1rem;
}

/* Cards de estatísticas */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: var(--card-gradient);
}

.stat-card.primary {
    --card-gradient: linear-gradient(45deg, #667eea, #764ba2);
}

.stat-card.success {
    --card-gradient: linear-gradient(45deg, #10b981, #059669);
}

.stat-card.info {
    --card-gradient: linear-gradient(45deg, #3b82f6, #2563eb);
}

.stat-card.warning {
    --card-gradient: linear-gradient(45deg, #f59e0b, #d97706);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: var(--card-gradient);
}

.stat-info h3 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0;
    color: #1e293b;
}

.stat-info p {
    font-size: 1rem;
    color: #64748b;
    margin: 0;
    font-weight: 500;
}

/* Seção de ações */
.actions-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.actions-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: center;
}

.action-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Botões modernos */
.btn {
    padding: 1rem 2rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-success {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    color: white;
    text-decoration: none;
}

.btn-info {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
    color: white;
    text-decoration: none;
}

.btn-warning {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
    color: white;
    text-decoration: none;
}

.btn-danger {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(239, 68, 68, 0.4);
    color: white;
    text-decoration: none;
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
    font-size: 0.8rem;
}

/* Tabela moderna */
.coupons-table-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    overflow: hidden;
}

.table-header {
    margin-bottom: 2rem;
}

.table-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.table-subtitle {
    color: #64748b;
    margin: 0.5rem 0 0;
    font-size: 1rem;
}

.coupons-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.coupons-table thead {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.coupons-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    font-weight: 600;
    color: white;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: none;
}

.coupons-table th:first-child {
    border-radius: 15px 0 0 0;
}

.coupons-table th:last-child {
    border-radius: 0 15px 0 0;
}

.coupons-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.coupons-table tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.01);
}

.coupons-table td {
    padding: 1.25rem 1rem;
    color: #374151;
    font-size: 0.9rem;
}

/* Badges modernos */
.badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.badge-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.badge-success {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
}

.badge-info {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    color: white;
}

.badge-warning {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    color: white;
}

.badge-danger {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
}

.badge-secondary {
    background: #f1f5f9;
    color: #64748b;
}

/* Progress bar moderno */
.progress {
    height: 25px;
    background: #f1f5f9;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    transition: width 0.3s ease;
}

/* Código de cupom */
.coupon-code {
    background: #f8fafc;
    color: #667eea;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    border: 2px dashed #667eea;
}

/* Estado vazio */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.empty-icon {
    font-size: 5rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 2rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
}

.empty-description {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* User badge */
.user-badge {
    background: #f0f4ff;
    color: #667eea;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Responsividade */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-group {
        justify-content: center;
    }
    
    .coupons-table {
        font-size: 0.8rem;
    }
    
    .coupons-table th,
    .coupons-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .action-buttons {
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

/* Estilos do formulário de cupons */
.coupons-form-container {
    max-width: 500px;
    margin: 2.5rem auto;
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 0.5rem 2rem rgba(44, 59, 68, 0.08);
    padding: 2.5rem 2rem 2rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.coupons-form-title {
    color: #1abc9c;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
}

.coupons-form label {
    color: #3498db;
    font-weight: 600;
    margin-bottom: 0.3rem;
    display: block;
}

.coupons-form input[type="text"],
.coupons-form input[type="number"],
.coupons-form input[type="date"],
.coupons-form select {
    width: 100%;
    padding: 0.7rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #ecf0f1;
    font-size: 1rem;
    background: #f9f9f9;
    margin-bottom: 1rem;
    transition: border 0.2s;
}

.coupons-form input:focus,
.coupons-form select:focus {
    border: 1.5px solid #1abc9c;
    outline: none;
}

.coupons-form-btns {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
}

.coupons-form-btn, .coupons-form-btn-back {
    padding: 0.7rem 2rem;
    border: none;
    border-radius: 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
}

.coupons-form-btn {
    background: #1abc9c;
    color: #fff;
}

.coupons-form-btn:hover {
    background: #16a085;
}

.coupons-form-btn-back {
    background: #3498db;
    color: #fff;
}

.coupons-form-btn-back:hover {
    background: #2980b9;
}

.coupons-form-error {
    color: #e74c3c;
    background: #fdecea;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    margin-bottom: 1rem;
    text-align: center;
    font-weight: 600;
}

@media (max-width: 600px) {
    .coupons-form-container {
        padding: 1rem 0.5rem;
    }
    .coupons-form-title {
        font-size: 1.2rem;
    }
    .coupons-form-btn, .coupons-form-btn-back {
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
}
</style>

<div class="admin-coupons-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="page-header animate-fade-in">
            <div class="breadcrumb">
                <i class="fas fa-home"></i>
                <span>Admin</span>
                <i class="fas fa-chevron-right"></i>
                <span>Dashboard</span>
                <i class="fas fa-chevron-right"></i>
                <span>Cupons Inteligentes</span>
            </div>
            
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-magic"></i>
                    Sistema de Cupons Inteligentes
                </h1>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid animate-fade-in">
            <div class="stat-card primary">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $stats['total_coupons'] }}</h3>
                        <p>Total de Cupons</p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $stats['active_coupons'] }}</h3>
                        <p>Cupons Ativos</p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $stats['auto_generated'] }}</h3>
                        <p>Auto-Gerados</p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $stats['total_usage'] }}</h3>
                        <p>Total de Usos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Administrativas -->
        <div class="actions-section animate-fade-in">
            <div class="actions-grid">
                <div>
                    <h3 style="margin: 0; color: #1e293b; font-size: 1.5rem; font-weight: 700;">
                        <i class="fas fa-cog"></i>
                        Ações Administrativas
                    </h3>
                    <p style="margin: 0.5rem 0 0; color: #64748b; font-size: 1rem;">
                        Gerencie cupons manualmente ou configure geração automática
                    </p>
                </div>
                <div class="action-group">
                    <button type="button" class="btn btn-success" onclick="createManualCoupon()">
                        <i class="fas fa-plus"></i>
                        Criar Cupom Manual
                    </button>
                    <button type="button" class="btn btn-info" onclick="generateSmartCoupons()">
                        <i class="fas fa-magic"></i>
                        Gerar Cupons IA
                    </button>
                    <a href="{{ route('admin.coupons.export', 'json') }}" class="btn btn-primary">
                        <i class="fas fa-file-code"></i>
                        Exportar JSON
                    </a>
                    <a href="{{ route('admin.coupons.export', 'csv') }}" class="btn btn-warning">
                        <i class="fas fa-file-csv"></i>
                        Exportar CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabela de Cupons -->
        <div class="coupons-table-container animate-fade-in">
            @if(count($coupons) > 0)
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="fas fa-list"></i>
                        Cupons Cadastrados
                    </h2>
                    <p class="table-subtitle">
                        Total de {{ count($coupons) }} {{ count($coupons) === 1 ? 'cupom encontrado' : 'cupons encontrados' }}
                    </p>
                </div>

                <div style="overflow-x: auto;">
                    <table class="coupons-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-tag"></i> Código</th>
                                <th><i class="fas fa-percentage"></i> Desconto</th>
                                <th><i class="fas fa-robot"></i> Tipo Trigger</th>
                                <th><i class="fas fa-user"></i> Usuário</th>
                                <th><i class="fas fa-chart-bar"></i> Uso</th>
                                <th><i class="fas fa-calendar"></i> Validade</th>
                                <th><i class="fas fa-toggle-on"></i> Status</th>
                                <th><i class="fas fa-cog"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr class="{{ ($coupon['is_active'] ?? true) ? '' : 'table-secondary' }}">
                                    <td>
                                        <strong style="color: #667eea; font-size: 1rem;">#{{ $coupon['id'] ?? '' }}</strong>
                                    </td>
                                    <td>
                                        <div class="coupon-code">{{ $coupon['code'] ?? 'N/A' }}</div>
                                        @if($coupon['is_auto_generated'] ?? false)
                                            <div style="margin-top: 0.5rem;">
                                                <span class="badge badge-info">
                                                    <i class="fas fa-robot"></i>
                                                    AUTO
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ ($coupon['type'] ?? 'percent') == 'percent' ? 'badge-success' : 'badge-primary' }}">
                                            @if(($coupon['type'] ?? 'percent') == 'percent')
                                                <i class="fas fa-percentage"></i>
                                                {{ $coupon['discount'] ?? 0 }}%
                                            @else
                                                <i class="fas fa-dollar-sign"></i>
                                                R$ {{ number_format($coupon['discount'] ?? 0, 2, ',', '.') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            @php
                                                $triggerTypeDisplays = [
                                                    'manual' => 'Manual',
                                                    'first_purchase' => 'Primeiro Pedido',
                                                    'cart_abandonment' => 'Abandono Carrinho',
                                                    'birthday' => 'Aniversário',
                                                    'genre_based' => 'Baseado em Gênero',
                                                    'loyalty' => 'Fidelidade',
                                                    'high_value_cart' => 'Carrinho Alto Valor'
                                                ];
                                                $triggerType = $coupon['trigger_type'] ?? 'manual';
                                            @endphp
                                            <i class="fas fa-cog"></i>
                                            {{ $triggerTypeDisplays[$triggerType] ?? 'Desconhecido' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($coupon['user_name'] ?? null)
                                            <span class="user-badge">
                                                <i class="fas fa-user"></i>
                                                {{ $coupon['user_name'] }}
                                            </span>
                                        @else
                                            <span style="color: #9ca3af; font-style: italic;">
                                                <i class="fas fa-users"></i>
                                                Todos os usuários
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress">
                                            @php 
                                                $maxUses = $coupon['max_uses'] ?? 1;
                                                $usedCount = $coupon['used_count'] ?? 0;
                                                $percentage = $maxUses > 0 ? ($usedCount / $maxUses) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" 
                                                 style="width: {{ $percentage }}%">
                                                {{ $usedCount }}/{{ $maxUses ?? '∞' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon['expires_at'] ?? null)
                                            @php 
                                                $expiryDate = date('Y-m-d', strtotime($coupon['expires_at']));
                                                $isExpired = strtotime($expiryDate) < time();
                                            @endphp
                                            <span class="badge {{ $isExpired ? 'badge-danger' : 'badge-warning' }}">
                                                <i class="fas fa-calendar"></i>
                                                {{ date('d/m/Y', strtotime($coupon['expires_at'])) }}
                                            </span>
                                            @if($isExpired)
                                                <div style="margin-top: 0.25rem;">
                                                    <small style="color: #ef4444; font-weight: 600;">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        Expirado
                                                    </small>
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-infinity"></i>
                                                Sem expiração
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon['is_active'] ?? true)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-pause-circle"></i>
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-info btn-sm" onclick="viewCouponDetails({{ $coupon['id'] ?? 0 }})" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                                Ver
                                            </button>
                                            @if($coupon['is_active'] ?? true)
                                                <button class="btn btn-warning btn-sm" onclick="toggleCouponStatus({{ $coupon['id'] ?? 0 }}, false)" title="Desativar">
                                                    <i class="fas fa-pause"></i>
                                                    Pausar
                                                </button>
                                            @else
                                                <button class="btn btn-success btn-sm" onclick="toggleCouponStatus({{ $coupon['id'] ?? 0 }}, true)" title="Ativar">
                                                    <i class="fas fa-play"></i>
                                                    Ativar
                                                </button>
                                            @endif
                                            <button class="btn btn-danger btn-sm" onclick="deleteCoupon({{ $coupon['id'] ?? 0 }})" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                                Excluir
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3 class="empty-title">Nenhum cupom inteligente encontrado</h3>
                    <p class="empty-description">
                        Ainda não há cupons cadastrados no sistema. 
                        Você pode criar cupons manualmente ou configurar a geração automática baseada em IA.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <button onclick="createManualCoupon()" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Criar Primeiro Cupom
                        </button>
                        <button onclick="generateSmartCoupons()" class="btn btn-info">
                            <i class="fas fa-magic"></i>
                            Gerar com IA
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação de entrada suave
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
    
    // Observar elementos para animação
    document.querySelectorAll('.animate-fade-in').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
        observer.observe(el);
    });

    // Hover effects nas linhas da tabela
    const tableRows = document.querySelectorAll('.coupons-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });

    // Animação nos cards de estatísticas
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px) scale(1)';
        });
    });
});

function createManualCoupon() {
    // Redirecionar para a página de criação de cupom
    if (confirm('Deseja criar um novo cupom manual?\n\nVocê será direcionado para o formulário de criação.')) {
        window.location.href = "{{ route('admin.coupons.create') }}";
    }
}

function generateSmartCoupons() {
    if (confirm('🤖 Gerar Cupons Inteligentes com IA\n\nO sistema analisará o comportamento dos usuários e gerará cupons automáticos personalizados.\n\nDeseja continuar?')) {
        // Simular loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando...';
        button.disabled = true;
        
        // Fazer requisição para gerar cupons
        fetch("{{ route('admin.coupons.generateSmart') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✨ Cupons inteligentes gerados com sucesso!\n\n' + data.message);
                window.location.reload();
            } else {
                alert('❌ Erro ao gerar cupons: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro na requisição. Tentando método alternativo...');
            // Fallback: usar form submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('admin.coupons.generateSmart') }}";
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            
            document.body.appendChild(form);
            form.submit();
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

function viewCouponDetails(id) {
    alert(`📋 Detalhes do Cupom ID: ${id}\n\nEsta funcionalidade abrirá um modal com:\n• Histórico de uso\n• Condições aplicadas\n• Métricas de performance\n• Configurações avançadas\n\n(Em implementação)`);
}

function toggleCouponStatus(id, activate) {
    const action = activate ? 'ativar' : 'desativar';
    const icon = activate ? '▶️' : '⏸️';
    
    if (confirm(`${icon} ${action.toUpperCase()} CUPOM\n\nDeseja ${action} este cupom?\n\nID: ${id}\nEsta ação pode ser revertida a qualquer momento.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/coupons/${id}/toggle`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);
        
        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        form.appendChild(token);
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'is_active';
        statusField.value = activate ? '1' : '0';
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteCoupon(id) {
    if (confirm('🗑️ EXCLUIR CUPOM\n\nTem certeza que deseja excluir este cupom?\n\nID: ' + id + '\n\n⚠️ Esta ação NÃO pode ser desfeita!\n\n• Todas as configurações serão perdidas\n• Histórico de uso será mantido para relatórios')) {
        // Confirmação dupla para ações críticas
        const code = prompt('Por segurança, digite "EXCLUIR" em maiúsculas para confirmar:');
        if (code === 'EXCLUIR') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/coupons/${id}`;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            
            document.body.appendChild(form);
            form.submit();
        } else if (code !== null) {
            alert('❌ Código incorreto. Operação cancelada.');
        }
    }
}
</script>
@endsection
