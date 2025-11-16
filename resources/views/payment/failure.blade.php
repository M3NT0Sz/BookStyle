@extends('layouts.app')

@section('content')
    @include('components.nav_bar')

    <div class="payment-result-container failure">
        <div class="payment-result-card">
            <div class="icon-wrapper">
                <i class="fas fa-times-circle"></i>
            </div>
            
            <h1>Pagamento Não Aprovado</h1>
            <p class="subtitle">Não foi possível processar seu pagamento</p>
            
            <div class="order-info">
                <div class="info-item">
                    <span class="label">Número do Pedido:</span>
                    <span class="value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Valor Total:</span>
                    <span class="value">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Status:</span>
                    <span class="badge badge-error">Falha no Pagamento</span>
                </div>
            </div>
            
            <div class="help-box">
                <h3>O que fazer agora?</h3>
                <ul>
                    <li><i class="fas fa-info-circle"></i> Verifique se os dados do cartão estão corretos</li>
                    <li><i class="fas fa-info-circle"></i> Confirme se há saldo/limite disponível</li>
                    <li><i class="fas fa-info-circle"></i> Tente outro método de pagamento</li>
                    <li><i class="fas fa-info-circle"></i> Entre em contato com seu banco se o problema persistir</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('payment.checkout', $order->id) }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i>
                    Tentar Novamente
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    Ver Meus Pedidos
                </a>
            </div>
        </div>
    </div>

    <style>
        .payment-result-container {
            min-height: calc(100vh - 180px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .payment-result-container.failure {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .payment-result-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .icon-wrapper {
            margin-bottom: 1.5rem;
        }

        .icon-wrapper i {
            font-size: 5rem;
            color: #ef4444;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .payment-result-card h1 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .order-info {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item .label {
            color: #6b7280;
            font-weight: 500;
        }

        .info-item .value {
            color: #1f2937;
            font-weight: 600;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .help-box {
            text-align: left;
            margin-bottom: 2rem;
            background: #fef3c7;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #f59e0b;
        }

        .help-box h3 {
            color: #92400e;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .help-box ul {
            list-style: none;
            padding: 0;
        }

        .help-box li {
            color: #78350f;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .help-box li i {
            color: #f59e0b;
            font-size: 1.1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        .btn-secondary:hover {
            background: #ef4444;
            color: white;
        }

        @media (max-width: 768px) {
            .payment-result-card {
                padding: 2rem;
            }

            .payment-result-card h1 {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    @include('components.footer')
@endsection
