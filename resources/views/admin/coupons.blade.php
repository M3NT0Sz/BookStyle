@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-magic"></i> Sistema de Cupons Inteligentes</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Estatísticas dos Cupons --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-ticket-alt fa-2x mr-3"></i>
                        <div>
                            <h5 class="card-title">Total de Cupons</h5>
                            <h2 class="mb-0">{{ $stats['total_coupons'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x mr-3"></i>
                        <div>
                            <h5 class="card-title">Cupons Ativos</h5>
                            <h2 class="mb-0">{{ $stats['active_coupons'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-robot fa-2x mr-3"></i>
                        <div>
                            <h5 class="card-title">Auto-Gerados</h5>
                            <h2 class="mb-0">{{ $stats['auto_generated'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line fa-2x mr-3"></i>
                        <div>
                            <h5 class="card-title">Total de Usos</h5>
                            <h2 class="mb-0">{{ $stats['total_usage'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ações Administrativas --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-cog"></i> Ações Administrativas</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group mb-2" role="group">
                        <button type="button" class="btn btn-success" onclick="createManualCoupon()">
                            <i class="fas fa-plus"></i> Criar Cupom Manual
                        </button>
                        <button type="button" class="btn btn-info" onclick="generateSmartCoupons()">
                            <i class="fas fa-magic"></i> Gerar Cupons Inteligentes
                        </button>
                    </div>
                    <div class="btn-group mb-2" role="group">
                        <a href="{{ route('admin.coupons.export', 'json') }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-code"></i> Exportar JSON
                        </a>
                        <a href="{{ route('admin.coupons.export', 'csv') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-csv"></i> Exportar CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabela de Cupons Inteligentes --}}
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Cupons Cadastrados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
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
                        @forelse($coupons as $coupon)
                            <tr class="{{ $coupon['is_active'] ? '' : 'table-secondary' }}">
                                <td>{{ $coupon['id'] }}</td>
                                <td>
                                    <code>{{ $coupon['code'] }}</code>
                                    @if($coupon['is_auto_generated'])
                                        <span class="badge badge-info">AUTO</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $coupon['type'] == 'percent' ? 'badge-success' : 'badge-primary' }}">
                                        {{ $coupon['type'] == 'percent' ? $coupon['discount'] . '%' : 'R$ ' . number_format($coupon['discount'], 2, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $coupon['trigger_type_display'] }}
                                    </span>
                                </td>
                                <td>
                                    {{ $coupon['user_name'] ?? 'Todos os usuários' }}
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        @php 
                                            $maxUses = $coupon['max_uses'] ?? 1;
                                            $usedCount = $coupon['used_count'] ?? 0;
                                            $percentage = $maxUses > 0 ? ($usedCount / $maxUses) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $percentage }}%" 
                                             aria-valuenow="{{ $usedCount }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $maxUses }}">
                                            {{ $usedCount }}/{{ $maxUses ?? '∞' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($coupon['expires_at'])
                                        @php 
                                            $expiryDate = date('Y-m-d', strtotime($coupon['expires_at']));
                                            $isExpired = strtotime($expiryDate) < time();
                                        @endphp
                                        <span class="badge {{ $isExpired ? 'badge-danger' : 'badge-warning' }}">
                                            {{ date('d/m/Y', strtotime($coupon['expires_at'])) }}
                                        </span>
                                        @if($isExpired)
                                            <small class="text-danger">Expirado</small>
                                        @endif
                                    @else
                                        <span class="badge badge-success">Sem expiração</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon['is_active'])
                                        <span class="badge badge-success">Ativo</span>
                                    @else
                                        <span class="badge badge-secondary">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info" onclick="viewCouponDetails({{ $coupon['id'] }})" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($coupon['is_active'])
                                            <button class="btn btn-warning" onclick="toggleCouponStatus({{ $coupon['id'] }}, false)" title="Desativar">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-success" onclick="toggleCouponStatus({{ $coupon['id'] }}, true)" title="Ativar">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger" onclick="deleteCoupon({{ $coupon['id'] }})" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhum cupom encontrado.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modais e JavaScript --}}
<script>
    function createManualCoupon() {
        // Implementar modal ou redirect para criação manual
        alert('Funcionalidade de criação manual será implementada');
    }

    function generateSmartCoupons() {
        if (confirm('Deseja gerar cupons inteligentes automáticos baseados no comportamento dos usuários?')) {
            // Ajax call para gerar cupons automáticos
            alert('Gerando cupons inteligentes... (funcionalidade em implementação)');
        }
    }

    function viewCouponDetails(id) {
        alert('Ver detalhes do cupom ID: ' + id);
    }

    function toggleCouponStatus(id, activate) {
        const action = activate ? 'ativar' : 'desativar';
        if (confirm(`Deseja ${action} este cupom?`)) {
            // Ajax call para toggle status
            alert(`${action} cupom ID: ${id} (funcionalidade em implementação)`);
        }
    }

    function deleteCoupon(id) {
        if (confirm('Tem certeza que deseja excluir este cupom? Esta ação não pode ser desfeita.')) {
            // Ajax call para deletar
            alert('Excluir cupom ID: ' + id + ' (funcionalidade em implementação)');
        }
    }
</script>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .table th {
        border-top: none;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .badge {
        font-size: 0.75rem;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .progress {
        background-color: #e9ecef;
    }

    .progress-bar {
        font-size: 0.75rem;
    }
</style>
@endsection
