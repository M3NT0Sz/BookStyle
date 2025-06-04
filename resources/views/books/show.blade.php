@extends('layouts.app')

@section('content')
    @vite('resources/css/bookShow.css')
    @include('components.nav_bar')
    
    @if(session('success'))
        <div class="alert alert-success" style="margin: 1em 0; color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 0.75em; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" style="margin: 1em 0; color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; padding: 0.75em; border-radius: 4px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="book-product-hero">
        <div class="book-product-imgbox">
            @php
                // Garante que $images sempre será um array de strings válidas
                $images = is_array($book)
                    ? (isset($book['images']) ? (is_array($book['images']) ? $book['images'] : json_decode($book['images'], true)) : [])
                    : (isset($book->images) ? (is_array($book->images) ? $book->images : json_decode($book->images, true)) : []);
                if (!is_array($images)) $images = [];
                // Remove valores vazios e normaliza caminhos
                $images = array_filter(array_map(function($img) {
                    return trim($img);
                }, $images), fn($img) => !empty($img));
            @endphp

            @if(count($images) > 0)
                <div class="book-gallery">
                    <!-- Imagem Principal -->
                    <div class="book-gallery-main">
                        <img id="mainBookImage" 
                             src="{{ asset('storage/' . $images[0]) }}" 
                             alt="{{ is_array($book) ? $book['name'] : $book->name }}" 
                             style="max-height: 16.25em;">
                    </div>
                    
                    <!-- Miniaturas (apenas se houver mais de uma imagem) -->
                    @if(count($images) > 1)
                        <div class="book-gallery-thumbs">
                            @foreach($images as $idx => $img)
                                <div class="book-gallery-thumb{{ $idx === 0 ? ' selected' : '' }}" 
                                     data-img="{{ asset('storage/' . $img) }}"
                                     title="Ver imagem {{ $idx + 1 }}">
                                    <img src="{{ asset('storage/' . $img) }}" 
                                         alt="Miniatura {{ $idx + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <!-- Imagem padrão caso não tenha nenhuma imagem -->
                <div class="book-gallery">
                    <div class="book-gallery-main">
                        <img id="mainBookImage" 
                             src="{{ asset('images/no-image.png') }}" 
                             alt="Sem imagem disponível" 
                             style="max-height: 16.25em;">
                    </div>
                </div>
            @endif
        </div>
        
        <div class="book-product-infobox">
            <h1 class="book-title">{{ is_array($book) ? $book['name'] : $book->name }}</h1>
            
            <div class="book-meta">
                <span class="book-author">
                    <i class="fa fa-user"></i> 
                    {{ is_array($book) ? $book['author'] : $book->author }}
                </span>
                <span class="book-genre">
                    <i class="fa fa-tag"></i> 
                    {{ is_array($book) ? $book['genre'] : $book->genre }}
                </span>
                <span class="book-condition">
                    <i class="fa fa-star"></i> 
                    {{ is_array($book) ? $book['condition'] : $book->condition }}
                </span>
            </div>
            
            <p class="book-desc">{{ is_array($book) ? $book['description'] : $book->description }}</p>
            
            <div class="book-purchase-row">
                <span class="book-price">R$ {{ number_format(is_array($book) ? $book['price'] : $book->price, 2, ',', '.') }}</span>
                
                <form class="add-cart-form" 
                      action="{{ route('cart.add', is_array($book) ? $book['id'] : $book->id) }}" 
                      method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="book_id" value="{{ is_array($book) ? $book['id'] : $book->id }}">
                    <button type="submit">
                        <i class="fa fa-shopping-cart"></i> 
                        Adicionar ao Carrinho
                    </button>
                </form>
            </div>
            
            <a href="{{ route('books.index') }}" class="back-btn">
                <i class="fa fa-arrow-left"></i> 
                Voltar
            </a>
        </div>
    </div>
    @include('components.footer')
    @vite('resources/js/bookShow.js')
    
    <script>
        // Script adicional para debug e garantir funcionamento
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DEBUG GALERIA DE IMAGENS ===');
            
            const mainImg = document.getElementById('mainBookImage');
            const thumbs = document.querySelectorAll('.book-gallery-thumb');
            
            console.log('Imagem principal:', mainImg);
            console.log('Número de miniaturas:', thumbs.length);
            
            if (thumbs.length > 0) {
                thumbs.forEach((thumb, index) => {
                    const dataImg = thumb.getAttribute('data-img');
                    console.log(`Miniatura ${index + 1} data-img:`, dataImg);
                    
                    // Adiciona evento de clique simples para debug
                    thumb.addEventListener('click', function() {
                        console.log(`Clicou na miniatura ${index + 1}`);
                        console.log('data-img:', this.getAttribute('data-img'));
                    });
                });
            }
            
            console.log('=== FIM DEBUG ===');
        });
    </script>

@endsection


