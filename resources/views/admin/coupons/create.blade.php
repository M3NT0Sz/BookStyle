@extends('layouts.app')

@section('content')
<style>
/* Design moderno para criação de cupons */
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
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
    z-index: 1;
}

/* Header */
.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
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

/* Form container */
.form-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

/* Form styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-control {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    background: #f9fafb;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    appearance: none;
}

/* Checkboxes e radio buttons */
.form-check {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: #667eea;
}

.form-check-label {
    font-size: 0.9rem;
    color: #374151;
    cursor: pointer;
}

/* Grid layout para campos */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-grid-full {
    grid-column: 1 / -1;
}

/* Botões */
.btn {
    padding: 1rem 2rem;
    border-radius: 12px;
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

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

/* Alert styles */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.alert-success {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

/* Responsividade */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
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
</style>

<div class="admin-coupons-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="page-header animate-fade-in">
            <div class="breadcrumb">
                <i class="fas fa-home"></i>
                <span>Admin</span>
                <i class="fas fa-chevron-right"></i>
                <span>Cupons</span>
                <i class="fas fa-chevron-right"></i>
                <span>Criar Cupom</span>
            </div>
            
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                Criar Novo Cupom
            </h1>
        </div>

        <!-- Form -->
        <div class="form-container animate-fade-in">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle"></i> Erro de validação:</strong>
                    <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <!-- Código do cupom -->
                    <div class="form-group">
                        <label for="code" class="form-label">
                            <i class="fas fa-tag"></i> Código do Cupom
                        </label>
                        <input type="text" 
                               id="code" 
                               name="code" 
                               class="form-control" 
                               value="{{ old('code') }}" 
                               placeholder="Ex: DESCONTO20"
                               required>
                        <small style="color: #64748b; font-size: 0.8rem;">
                            Deve ser único e fácil de lembrar
                        </small>
                    </div>

                    <!-- Tipo de desconto -->
                    <div class="form-group">
                        <label for="type" class="form-label">
                            <i class="fas fa-percentage"></i> Tipo de Desconto
                        </label>
                        <select id="type" name="type" class="form-control form-select" required>
                            <option value="">Selecione o tipo</option>
                            <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentual (%)</option>
                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                        </select>
                    </div>

                    <!-- Valor do desconto -->
                    <div class="form-group">
                        <label for="discount" class="form-label">
                            <i class="fas fa-money-bill-wave"></i> Valor do Desconto
                        </label>
                        <input type="number" 
                               id="discount" 
                               name="discount" 
                               class="form-control" 
                               value="{{ old('discount') }}" 
                               step="0.01"
                               min="0"
                               placeholder="Ex: 20"
                               required>
                        <small style="color: #64748b; font-size: 0.8rem;">
                            Valor em % ou R$ conforme o tipo
                        </small>
                    </div>

                    <!-- Tipo de trigger -->
                    <div class="form-group">
                        <label for="trigger_type" class="form-label">
                            <i class="fas fa-robot"></i> Tipo de Trigger
                        </label>
                        <select id="trigger_type" name="trigger_type" class="form-control form-select" required>
                            <option value="">Selecione o trigger</option>
                            <option value="manual" {{ old('trigger_type') === 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="first_purchase" {{ old('trigger_type') === 'first_purchase' ? 'selected' : '' }}>Primeiro Pedido</option>
                            <option value="cart_abandonment" {{ old('trigger_type') === 'cart_abandonment' ? 'selected' : '' }}>Abandono de Carrinho</option>
                            <option value="birthday" {{ old('trigger_type') === 'birthday' ? 'selected' : '' }}>Aniversário</option>
                            <option value="genre_based" {{ old('trigger_type') === 'genre_based' ? 'selected' : '' }}>Baseado em Gênero</option>
                            <option value="loyalty" {{ old('trigger_type') === 'loyalty' ? 'selected' : '' }}>Fidelidade</option>
                            <option value="high_value_cart" {{ old('trigger_type') === 'high_value_cart' ? 'selected' : '' }}>Carrinho Alto Valor</option>
                        </select>
                    </div>

                    <!-- Usuário específico -->
                    <div class="form-group">
                        <label for="user_id" class="form-label">
                            <i class="fas fa-user"></i> Usuário Específico (Opcional)
                        </label>
                        <select id="user_id" name="user_id" class="form-control form-select">
                            <option value="">Todos os usuários</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Máximo de usos -->
                    <div class="form-group">
                        <label for="max_uses" class="form-label">
                            <i class="fas fa-hashtag"></i> Máximo de Usos
                        </label>
                        <input type="number" 
                               id="max_uses" 
                               name="max_uses" 
                               class="form-control" 
                               value="{{ old('max_uses') }}" 
                               min="1"
                               placeholder="Ex: 100">
                        <small style="color: #64748b; font-size: 0.8rem;">
                            Deixe vazio para uso ilimitado
                        </small>
                    </div>

                    <!-- Valor mínimo do carrinho -->
                    <div class="form-group form-grid-full">
                        <label for="minimum_cart_value" class="form-label">
                            <i class="fas fa-shopping-cart"></i> Valor Mínimo do Carrinho (R$)
                        </label>
                        <input type="number" 
                               id="minimum_cart_value" 
                               name="minimum_cart_value" 
                               class="form-control" 
                               value="{{ old('minimum_cart_value') }}" 
                               step="0.01"
                               min="0"
                               placeholder="Ex: 50.00">
                        <small style="color: #64748b; font-size: 0.8rem;">
                            Valor mínimo para aplicar o cupom
                        </small>
                    </div>

                    <!-- Data de expiração -->
                    <div class="form-group form-grid-full">
                        <label for="expires_at" class="form-label">
                            <i class="fas fa-calendar"></i> Data de Expiração
                        </label>
                        <input type="date" 
                               id="expires_at" 
                               name="expires_at" 
                               class="form-control" 
                               value="{{ old('expires_at') }}"
                               min="{{ date('Y-m-d') }}">
                        <small style="color: #64748b; font-size: 0.8rem;">
                            Deixe vazio para cupom sem expiração
                        </small>
                    </div>

                    <!-- Status ativo -->
                    <div class="form-group form-grid-full">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   class="form-check-input" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">
                                <i class="fas fa-toggle-on"></i> Cupom ativo (disponível para uso)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.coupons') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Criar Cupom
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação de entrada
    const elements = document.querySelectorAll('.animate-fade-in');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 100);
    });

    // Gerar código automaticamente
    const codeInput = document.getElementById('code');
    const generateBtn = document.createElement('button');
    generateBtn.type = 'button';
    generateBtn.innerHTML = '<i class="fas fa-magic"></i>';
    generateBtn.style.cssText = `
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: #667eea;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.5rem;
        cursor: pointer;
        font-size: 0.8rem;
    `;
    generateBtn.title = 'Gerar código automaticamente';
    
    codeInput.parentElement.style.position = 'relative';
    codeInput.style.paddingRight = '3rem';
    codeInput.parentElement.appendChild(generateBtn);
    
    generateBtn.addEventListener('click', function() {
        const prefixes = ['SAVE', 'DISCOUNT', 'PROMO', 'DEAL', 'OFF'];
        const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
        const number = Math.floor(Math.random() * 99) + 1;
        codeInput.value = prefix + number;
    });

    // Validação em tempo real
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], select[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#10b981';
            }
        });
    });
});
</script>
@endsection
