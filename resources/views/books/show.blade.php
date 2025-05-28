@extends('layouts.app')

@section('content')
    @vite('resources/css/bookShow.css')
    @include('components.nav_bar')
    <div class="book-product-hero">
        <div class="book-product-imgbox">
            @php
                $images = is_array($book)
                    ? (isset($book['images']) ? (is_array($book['images']) ? $book['images'] : json_decode($book['images'], true)) : [])
                    : (isset($book->images) ? (is_array($book->images) ? $book->images : json_decode($book->images, true)) : []);
            @endphp
            @if(is_array($images) && !empty($images))
                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ is_array($book) ? $book['name'] : $book->name }}">
            @elseif(!is_array($images) && !empty($images))
                <img src="{{ asset('storage/' . $images) }}" alt="{{ is_array($book) ? $book['name'] : $book->name }}">
            @endif
        </div>
        <div class="book-product-infobox">
            <h1 class="book-title">{{ is_array($book) ? $book['name'] : $book->name }}</h1>
            <div class="book-meta">
                <span class="book-author"><i class="fa fa-user"></i> {{ is_array($book) ? $book['author'] : $book->author }}</span>
                <span class="book-genre"><i class="fa fa-tag"></i> {{ is_array($book) ? $book['genre'] : $book->genre }}</span>
                <span class="book-condition"><i class="fa fa-star"></i> {{ is_array($book) ? $book['condition'] : $book->condition }}</span>
            </div>
            <p class="book-desc">{{ is_array($book) ? $book['description'] : $book->description }}</p>
            <div class="book-purchase-row">
                <span class="book-price">R$ {{ number_format(is_array($book) ? $book['price'] : $book->price, 2, ',', '.') }}</span>
                <form class="add-cart-form" action="{{ route('cart.add', is_array($book) ? $book['id'] : $book->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <input type="text" name="coupon_code" placeholder="Cupom de desconto">
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>
            <a href="{{ route('books.index') }}" class="back-btn">Voltar</a>
        </div>
    </div>
    @include('components.footer')
@endsection
@section('scripts')
    @vite('resources/js/bookShow.js')
@endsection