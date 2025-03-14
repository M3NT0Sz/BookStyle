@extends('layouts.app')

@section('content')
    @php
        $bookId = request()->route('book');
        $book = \App\Models\Book::find($bookId);
    @endphp
    @if($book->images)
        @foreach(json_decode($book->images, true) as $image)
            <img width="200" src="{{ asset('storage/' . $image) }}" alt="{{ $book->name }}">
        @endforeach
    @endif
    <h2>{{ $book->name }}</h2>
    <p>{{ $book->description }}</p>
    <p>{{ $book->author }}</p>
    <p>{{ $book->genre }}</p>
    <p>{{ $book->condition }}</p>
    <p>{{ $book->price }}</p>
    <button>Comprar</button>
    <a href="{{ route('index') }}">Voltar</a>
@endsection