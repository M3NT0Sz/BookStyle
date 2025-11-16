@extends('layouts.app')

@section('content')
    @include('components.nav_bar')

    <div class="payment-result-container pending">
        <div class="payment-result-card">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            
            <h1>Pagamento Pendente</h1>
            <p class="subtitle">Aguardando confirmação do pagamento</p>
            
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
                    <span class="badge badge-pending">Pagamento Pendente</span>
                </div>
            </div>
            
            <div class="info-box">
                <h3>O que significa?</h3>
                <p>Seu pagamento está sendo processado. Isso pode acontecer quando:</p>
                <ul>
                    <li><i class="fas fa-info-circle"></i> Você escolheu boleto bancário (aguardando pagamento)</li>
                    <li><i class="fas fa-info-circle"></i> O pagamento está em análise pela operadora</li>
                    <li><i class="fas fa-info-circle"></i> Pix pendente de confirmação</li>
                </ul>
                <p class="note">Você receberá um e-mail assim que o pagamento for confirmado.</p>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    Acompanhar Pedido
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-book"></i>
                    Continuar Comprando
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

        .payment-result-container.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            color: #f59e0b;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
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

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .info-box {
            text-align: left;
            margin-bottom: 2rem;
            background: #eff6ff;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
        }

        .info-box h3 {
            color: #1e40af;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .info-box p {
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
            margin-bottom: 1rem;
        }

        .info-box li {
            color: #1e3a8a;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-box li i {
            color: #3b82f6;
            font-size: 1.1rem;
        }

        .info-box .note {
            font-weight: 600;
            color: #1e40af;
            margin: 0;
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #f59e0b;
            border: 2px solid #f59e0b;
        }

        .btn-secondary:hover {
            background: #f59e0b;
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
