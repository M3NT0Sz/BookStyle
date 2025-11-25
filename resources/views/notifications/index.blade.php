@extends('layouts.app')

@section('content')
<style>
    /* Animações */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .notification-item {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }

    .notification-item:nth-child(1) { animation-delay: 0.1s; }
    .notification-item:nth-child(2) { animation-delay: 0.15s; }
    .notification-item:nth-child(3) { animation-delay: 0.2s; }
    .notification-item:nth-child(4) { animation-delay: 0.25s; }
    .notification-item:nth-child(5) { animation-delay: 0.3s; }

    .notification-badge {
        animation: pulse 2s infinite;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 24px;
        flex-shrink: 0;
    }

    .notification-type-order_created .notification-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .notification-type-order_status .notification-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .notification-type-coupon_available .notification-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .notification-type-review_received .notification-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .notification-type-request_review .notification-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .notification-type-default .notification-icon {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }

    .action-button {
        transition: all 0.3s ease;
        border-radius: 8px;
        padding: 8px 12px;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .empty-state {
        animation: fadeInUp 0.8s ease;
    }

    .notification-card {
        transition: all 0.3s ease;
    }

    .notification-card:hover {
        transform: translateX(8px);
    }

    .coupon-code {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        display: inline-block;
        letter-spacing: 2px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
</style>

<div style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem 0;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 1rem;">
        <!-- Header -->
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <a href="{{ route('index') }}" style="color: white; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 1rem; opacity: 0.9; transition: opacity 0.3s;">
                        <i class="fas fa-arrow-left"></i>
                        <span>Voltar</span>
                    </a>
                    <h1 style="font-size: 2.5rem; font-weight: bold; margin: 0; display: flex; align-items: center; gap: 1rem;">
                        <i class="fas fa-bell" style="animation: pulse 2s infinite;"></i>
                        Notificações
                    </h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 1.1rem;">
                        @if($unreadCount > 0)
                            Você tem <strong>{{ $unreadCount }}</strong> {{ $unreadCount == 1 ? 'notificação não lida' : 'notificações não lidas' }}
                        @else
                            Todas as notificações estão em dia
                        @endif
                    </p>
                </div>
                
                @if($notifications->total() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="action-button" style="background: white; color: #667eea; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 1rem;">
                            <i class="fas fa-check-double"></i>
                            Marcar todas como lidas
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($notifications as $notification)
                    @php
                        $borderColor = $notification->is_read ? '#cbd5e0' : '#667eea';
                    @endphp
                    <div class="notification-item notification-card notification-type-{{ $notification->type ?? 'default' }}" 
                         style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); border-left: 5px solid {{ $borderColor }}; position: relative;">
                        
                        @if(!$notification->is_read)
                            <div class="notification-badge" style="position: absolute; top: -8px; right: -8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 0.75rem; padding: 4px 12px; border-radius: 12px; font-weight: bold; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);">
                                NOVO
                            </div>
                        @endif

                        <div style="display: flex; gap: 1.5rem; align-items: start;">
                            <!-- Icon -->
                            <div class="notification-icon">
                                @switch($notification->type)
                                    @case('order_created')
                                        <i class="fas fa-shopping-cart" style="color: white;"></i>
                                        @break
                                    @case('order_status')
                                        <i class="fas fa-truck" style="color: white;"></i>
                                        @break
                                    @case('coupon_available')
                                        <i class="fas fa-gift" style="color: white;"></i>
                                        @break
                                    @case('review_received')
                                        <i class="fas fa-star" style="color: white;"></i>
                                        @break
                                    @case('request_review')
                                        <i class="fas fa-comment-dots" style="color: white;"></i>
                                        @break
                                    @default
                                        <i class="fas fa-envelope" style="color: white;"></i>
                                @endswitch
                            </div>

                            <!-- Content -->
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #2d3748; margin: 0;">
                                        {{ $notification->title }}
                                    </h3>
                                    <span style="color: #a0aec0; font-size: 0.875rem; white-space: nowrap; margin-left: 1rem;">
                                        <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <p style="color: #4a5568; font-size: 1rem; line-height: 1.6; margin: 0.5rem 0 1rem 0;">
                                    {{ $notification->message }}
                                </p>
                                
                                @if($notification->data)
                                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
                                        @if(isset($notification->data['order_id']))
                                            <a href="{{ route('orders.show', $notification->data['order_id']) }}" 
                                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                                                <i class="fas fa-eye"></i>
                                                Ver pedido
                                            </a>
                                        @endif
                                        
                                        @if(isset($notification->data['book_id']))
                                            <a href="{{ route('books.show', $notification->data['book_id']) }}" 
                                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                                                <i class="fas fa-book"></i>
                                                Ver livro
                                            </a>
                                        @endif
                                        
                                        @if(isset($notification->data['coupon_code']))
                                            <div class="coupon-code">
                                                <i class="fas fa-ticket-alt"></i>
                                                {{ $notification->data['coupon_code'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="action-button"
                                                style="background: #48bb78; color: white; border: none; cursor: pointer; font-size: 1rem;"
                                                title="Marcar como lida">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button"
                                            style="background: #f56565; color: white; border: none; cursor: pointer; font-size: 1rem;"
                                            title="Excluir notificação"
                                            onclick="return confirm('Deseja excluir esta notificação?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div style="margin-top: 2rem;">
                {{ $notifications->links() }}
            </div>
            
            <!-- Clear Read Button -->
            @if($notifications->where('is_read', true)->count() > 0)
                <div style="margin-top: 2rem; text-align: center;">
                    <form action="{{ route('notifications.clearRead') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="action-button"
                                style="background: white; color: #e53e3e; border: 2px solid #e53e3e; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;"
                                onclick="return confirm('Deseja remover todas as notificações lidas?')">
                            <i class="fas fa-trash-alt"></i>
                            Limpar notificações lidas
                        </button>
                    </form>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state" style="background: white; border-radius: 20px; padding: 4rem 2rem; text-align: center; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
                <div style="width: 120px; height: 120px; margin: 0 auto 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
                    <i class="fas fa-bell-slash" style="font-size: 3rem; color: white;"></i>
                </div>
                <h3 style="font-size: 1.75rem; font-weight: bold; color: #2d3748; margin: 0 0 1rem 0;">
                    Nenhuma notificação
                </h3>
                <p style="color: #718096; font-size: 1.1rem; margin: 0 0 2rem 0;">
                    Você está em dia! Não há notificações no momento.
                </p>
                <a href="{{ route('index') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                    <i class="fas fa-home"></i>
                    Voltar para a página inicial
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
