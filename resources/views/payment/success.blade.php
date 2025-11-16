@extends('layouts.app')

@section('content')
    @include('components.nav_bar')

    <div class="payment-result-container">
        <div class="payment-result-card success">
            <div class="icon-wrapper">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Pagamento Aprovado!</h1>
            <p class="subtitle">Seu pedido foi confirmado com sucesso</p>
            
            <div class="order-info">
                <div class="info-item">
                    <span class="label">Número do Pedido:</span>
                    <span class="value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="info-item">
                    <span class="label">Valor Total:</span>
                    <span class="value">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                </div>
                
                @if($order->payment_id)
                <div class="info-item">
                    <span class="label">ID do Pagamento:</span>
                    <span class="value">{{ $order->payment_id }}</span>
                </div>
                @endif
                
                <div class="info-item">
                    <span class="label">Status:</span>
                    <span class="badge badge-success">Pago</span>
                </div>
            </div>
            
            <div class="next-steps">
                <h3>Próximos Passos:</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Você receberá um e-mail com a confirmação do pedido</li>
                    <li><i class="fas fa-check"></i> Acompanhe o status do seu pedido na área de pedidos</li>
                    <li><i class="fas fa-check"></i> Você será notificado quando o pedido for enviado</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    Ver Detalhes do Pedido
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #10b981;
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

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .next-steps {
            text-align: left;
            margin-bottom: 2rem;
        }

        .next-steps h3 {
            color: #1f2937;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .next-steps ul {
            list-style: none;
            padding: 0;
        }

        .next-steps li {
            color: #4b5563;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .next-steps li i {
            color: #10b981;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
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
