@extends('layouts.app')

@section('content')
    <a href="{{ route('books.create') }}">Cadastrar livro</a>
    <a href="{{ route('index') }}">Voltar</a>

    <h1>Profile</h1>
    @foreach ($books as $book)
        <section>
            @if(!empty($book->images))
                @php
                    $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                @endphp
                @if(is_array($images))
                    @foreach($images as $image)
                        <img width="50px" src="{{ asset('storage/' . $image) }}" alt="{{ $book->name }}">
                    @endforeach
                @else
                    <img src="{{ asset($book->images) }}" alt="{{ $book->name }}">
                @endif
            @endif
            <h2> {{ $book->name }} </h2>
            <p> {{ $book->description }} </p>
            <p> {{ $book->author }} </p>
            <p> {{ $book->genre }} </p>
            <p> {{ $book->condition }} </p>
            <p> {{ $book->price }} </p>
            <button><a href="{{ route('books.edit', $book->id) }}">Editar</a></button>
            <form action="{{ route('books.destroy', $book->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button>Deletar</button>
            </form>
        </section>
    @endforeach
@endsection