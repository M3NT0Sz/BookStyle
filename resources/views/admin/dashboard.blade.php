@extends('layouts.app')

@section('title', 'Painel Administrativo - BookStyle')

@section('content')
<style>
/* Design moderno para painel admin */
* {
    box-sizing: border-box;
}

.admin-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

.admin-wrapper::before {
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

/* Header do admin */
.admin-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.admin-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.admin-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.admin-subtitle {
    font-size: 1.1rem;
    color: #64748b;
    margin-bottom: 2rem;
}

.admin-breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #64748b;
}

/* Cards de navegação */
.admin-nav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.admin-nav-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}

.admin-nav-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(45deg, var(--card-color-1), var(--card-color-2));
    transition: height 0.3s ease;
}

.admin-nav-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.admin-nav-card:hover::before {
    height: 8px;
}

.card-books {
    --card-color-1: #10b981;
    --card-color-2: #059669;
}

.card-users {
    --card-color-1: #3b82f6;
    --card-color-2: #2563eb;
}

.card-coupons {
    --card-color-1: #f59e0b;
    --card-color-2: #d97706;
}

.card-stats {
    --card-color-1: #8b5cf6;
    --card-color-2: #7c3aed;
}

.nav-card-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto 1.5rem;
    background: linear-gradient(45deg, var(--card-color-1), var(--card-color-2));
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.nav-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    text-align: center;
}

.nav-card-description {
    color: #64748b;
    text-align: center;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.nav-card-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--card-color-1);
    display: block;
}

.stat-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Seção de estatísticas rápidas */
.quick-stats {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.stats-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.quick-stat-card {
    background: #f8fafc;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.quick-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.quick-stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin: 0 auto 1rem;
}

.quick-stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.quick-stat-label {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Botões */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
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

/* Responsividade */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .admin-header {
        padding: 2rem 1.5rem;
    }
    
    .admin-title {
        font-size: 2rem;
        flex-direction: column;
    }
    
    .admin-nav-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-wrapper">
    <div class="container">
        <!-- Header do Admin -->
        <div class="admin-header animate-fade-in">
            <h1 class="admin-title">
                <i class="fas fa-shield-alt"></i>
                Painel Administrativo
            </h1>
            <p class="admin-subtitle">
                Gerencie toda a plataforma BookStyle com controle total e visualizações avançadas
            </p>
            <div class="admin-breadcrumb">
                <i class="fas fa-home"></i>
                <span>Início</span>
                <i class="fas fa-chevron-right"></i>
                <span>Admin</span>
                <i class="fas fa-chevron-right"></i>
                <span>Dashboard</span>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="quick-stats animate-fade-in">
            <h2 class="stats-title">
                <i class="fas fa-chart-line"></i>
                Visão Geral do Sistema
            </h2>
            <div class="stats-grid">
                <div class="quick-stat-card">
                    <div class="quick-stat-icon" style="background: linear-gradient(45deg, #10b981, #059669);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="quick-stat-value">{{ $totalBooks ?? '0' }}</div>
                    <div class="quick-stat-label">Total de Livros</div>
                </div>
                
                <div class="quick-stat-card">
                    <div class="quick-stat-icon" style="background: linear-gradient(45deg, #3b82f6, #2563eb);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="quick-stat-value">{{ $totalUsers ?? '0' }}</div>
                    <div class="quick-stat-label">Usuários Ativos</div>
                </div>
                
                <div class="quick-stat-card">
                    <div class="quick-stat-icon" style="background: linear-gradient(45deg, #f59e0b, #d97706);">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="quick-stat-value">{{ $totalCoupons ?? '0' }}</div>
                    <div class="quick-stat-label">Cupons Ativos</div>
                </div>
                
                <div class="quick-stat-card">
                    <div class="quick-stat-icon" style="background: linear-gradient(45deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="quick-stat-value">{{ $totalOrders ?? '0' }}</div>
                    <div class="quick-stat-label">Pedidos Hoje</div>
                </div>
            </div>
        </div>

        <!-- Navegação Principal -->
        <div class="admin-nav-grid">
            <!-- Gerenciar Livros -->
            <a href="{{ route('admin.books') }}" class="admin-nav-card card-books animate-fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="nav-card-title">Gerenciar Livros</h3>
                <p class="nav-card-description">
                    Visualize, edite e exporte todos os livros cadastrados na plataforma. 
                    Controle completo sobre o catálogo.
                </p>
                <div class="nav-card-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $totalBooks ?? '0' }}</span>
                        <span class="stat-label">Total</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $newBooksToday ?? '0' }}</span>
                        <span class="stat-label">Novos Hoje</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-arrow-right" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                </div>
            </a>

            <!-- Gerenciar Usuários -->
            <a href="{{ route('admin.users') }}" class="admin-nav-card card-users animate-fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="nav-card-title">Gerenciar Usuários</h3>
                <p class="nav-card-description">
                    Administre contas de usuários, edite permissões e exporte dados. 
                    Controle de acesso centralizado.
                </p>
                <div class="nav-card-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $totalUsers ?? '0' }}</span>
                        <span class="stat-label">Usuários</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $adminUsers ?? '0' }}</span>
                        <span class="stat-label">Admins</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-arrow-right" style="color: #3b82f6; font-size: 1.2rem;"></i>
                    </div>
                </div>
            </a>

            <!-- Gerenciar Cupons -->
            <a href="{{ route('admin.coupons') }}" class="admin-nav-card card-coupons animate-fade-in">
                <div class="nav-card-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <h3 class="nav-card-title">Sistema de Cupons</h3>
                <p class="nav-card-description">
                    Crie e gerencie cupons inteligentes com IA. Sistema avançado 
                    de descontos e promoções.
                </p>
                <div class="nav-card-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $totalCoupons ?? '0' }}</span>
                        <span class="stat-label">Ativos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $usedCoupons ?? '0' }}</span>
                        <span class="stat-label">Usados</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-arrow-right" style="color: #f59e0b; font-size: 1.2rem;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Botão Voltar -->
        <div style="text-align: center;">
            <a href="{{ route('index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Site
            </a>
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

    // Efeito hover nos cards
    const navCards = document.querySelectorAll('.admin-nav-card');
    navCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

@endsection
