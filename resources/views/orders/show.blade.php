@extends('layouts.app')

@section('title', 'Pedido #' . $order->order_number . ' - BookStyle')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header com breadcrumb -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <a href="{{ route('orders.index') }}" class="hover:text-blue-600">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar para Meus Pedidos
                    </a>
                </div>
                
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Pedido #{{ $order->order_number }}
                        </h1>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                <i class="fas fa-circle mr-2 text-xs"></i>
                                {{ $order->status_label }}
                            </span>
                            <span class="text-gray-600">
                                Pedido realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    @if($order->canBeCancelled())
                        <div class="mt-4 lg:mt-0">
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar Pedido
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
                    <li class="inline-flex items-center">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Meus Pedidos
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Pedido #{{ $order->order_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header do pedido -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Pedido #{{ $order->order_number }}
                        </h1>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $order->status_label }}
                            </span>
                            <span class="text-sm text-gray-600">
                                Pedido realizado em {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4 lg:mt-0">
                        @if($order->canBeCancelled())
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar Pedido
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Itens do pedido -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                        
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                <!-- Aqui você pode adicionar uma imagem do livro se tiver -->
                                <div class="flex-shrink-0 w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item->book->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->book->author }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Quantidade: {{ $item->quantity }}
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($item->price, 2, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Subtotal: R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Resumo e informações -->
                <div class="space-y-6">
                    <!-- Resumo do pedido -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $order->orderItems->sum('quantity') }} itens):</span>
                                <span class="text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                            </div>
                            <!-- Aqui você pode adicionar frete, desconto, etc. -->
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between font-semibold">
                                    <span>Total:</span>
                                    <span class="text-lg">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de pagamento -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pagamento</h2>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Método:</span>
                                <span class="text-gray-900">{{ $order->payment_method }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $order->payment_status === 'paid' ? 'Pago' : 'Pendente' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Endereços -->
                    @if($order->shipping_address || $order->billing_address)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Endereços</h2>
                        
                        @if($order->shipping_address)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Endereço de Entrega</h3>
                            <div class="text-sm text-gray-600">
                                @if(is_array($order->shipping_address))
                                    <div>{{ $order->shipping_address['street'] ?? '' }}</div>
                                    <div>{{ $order->shipping_address['city'] ?? '' }} - {{ $order->shipping_address['state'] ?? '' }}</div>
                                    <div>CEP: {{ $order->shipping_address['postal_code'] ?? '' }}</div>
                                @else
                                    {{ $order->shipping_address }}
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Endereço de Cobrança</h3>
                            <div class="text-sm text-gray-600">
                                @if(is_array($order->billing_address))
                                    <div>{{ $order->billing_address['street'] ?? '' }}</div>
                                    <div>{{ $order->billing_address['city'] ?? '' }} - {{ $order->billing_address['state'] ?? '' }}</div>
                                    <div>CEP: {{ $order->billing_address['postal_code'] ?? '' }}</div>
                                @else
                                    {{ $order->billing_address }}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Timeline do pedido -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Status do Pedido</h2>
                        
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Pedido criado -->
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Pedido realizado</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                                        @endif
                                    </div>
                                </li>

                                <!-- Processando -->
                                @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-cog text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Em processamento</p>
                                                </div>
                                            </div>
                                        </div>
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                                        @endif
                                    </div>
                                </li>
                                @endif

                                <!-- Enviado -->
                                @if(in_array($order->status, ['shipped', 'delivered']) && $order->shipped_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-truck text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Pedido enviado</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $order->shipped_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($order->status === 'delivered')
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                                        @endif
                                    </div>
                                </li>
                                @endif

                                <!-- Entregue -->
                                @if($order->status === 'delivered' && $order->delivered_at)
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-home text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Pedido entregue</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $order->delivered_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Cancelado -->
                                @if($order->status === 'cancelled')
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-times text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Pedido cancelado</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertas -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
</div>
@endif

<script>
// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.fixed.bottom-4.right-4');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endsection