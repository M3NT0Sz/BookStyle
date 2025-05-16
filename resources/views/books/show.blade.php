@extends('layouts.app')

@section('content')
    @php
        $images = is_array($book)
            ? (isset($book['images']) ? (is_array($book['images']) ? $book['images'] : json_decode($book['images'], true)) : [])
            : (isset($book->images) ? (is_array($book->images) ? $book->images : json_decode($book->images, true)) : []);
    @endphp
    @if(is_array($images) && !empty($images))
        <img width="200" src="{{ asset('storage/' . $images[0]) }}" alt="{{ is_array($book) ? $book['name'] : $book->name }}">
    @elseif(!is_array($images) && !empty($images))
        <img width="200" src="{{ asset('storage/' . $images) }}" alt="{{ is_array($book) ? $book['name'] : $book->name }}">
    @endif
    <h2>{{ is_array($book) ? $book['name'] : $book->name }}</h2>
    <p>{{ is_array($book) ? $book['description'] : $book->description }}</p>
    <p>{{ is_array($book) ? $book['author'] : $book->author }}</p>
    <p>{{ is_array($book) ? $book['genre'] : $book->genre }}</p>
    <p>{{ is_array($book) ? $book['condition'] : $book->condition }}</p>
    <p>{{ is_array($book) ? $book['price'] : $book->price }}</p>
    <form action="{{ route('cart.add', is_array($book) ? $book['id'] : $book->id) }}" method="POST" style="display:inline">
        @csrf
        <input type="hidden" name="quantity" value="1">
        <input type="text" name="coupon_code" placeholder="Cupom de desconto">
        <button type="submit">Adicionar ao Carrinho</button>
    </form>
    <a href="{{ route('books.index') }}">
        <button>Voltar</button>
    </a>
@endsection