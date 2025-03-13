@extends('layouts.app')

@section('content')
    @foreach ($books as $book)
        <div>
            @php
                $images = json_decode($book->images, true);
            @endphp
            @if(is_array($images))
                @foreach($images as $image)
                    <img width="200" src="{{ asset('storage/' . $image) }}" alt="{{ $book->name }}">
                @endforeach
            @endif
            <h2>{{ $book->name }}</h2>
            <p>{{ $book->description }}</p>
            <p>{{ $book->author }}</p>
            <p>{{ $book->genre }}</p>
            <p>{{ $book->condition }}</p>
            <p>{{ $book->price }}</p>
            <a href="{{ route('books.edit', $book->id) }}">Editar</a>
            <form action="{{ route('books.destroy', $book->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit">Deletar</button>
            </form>
        </div>
    @endforeach
    <a href="{{ route('books.create') }}">Cadastrar Livro</a>
@endsection