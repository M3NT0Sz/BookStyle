@extends('layouts.app')

@section('title', 'Checkout - BookStyle')

@section('content')
<style>
/* Layout moderno para checkout */
* {
    box-sizing: border-box;
}

.checkout-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

.checkout-wrapper::before {
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
.header-section {
    text-align: center;
    margin-bottom: 3rem;
    color: white;
}

.header-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #fff, #e6f3ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

/* Barra de progresso */
.progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: 12px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.2);
}

.progress-step.active {
    background: rgba(255,255,255,0.2);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.progress-connector {
    width: 3rem;
    height: 2px;
    background: rgba(255,255,255,0.3);
    border-radius: 1px;
}

/* Layout principal */
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    align-items: start;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Cards de formulário */
.form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
}

.form-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-icon {
    font-size: 1.25rem;
    color: #667eea;
}

.info-button {
    background: #667eea;
    color: white;
    border: none;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.info-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Métodos de pagamento */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-method {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.payment-method::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    transition: left 0.5s ease;
}

.payment-method:hover::before {
    left: 100%;
}

.payment-method.selected {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
}

.payment-method:hover {
    transform: translateX(5px);
    border-color: #5a67d8;
}

.radio-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.payment-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.payment-icon {
    font-size: 1.5rem;
    min-width: 2rem;
    text-align: center;
}

.payment-info {
    flex: 1;
}

.payment-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 1.1rem;
}

.payment-description {
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.demo-badge {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* Formulários */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-input {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    background: white;
}

.form-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.hidden {
    display: none !important;
}

/* Sidebar de resumo */
.summary-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.summary-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.summary-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.summary-subtitle {
    color: #64748b;
    font-size: 0.875rem;
}

.cart-items {
    margin-bottom: 2rem;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 3rem;
    height: 3rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.item-details {
    flex: 1;
}

.item-title {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.item-info {
    color: #64748b;
    font-size: 0.75rem;
}

.item-price {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
}

.summary-totals {
    margin-bottom: 2rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    padding: 0.5rem 0;
}

.total-label {
    color: #64748b;
    font-size: 0.875rem;
}

.total-value {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
}

.final-total {
    border-top: 2px solid #f1f5f9;
    padding-top: 1rem;
    margin-top: 1rem;
}

.final-total .total-row {
    margin-bottom: 0;
}

.final-total .total-label,
.final-total .total-value {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

.finalize-button {
    width: 100%;
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.finalize-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.finalize-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.security-info {
    text-align: center;
}

.security-text {
    color: #64748b;
    font-size: 0.75rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.security-badges {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.security-badge {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    color: #10b981;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 2rem;
}

.modal-content {
    background: white;
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.modal-overlay[style*="flex"] .modal-content {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 2px solid #f1f5f9;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-close {
    background: #f1f5f9;
    border: none;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #64748b;
}

.modal-close:hover {
    background: #e2e8f0;
    color: #374151;
}

.modal-body {
    padding: 2rem;
}

.info-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #e5e7eb;
}

.info-section.demo {
    background: #fef3c7;
    border-left-color: #f59e0b;
}

.info-section.pix {
    background: #ede9fe;
    border-left-color: #8b5cf6;
}

.info-section.boleto {
    background: #fef3c7;
    border-left-color: #f59e0b;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.info-icon {
    font-size: 1.25rem;
}

.info-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
}

.info-text {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.info-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.feature-icon {
    font-size: 1.5rem;
}

.feature-title {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
}

.feature-desc {
    color: #64748b;
    font-size: 0.75rem;
}

/* Animações */
.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsividade */
@media (max-width: 1024px) {
    .checkout-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .summary-sidebar {
        order: -1;
        position: static;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .header-title {
        font-size: 2rem;
    }
    
    .form-card,
    .summary-card {
        padding: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .progress-bar {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .progress-connector {
        width: 2px;
        height: 1rem;
        transform: rotate(90deg);
    }
    
    .info-features {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="checkout-wrapper">
    <div class="container">
        <!-- Header moderno -->
        <div class="header-section">
            <h1 class="header-title">🛒 Checkout Seguro</h1>
            <p class="header-subtitle">Finalize sua compra com total segurança e praticidade</p>
            
            <!-- Barra de progresso -->
            <div class="progress-bar">
                <div class="progress-step active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Carrinho</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step active">
                    <i class="fas fa-credit-card"></i>
                    <span>Pagamento</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i>
                    <span>Confirmação</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" id="checkout-form">
            @csrf
            
            <div class="checkout-grid">
                <!-- Formulários principais -->
                <div class="form-section">
                    <!-- Métodos de Pagamento -->
                    <div class="form-card animate-slide-in">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-credit-card card-icon"></i>
                                Método de Pagamento
                            </h2>
                            <button type="button" class="info-button" onclick="togglePaymentInfo()" title="Informações sobre pagamento">
                                <i class="fas fa-question"></i>
                            </button>
                        </div>
                        
                        <div class="payment-methods">
                            <!-- Cartão de Crédito -->
                            <div class="payment-method" data-method="credit_card">
                                <input type="radio" name="payment_method" value="credit_card" id="credit_card" class="radio-input" required>
                                <label for="credit_card" class="payment-content">
                                    <div class="payment-icon">
                                        <i class="far fa-credit-card" style="color: #3b82f6;"></i>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">Cartão de Crédito</div>
                                        <div class="payment-description">Visa, Mastercard, Elo</div>
                                    </div>
                                    <div class="demo-badge">DEMO</div>
                                </label>
                            </div>
                            
                            <!-- Cartão de Débito -->
                            <div class="payment-method" data-method="debit_card">
                                <input type="radio" name="payment_method" value="debit_card" id="debit_card" class="radio-input" required>
                                <label for="debit_card" class="payment-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-credit-card" style="color: #10b981;"></i>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">Cartão de Débito</div>
                                        <div class="payment-description">Débito instantâneo</div>
                                    </div>
                                    <div class="demo-badge">DEMO</div>
                                </label>
                            </div>
                            
                            <!-- PIX -->
                            <div class="payment-method" data-method="pix">
                                <input type="radio" name="payment_method" value="pix" id="pix" class="radio-input" required>
                                <label for="pix" class="payment-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-qrcode" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">PIX</div>
                                        <div class="payment-description">Pagamento instantâneo</div>
                                    </div>
                                    <div class="demo-badge">DEMO</div>
                                </label>
                            </div>
                            
                            <!-- Boleto -->
                            <div class="payment-method" data-method="boleto">
                                <input type="radio" name="payment_method" value="boleto" id="boleto" class="radio-input" required>
                                <label for="boleto" class="payment-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-barcode" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">Boleto Bancário</div>
                                        <div class="payment-description">Vencimento em 3 dias</div>
                                    </div>
                                    <div class="demo-badge">DEMO</div>
                                </label>
                            </div>
                        </div>
                        
                        @error('payment_method')
                            <div class="error-message mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <!-- Campos do cartão (ocultos por padrão) -->
                        <div id="card-fields" class="hidden mt-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-lock mr-2 text-green-600"></i>
                                Dados do Cartão
                            </h3>
                            <div class="form-grid">
                                <div class="form-group full">
                                    <label class="form-label">Nome no Cartão</label>
                                    <input type="text" id="card_name" name="card_name" class="form-input" placeholder="Como aparece no cartão">
                                </div>
                                <div class="form-group full">
                                    <label class="form-label">Número do Cartão</label>
                                    <input type="text" id="card_number" name="card_number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Validade</label>
                                    <input type="text" id="card_expiry" name="card_expiry" class="form-input" placeholder="MM/AA" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">CVV</label>
                                    <input type="text" id="card_cvv" name="card_cvv" class="form-input" placeholder="123" maxlength="4">
                                </div>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg mt-4">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Ambiente de demonstração:</strong> Use dados fictícios. Nenhum pagamento real será processado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço de Cobrança -->
                    <div class="form-card animate-slide-in">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-map-marker-alt card-icon"></i>
                                Endereço de Cobrança
                            </h2>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group full">
                                <label class="form-label">CEP</label>
                                <input type="text" name="billing_address[postal_code]" id="billing_cep" class="form-input" placeholder="00000-000" maxlength="9" required>
                                <div id="billing_cep_loading" class="hidden mt-2 text-sm text-blue-600">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    Buscando endereço...
                                </div>
                            </div>
                            <div class="form-group full">
                                <label class="form-label">Rua/Logradouro</label>
                                <input type="text" name="billing_address[street]" id="billing_street" class="form-input" readonly required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Número</label>
                                <input type="text" name="billing_address[number]" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Complemento</label>
                                <input type="text" name="billing_address[complement]" class="form-input" placeholder="Opcional">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bairro</label>
                                <input type="text" name="billing_address[neighborhood]" id="billing_neighborhood" class="form-input" readonly required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="billing_address[city]" id="billing_city" class="form-input" readonly required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="billing_address[state]" id="billing_state" class="form-input form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço de Entrega -->
                    <div class="form-card animate-slide-in">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-truck card-icon"></i>
                                Endereço de Entrega
                            </h2>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" id="same-address" name="same_address" value="1" class="rounded">
                                <span>Mesmo endereço de cobrança</span>
                            </label>
                        </div>
                        
                        <div class="form-grid" id="shipping-fields">
                            <div class="form-group full">
                                <label class="form-label">CEP</label>
                                <input type="text" name="shipping_address[postal_code]" id="shipping_cep" class="form-input" placeholder="00000-000" maxlength="9" required>
                            </div>
                            <div class="form-group full">
                                <label class="form-label">Rua/Logradouro</label>
                                <input type="text" name="shipping_address[street]" id="shipping_street" class="form-input" readonly required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Número</label>
                                <input type="text" name="shipping_address[number]" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Complemento</label>
                                <input type="text" name="shipping_address[complement]" class="form-input" placeholder="Opcional">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bairro</label>
                                <input type="text" name="shipping_address[neighborhood]" id="shipping_neighborhood" class="form-input" readonly required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="shipping_address[city]" id="shipping_city" class="form-input" readonly required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="shipping_address[state]" id="shipping_state" class="form-input form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar de Resumo -->
                <div class="summary-sidebar">
                    <div class="summary-card">
                        <div class="summary-header">
                            <h3 class="summary-title">Resumo do Pedido</h3>
                            <p class="summary-subtitle">{{ count($cartItems) }} {{ count($cartItems) === 1 ? 'item' : 'itens' }}</p>
                        </div>
                        
                        <div class="cart-items">
                            @foreach($cartItems as $item)
                            <div class="cart-item">
                                <div class="item-image">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="item-details">
                                    <div class="item-title">{{ $item->book->title }}</div>
                                    <div class="item-info">Qtd: {{ $item->quantity }} × R$ {{ number_format($item->book->price, 2, ',', '.') }}</div>
                                </div>
                                <div class="item-price">
                                    R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="summary-totals">
                            <div class="total-row">
                                <span class="total-label">Subtotal</span>
                                <span class="total-value">R$ {{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                            <div class="total-row">
                                <span class="total-label">Frete</span>
                                <span class="total-value" style="color: #10b981;">Grátis</span>
                            </div>
                            <div class="total-row">
                                <span class="total-label">Desconto</span>
                                <span class="total-value">-</span>
                            </div>
                            <div class="final-total">
                                <div class="total-row">
                                    <span class="total-label">Total</span>
                                    <span class="total-value">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="finalize-button">
                            <i class="fas fa-lock mr-2"></i>
                            Finalizar Compra
                        </button>
                        
                        <div class="security-info">
                            <p class="security-text">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Compra 100% segura e protegida
                            </p>
                            <div class="security-badges">
                                <span class="security-badge">
                                    <i class="fas fa-check-circle"></i>
                                    SSL
                                </span>
                                <span class="security-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Seguro
                                </span>
                                <span class="security-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Privado
                                </span>
                            </div>
                            <p class="security-text mt-2">
                                Ao finalizar, você concorda com nossos termos de uso
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Informações -->
<div class="modal-overlay" id="paymentInfoModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-info-circle"></i>
                Informações sobre Pagamento
            </h3>
            <button type="button" class="modal-close" onclick="togglePaymentInfo()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <!-- Sistema de demonstração -->
            <div class="info-section demo">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h4 class="info-title">Sistema de Demonstração</h4>
                    </div>
                </div>
                <p class="info-text">
                    Este é um ambiente de teste. Nenhum pagamento real será processado. 
                    Use dados fictícios para experimentar o sistema.
                </p>
            </div>
            
            <!-- PIX -->
            <div class="info-section pix">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div>
                        <h4 class="info-title">Pagamento via PIX</h4>
                    </div>
                </div>
                <p class="info-text">
                    Após confirmar o pedido, você receberá um QR Code para pagamento instantâneo.
                    O PIX é rápido, seguro e disponível 24h por dia.
                </p>
                <div class="info-features">
                    <div class="feature-item">
                        <div class="feature-icon" style="color: #8b5cf6;">⚡</div>
                        <div class="feature-title">Instantâneo</div>
                        <div class="feature-desc">Pagamento imediato</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon" style="color: #10b981;">🛡️</div>
                        <div class="feature-title">Seguro</div>
                        <div class="feature-desc">Tecnologia bancária</div>
                    </div>
                </div>
            </div>
            
            <!-- Boleto -->
            <div class="info-section boleto">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <div>
                        <h4 class="info-title">Pagamento via Boleto</h4>
                    </div>
                </div>
                <p class="info-text">
                    Após confirmar o pedido, você receberá o boleto para pagamento em até 3 dias úteis.
                    Pode ser pago em qualquer banco, lotérica ou internet banking.
                </p>
                <div class="info-features">
                    <div class="feature-item">
                        <div class="feature-icon" style="color: #f59e0b;">📅</div>
                        <div class="feature-title">3 Dias Úteis</div>
                        <div class="feature-desc">Prazo para pagamento</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon" style="color: #3b82f6;">🏦</div>
                        <div class="feature-title">Qualquer Banco</div>
                        <div class="feature-desc">Lotéricas e apps</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardFields = document.getElementById('card-fields');
    const sameAddressCheckbox = document.getElementById('same-address');
    const shippingFields = document.getElementById('shipping-fields');
    const form = document.getElementById('checkout-form');
    
    // Configurar métodos de pagamento
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Remover classe active de todos os métodos
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('selected');
            });
            
            // Adicionar classe active ao método selecionado
            this.closest('.payment-method').classList.add('selected');
            
            // Mostrar/ocultar campos do cartão
            if (this.value === 'credit_card' || this.value === 'debit_card') {
                cardFields.classList.remove('hidden');
                cardFields.classList.add('animate-fade-in');
                
                // Tornar campos obrigatórios
                document.getElementById('card_name').required = true;
                document.getElementById('card_number').required = true;
                document.getElementById('card_expiry').required = true;
                document.getElementById('card_cvv').required = true;
            } else {
                cardFields.classList.add('hidden');
                cardFields.classList.remove('animate-fade-in');
                
                // Tornar campos opcionais
                document.getElementById('card_name').required = false;
                document.getElementById('card_number').required = false;
                document.getElementById('card_expiry').required = false;
                document.getElementById('card_cvv').required = false;
            }
        });
    });
    
    // Máscara para cartão de crédito
    const cardNumber = document.getElementById('card_number');
    if (cardNumber) {
        cardNumber.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            this.value = value;
        });
    }
    
    // Máscara para data de validade
    const cardExpiry = document.getElementById('card_expiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });
    }
    
    // Máscara para CVV
    const cardCvv = document.getElementById('card_cvv');
    if (cardCvv) {
        cardCvv.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }
    
    // Máscara para CEP
    const cepInputs = document.querySelectorAll('[id$="_cep"]');
    cepInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            this.value = value;
            
            // Buscar endereço quando CEP estiver completo
            if (value.length === 9) {
                buscarCEP(this);
            }
        });
    });
    
    // Função para buscar CEP
    async function buscarCEP(input) {
        const cep = input.value.replace(/\D/g, '');
        const isShipping = input.id.includes('shipping');
        const prefix = isShipping ? 'shipping' : 'billing';
        
        if (cep.length === 8) {
            const loadingElement = document.getElementById(`${prefix}_cep_loading`);
            if (loadingElement) {
                loadingElement.classList.remove('hidden');
            }
            
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (!data.erro) {
                    document.getElementById(`${prefix}_street`).value = data.logradouro || '';
                    document.getElementById(`${prefix}_neighborhood`).value = data.bairro || '';
                    document.getElementById(`${prefix}_city`).value = data.localidade || '';
                    document.getElementById(`${prefix}_state`).value = data.uf || '';
                }
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
            } finally {
                if (loadingElement) {
                    loadingElement.classList.add('hidden');
                }
            }
        }
    }
    
    // Checkbox "mesmo endereço"
    sameAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Copiar dados do endereço de cobrança para entrega
            const billingFields = {
                postal_code: document.querySelector('[name="billing_address[postal_code]"]').value,
                street: document.querySelector('[name="billing_address[street]"]').value,
                number: document.querySelector('[name="billing_address[number]"]').value,
                complement: document.querySelector('[name="billing_address[complement]"]').value,
                neighborhood: document.querySelector('[name="billing_address[neighborhood]"]').value,
                city: document.querySelector('[name="billing_address[city]"]').value,
                state: document.querySelector('[name="billing_address[state]"]').value
            };
            
            Object.keys(billingFields).forEach(field => {
                const shippingField = document.querySelector(`[name="shipping_address[${field}]"]`);
                if (shippingField && billingFields[field]) {
                    shippingField.value = billingFields[field];
                }
            });
            
            // Desabilitar campos de entrega
            shippingFields.style.opacity = '0.6';
            shippingFields.style.pointerEvents = 'none';
        } else {
            // Habilitar campos de entrega
            shippingFields.style.opacity = '1';
            shippingFields.style.pointerEvents = 'auto';
        }
    });
    
    // Animações de entrada
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observar elementos para animação
    document.querySelectorAll('.animate-slide-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
    
    // Validação do formulário
    form.addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!paymentMethod) {
            e.preventDefault();
            alert('Por favor, selecione um método de pagamento.');
            return;
        }
        
        // Validação específica para cartão
        if ((paymentMethod.value === 'credit_card' || paymentMethod.value === 'debit_card')) {
            const cardName = document.getElementById('card_name').value.trim();
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            const cardExpiry = document.getElementById('card_expiry').value;
            const cardCvv = document.getElementById('card_cvv').value;
            
            if (!cardName || !cardNumber || !cardExpiry || !cardCvv) {
                e.preventDefault();
                alert('Por favor, preencha todos os dados do cartão.');
                return;
            }
            
            if (cardNumber.length < 13) {
                e.preventDefault();
                alert('Número do cartão inválido.');
                return;
            }
            
            if (cardExpiry.length < 5) {
                e.preventDefault();
                alert('Data de validade inválida.');
                return;
            }
            
            if (cardCvv.length < 3) {
                e.preventDefault();
                alert('CVV inválido.');
                return;
            }
        }
        
        // Mostrar loading
        const submitButton = document.querySelector('.finalize-button');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
        submitButton.disabled = true;
        
        // Simular processamento (remover em produção)
        setTimeout(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 3000);
    });
});

// Função para toggle do modal de informações
function togglePaymentInfo() {
    const modal = document.getElementById('paymentInfoModal');
    if (modal.style.display === 'flex') {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    } else {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Fechar modal ao clicar fora
document.getElementById('paymentInfoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        togglePaymentInfo();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('paymentInfoModal');
        if (modal.style.display === 'flex') {
            togglePaymentInfo();
        }
    }
});
</script>

@endsection