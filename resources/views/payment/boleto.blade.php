@extends('layouts.app')

@section('title', 'Pagamento via Boleto - BookStyle')

@section('content')
<style>
.boleto-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.boleto-card {
    max-width: 700px;
    margin: 0 auto;
    background: white;
    border-radius: 24px;
    padding: 3rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.boleto-header {
    text-align: center;
    margin-bottom: 2rem;
}

.boleto-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.boleto-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.boleto-subtitle {
    color: #64748b;
    font-size: 1.1rem;
}

.boleto-info {
    background: #f8fafc;
    border-radius: 16px;
    padding: 2rem;
    margin: 2rem 0;
}

.boleto-info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.boleto-info-row:last-child {
    border-bottom: none;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.barcode-box {
    background: white;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 2rem 0;
    text-align: center;
}

.barcode-number {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    color: #1e293b;
    font-weight: 600;
    letter-spacing: 2px;
    margin: 1rem 0;
    padding: 1rem;
    background: #f1f5f9;
    border-radius: 8px;
}

.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin: 2rem 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 1rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: #e2e8f0;
    color: #475569;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 1rem;
}

.btn-secondary:hover {
    background: #cbd5e1;
    color: #1e293b;
}

.instructions {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 2rem 0;
}

.instructions h3 {
    color: #92400e;
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.instructions ol {
    margin: 0;
    padding-left: 1.5rem;
}

.instructions li {
    color: #78350f;
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #f59e0b;
    margin: 1rem 0;
}

@media (max-width: 640px) {
    .boleto-card {
        padding: 2rem 1.5rem;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .barcode-number {
        font-size: 0.9rem;
        letter-spacing: 1px;
    }
}
</style>

<div class="boleto-container">
    <div class="boleto-card">
        <div class="boleto-header">
            <div class="boleto-icon">
                <i class="fas fa-barcode"></i>
            </div>
            <h1 class="boleto-title">Pagamento via Boleto</h1>
            <p class="boleto-subtitle">Seu boleto foi gerado com sucesso!</p>
        </div>

        @if($boletoData && isset($boletoData['ticket_url']))
        <div class="instructions">
            <h3><i class="fas fa-info-circle"></i> Informações Importantes</h3>
            <ol>
                <li>O boleto tem validade de 3 dias úteis</li>
                <li>Após o pagamento, pode levar até 2 dias úteis para compensar</li>
                <li>Você será notificado quando o pagamento for confirmado</li>
                <li>Guarde o comprovante de pagamento</li>
            </ol>
        </div>

        @if(isset($boletoData['barcode']))
        <div class="barcode-box">
            <div style="color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">
                <i class="fas fa-barcode"></i> Código de Barras
            </div>
            <div class="barcode-number">{{ $boletoData['barcode'] }}</div>
        </div>
        @endif

        <div class="action-buttons">
            <a href="{{ $boletoData['ticket_url'] }}" target="_blank" class="btn-primary">
                <i class="fas fa-download"></i>
                Baixar Boleto
            </a>
            <a href="{{ $boletoData['ticket_url'] }}" target="_blank" class="btn-secondary">
                <i class="fas fa-print"></i>
                Imprimir Boleto
            </a>
        </div>
        @else
        <div class="alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Atenção!</strong> Boleto indisponível no momento em ambiente de teste. 
            Em produção, você receberá o link para pagamento.
        </div>
        
        <div style="text-align: center; margin: 2rem 0;">
            <p style="color: #64748b; margin-bottom: 1rem;">
                Para testar pagamentos, use os cartões de teste ou PIX.
            </p>
        </div>
        @endif

        <div class="boleto-info">
            <div class="boleto-info-row">
                <span style="color: #64748b;">Pedido</span>
                <span style="font-weight: 600;">#{{ $order->order_number }}</span>
            </div>
            <div class="boleto-info-row">
                <span style="color: #64748b;">Status</span>
                <span style="color: #f59e0b; font-weight: 600;">
                    <i class="fas fa-clock"></i> Aguardando Pagamento
                </span>
            </div>
            <div class="boleto-info-row">
                <span>Total a Pagar</span>
                <span>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('orders.show', $order->id) }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Ver Detalhes do Pedido
            </a>
        </div>
    </div>
</div>

<script>
// Verificar status do pagamento a cada 10 segundos
setInterval(() => {
    fetch(`/payment/status/{{ $order->id }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.order.payment_status === 'paid') {
                window.location.href = '{{ route("payment.success", ["order" => $order->id]) }}';
            }
        })
        .catch(err => console.error('Erro ao verificar status:', err));
}, 10000);
</script>
@endsection
