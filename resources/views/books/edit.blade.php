@extends('layouts.app')

@section('content')
    @php
        $bookId = request()->route('book');
        $book = \App\Models\Book::find($bookId);
    @endphp
    <form action="{{ route('books.update', ['book' => $book->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" value="{{ $book->name }}">
        </div>
        <div>
            <label for="author">Autor</label>
            <input type="text" name="author" id="author" value="{{ $book->author }}">
        </div>
        <div>
            <label for="genre">Gênero</label>
            <input type="text" name="genre" id="genre" value="{{ $book->genre }}">
        </div>
        <div>
            <label for="condition">Condição</label>
            <select name="condition" id="condition">
                <option value="new" {{ $book->condition == 'new' ? 'selected' : '' }}>Novo</option>
                <option value="used" {{ $book->condition == 'used' ? 'selected' : '' }}>Usado</option>
            </select>
        </div>
        <div>
            <label for="price">Preço</label>
            <input type="text" name="price" id="price" value="{{ $book->price }}">
        </div>
        <div>
            <label for="description">Descrição</label>
            <textarea name="description" id="description">{{ $book->description }}</textarea>
        </div>
        <div>
            <label for="images">Imagens</label>
            <input type="file" name="images[]" id="images" multiple>
        </div>
        <div>
            <button type="submit">Editar</button>
        </div>
    </form>
    <a href="{{ route('books.index') }}">Voltar</a>
@endsection