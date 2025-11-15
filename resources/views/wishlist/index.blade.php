@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    ❤️ Minha Lista de Desejos
                </h1>
                <p class="text-gray-600 mt-2">
                    {{ $wishlistItems->count() }} {{ $wishlistItems->count() === 1 ? 'livro' : 'livros' }} salvos
                </p>
            </div>
        </div>

        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($wishlistItems as $item)
                    @php
                        $book = $item->book;
                        $images = is_string($book->images) ? json_decode($book->images, true) : $book->images;
                        $firstImage = is_array($images) && !empty($images) ? $images[0] : 'default.jpg';
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $firstImage) }}" 
                                 alt="{{ $book->name }}" 
                                 class="w-full h-64 object-cover"
                                 onerror="this.src='{{ Vite::asset('resources/img/default-book.jpg') }}'">
                            
                            <button onclick="removeFromWishlist({{ $book->id }})" 
                                    class="absolute top-3 right-3 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition shadow-lg"
                                    title="Remover dos favoritos">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2l-6 6 6 6v-4h14v-4h-14v-4z"/>
                                </svg>
                            </button>
                            
                            @if($item->price_alert && $book->price <= $item->price_alert)
                                <div class="absolute top-3 left-3 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                    🎉 Preço Baixou!
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-5">
                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">
                                {{ $book->name }}
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-semibold">Autor:</span> {{ $book->author }}
                            </p>
                            
                            <p class="text-sm text-gray-600 mb-3">
                                <span class="font-semibold">Gênero:</span> {{ $book->genre }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-green-600">
                                    R$ {{ number_format($book->price, 2, ',', '.') }}
                                </span>
                                
                                @if($item->price_alert)
                                    <span class="text-xs text-gray-500">
                                        Alerta: R$ {{ number_format($item->price_alert, 2, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                                    🔔 Alerta de Preço (opcional)
                                </label>
                                <div class="flex gap-2">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0"
                                           value="{{ $item->price_alert }}"
                                           placeholder="Ex: 25.00"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           id="price-alert-{{ $book->id }}">
                                    <button onclick="updatePriceAlert({{ $book->id }})"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                        Salvar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('books.show', $book->id) }}" 
                                   class="flex-1 bg-blue-500 text-white text-center py-2 rounded-lg hover:bg-blue-600 transition">
                                    Ver Detalhes
                                </a>
                                <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition">
                                        🛒 Adicionar
                                    </button>
                                </form>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-3 text-center">
                                Adicionado {{ $item->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center mb-12">
                <span class="text-6xl mb-4 block">💔</span>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">
                    Sua lista de desejos está vazia
                </h3>
                <p class="text-gray-600 mb-6">
                    Explore nosso catálogo e adicione seus livros favoritos aqui!
                </p>
                <a href="{{ route('books.index') }}" 
                   class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                    🔍 Explorar Livros
                </a>
            </div>
        @endif

        @if(count($recommendations) > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    💡 Recomendações para Você
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendations as $book)
                        @php
                            $images = is_string($book['images']) ? json_decode($book['images'], true) : $book['images'];
                            $firstImage = is_array($images) && !empty($images) ? $images[0] : 'default.jpg';
                        @endphp
                        
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            <img src="{{ asset('storage/' . $firstImage) }}" 
                                 alt="{{ $book['name'] }}" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.src='{{ Vite::asset('resources/img/default-book.jpg') }}'">
                            
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">
                                    {{ $book['name'] }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-3">{{ $book['author'] }}</p>
                                <p class="text-xl font-bold text-green-600 mb-4">
                                    R$ {{ number_format($book['price'], 2, ',', '.') }}
                                </p>
                                <a href="{{ route('books.show', $book['id']) }}" 
                                   class="block w-full bg-blue-500 text-white text-center py-2 rounded-lg hover:bg-blue-600 transition">
                                    Ver Livro
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function removeFromWishlist(bookId) {
        if (!confirm('Deseja remover este livro da sua lista de desejos?')) return;
        
        fetch(`/wishlist/${bookId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao remover item');
        });
    }

    function updatePriceAlert(bookId) {
        const priceInput = document.getElementById(`price-alert-${bookId}`);
        const priceAlert = priceInput.value;
        
        fetch(`/wishlist/${bookId}/price-alert`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ price_alert: priceAlert || null })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao atualizar alerta');
        });
    }
</script>

@endsection
