@extends('layouts.app')

@section('title', 'Pagamento via PIX - BookStyle')

@section('content')
<style>
.pix-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.pix-card {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    border-radius: 24px;
    padding: 3rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.pix-header {
    text-align: center;
    margin-bottom: 2rem;
}

.pix-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #32BCAD, #2C9D8E);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.pix-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.pix-subtitle {
    color: #64748b;
    font-size: 1.1rem;
}

.qr-code-container {
    background: #f8fafc;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    margin: 2rem 0;
}

.qr-code-image {
    max-width: 300px;
    width: 100%;
    height: auto;
    margin: 0 auto;
    border: 4px solid white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pix-code-box {
    background: white;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 2rem 0;
    position: relative;
}

.pix-code-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.pix-code-text {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: #1e293b;
    word-break: break-all;
    line-height: 1.6;
    padding: 1rem;
    background: #f1f5f9;
    border-radius: 8px;
}

.copy-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 1rem;
    width: 100%;
    justify-content: center;
    font-size: 1rem;
}

.copy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
}

.copy-btn.copied {
    background: linear-gradient(135deg, #10b981, #059669);
}

.order-info {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.order-info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.order-info-row:last-child {
    border-bottom: none;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #e2e8f0;
    color: #475569;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    margin-top: 1rem;
}

.back-btn:hover {
    background: #cbd5e1;
    color: #1e293b;
}

.instructions {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 2rem 0;
}

.instructions h3 {
    color: #1e40af;
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.instructions ol {
    margin: 0;
    padding-left: 1.5rem;
}

.instructions li {
    color: #475569;
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

@media (max-width: 640px) {
    .pix-card {
        padding: 2rem 1.5rem;
    }
    
    .qr-code-image {
        max-width: 250px;
    }
}
</style>

<div class="pix-container">
    <div class="pix-card">
        <div class="pix-header">
            <div class="pix-icon">
                <i class="fas fa-qrcode"></i>
            </div>
            <h1 class="pix-title">Pagamento via PIX</h1>
            <p class="pix-subtitle">Escaneie o QR Code ou copie o código</p>
        </div>

        @if($pixData && isset($pixData['qr_code_base64']))
        <div class="qr-code-container">
            <img src="data:image/png;base64,{{ $pixData['qr_code_base64'] }}" alt="QR Code PIX" class="qr-code-image">
            <p style="color: #64748b; margin-top: 1rem; font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> Abra o app do seu banco e escaneie o código
            </p>
        </div>

        @if(isset($pixData['qr_code']))
        <div class="pix-code-box">
            <div class="pix-code-label">
                <i class="fas fa-copy"></i> Código PIX Copia e Cola
            </div>
            <div class="pix-code-text" id="pixCode">{{ $pixData['qr_code'] }}</div>
            <button class="copy-btn" onclick="copyPixCode()">
                <i class="fas fa-copy"></i>
                <span id="copyText">Copiar Código PIX</span>
            </button>
        </div>
        @endif

        <div class="instructions">
            <h3><i class="fas fa-check-circle"></i> Como pagar</h3>
            <ol>
                <li>Abra o aplicativo do seu banco</li>
                <li>Escolha a opção "Pagar com PIX"</li>
                <li>Escaneie o QR Code ou cole o código</li>
                <li>Confirme o pagamento</li>
                <li>Pronto! Seu pedido será processado automaticamente</li>
            </ol>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Não foi possível gerar o QR Code PIX. Tente novamente.
        </div>
        @endif

        <div class="order-info">
            <div class="order-info-row">
                <span style="color: #64748b;">Pedido</span>
                <span style="font-weight: 600;">#{{ $order->order_number }}</span>
            </div>
            <div class="order-info-row">
                <span style="color: #64748b;">Status</span>
                <span style="color: #f59e0b; font-weight: 600;">
                    <i class="fas fa-clock"></i> Aguardando Pagamento
                </span>
            </div>
            <div class="order-info-row">
                <span>Total a Pagar</span>
                <span>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('orders.show', $order->id) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Ver Detalhes do Pedido
            </a>
        </div>
    </div>
</div>

<script>
function copyPixCode() {
    const pixCode = document.getElementById('pixCode').textContent;
    const copyBtn = document.querySelector('.copy-btn');
    const copyText = document.getElementById('copyText');
    
    navigator.clipboard.writeText(pixCode).then(() => {
        copyBtn.classList.add('copied');
        copyText.innerHTML = '<i class="fas fa-check"></i> Código Copiado!';
        
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyText.innerHTML = 'Copiar Código PIX';
        }, 3000);
    }).catch(err => {
        alert('Erro ao copiar código. Tente copiar manualmente.');
        console.error('Erro ao copiar:', err);
    });
}

// Verificar status do pagamento a cada 5 segundos
setInterval(() => {
    fetch(`/payment/status/{{ $order->id }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.order.payment_status === 'paid') {
                window.location.href = '{{ route("payment.success", ["order" => $order->id]) }}';
            }
        })
        .catch(err => console.error('Erro ao verificar status:', err));
}, 5000);
</script>
@endsection
