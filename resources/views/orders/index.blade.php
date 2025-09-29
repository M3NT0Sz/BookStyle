@extends('layouts.app')

@section('title', 'Meus Pedidos - BookStyle')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header/Navigation aqui se necessário -->
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header da página -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Meus Pedidos</h1>
                
                <!-- Filtros e busca -->
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <!-- Filtro por status -->
                    <form method="GET" action="{{ route('orders.filter') }}" class="flex-1">
                        <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Todos os Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processando</option>
                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Enviado</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregue</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </form>
                    
                    <!-- Busca por número do pedido -->
                    <form method="GET" action="{{ route('orders.search') }}" class="flex-1">
                        <div class="flex">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Buscar por número do pedido..." 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de pedidos -->
            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-2">
                                        <h3 class="font-semibold text-gray-900">
                                            Pedido #{{ $order->order_number }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 mb-2">
                                        <span>Data: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $order->orderItems->count() }} {{ $order->orderItems->count() === 1 ? 'item' : 'itens' }}</span>
                                        @if($order->shipped_at)
                                            <span class="mx-2">•</span>
                                            <span>Enviado em: {{ $order->shipped_at->format('d/m/Y') }}</span>
                                        @endif
                                        @if($order->delivered_at)
                                            <span class="mx-2">•</span>
                                            <span>Entregue em: {{ $order->delivered_at->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Prévia dos itens -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($order->orderItems->take(3) as $item)
                                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                {{ $item->book->title }} ({{ $item->quantity }}x)
                                            </span>
                                        @endforeach
                                        @if($order->orderItems->count() > 3)
                                            <span class="text-xs text-gray-500">+{{ $order->orderItems->count() - 3 }} mais</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-col lg:items-end gap-3">
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            R$ {{ number_format($order->total, 2, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ $order->payment_method }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detalhes
                                        </a>
                                        
                                        @if($order->canBeCancelled())
                                            <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                                        class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-8">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <i class="fas fa-shopping-bag text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum pedido encontrado</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search') || request('status'))
                            Não encontramos pedidos com os filtros selecionados.
                        @else
                            Você ainda não fez nenhum pedido.
                        @endif
                    </p>
                    <a href="{{ route('books.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-book mr-2"></i>
                        Explorar Livros
                    </a>
                </div>
            @endif
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