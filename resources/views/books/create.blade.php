@extends('layouts.bookRegister')

@section('content')
    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
        </div>
        <div>
            <label for="author">Autor</label>
            <input type="text" name="author" id="author" value="{{ old('author') }}">
        </div>
        <div>
            <label for="genre">Gênero</label>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}">
        </div>
        <div>
            <label for="condition">Condição</label>
            <select name="condition" id="condition">
                <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Novo</option>
                <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Usado</option>
            </select>
        </div>
        <div>
            <label for="price">Preço</label>
            <input type="text" name="price" id="price" value="{{ old('price') }}">
        </div>
        <div>
            <label for="description">Descrição</label>
            <textarea name="description" id="description">{{ old('description') }}</textarea>
        </div>
        <div>
            <label for="images">Imagens</label>
            <input type="file" name="images[]" id="images" multiple>
        </div>
        <div>
            <button type="submit">Cadastrar</button>
        </div>
    </form>
@endsection