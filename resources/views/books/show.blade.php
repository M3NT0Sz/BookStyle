@extends('layouts.app')

@section('content')
    @if($book->images)
        <img width="200" src="{{ asset($book->images) }}" alt="{{ $book->name }}">
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