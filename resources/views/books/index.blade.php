@extends('layouts.app')

@section('content')
    @foreach ($books as $book)
        <div>
            <img width="200" src="{{ asset($book->images) }}" alt="{{ $book->name }}">
            <h2>{{ $book->name }}</h2>
            <p>{{ $book->description }}</p>
            <p>{{ $book->author }}</p>
            <p>{{ $book->genre }}</p>
            <p>{{ $book->condition }}</p>
            <p>{{ $book->price }}</p>
            <a href="{{ route('books.show', $book->id) }}">
                <button>Comprar</button>
            </a>
        </div>
    @endforeach
@endsection