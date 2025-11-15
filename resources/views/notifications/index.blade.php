@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                🔔 Minhas Notificações
            </h1>
            
            @if($notifications->total() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                        ✓ Marcar todas como lidas
                    </button>
                </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $notification->is_read ? 'border-gray-300 opacity-75' : 'border-blue-500' }} hover:shadow-lg transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-2xl">
                                        @switch($notification->type)
                                            @case('order_created')
                                                🎉
                                                @break
                                            @case('order_status')
                                                📦
                                                @break
                                            @case('coupon_available')
                                                🎁
                                                @break
                                            @case('review_received')
                                                ⭐
                                                @break
                                            @case('request_review')
                                                📝
                                                @break
                                            @default
                                                📬
                                        @endswitch
                                    </span>
                                    
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ $notification->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    @if(!$notification->is_read)
                                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                            Novo
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700 ml-11">
                                    {{ $notification->message }}
                                </p>
                                
                                @if($notification->data)
                                    <div class="mt-3 ml-11 text-sm text-gray-600">
                                        @if(isset($notification->data['order_id']))
                                            <a href="{{ route('orders.show', $notification->data['order_id']) }}" 
                                               class="text-blue-500 hover:text-blue-700 underline">
                                                Ver pedido →
                                            </a>
                                        @endif
                                        
                                        @if(isset($notification->data['book_id']))
                                            <a href="{{ route('books.show', $notification->data['book_id']) }}" 
                                               class="text-blue-500 hover:text-blue-700 underline">
                                                Ver livro →
                                            </a>
                                        @endif
                                        
                                        @if(isset($notification->data['coupon_code']))
                                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded font-mono font-bold">
                                                {{ $notification->data['coupon_code'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex gap-2 ml-4">
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="text-blue-500 hover:text-blue-700 p-2"
                                                title="Marcar como lida">
                                            ✓
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-700 p-2"
                                            title="Excluir notificação"
                                            onclick="return confirm('Deseja excluir esta notificação?')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
            
            @if($notifications->where('is_read', true)->count() > 0)
                <div class="mt-6 text-center">
                    <form action="{{ route('notifications.clearRead') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-gray-500 hover:text-gray-700 underline"
                                onclick="return confirm('Deseja remover todas as notificações lidas?')">
                            🗑️ Limpar notificações lidas
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <span class="text-6xl mb-4 block">🔕</span>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    Nenhuma notificação
                </h3>
                <p class="text-gray-600">
                    Você está em dia! Não há notificações no momento.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
