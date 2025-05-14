@extends('layouts.bookRegister')

@section('content')
    <main>
        <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt=""></a>
        <h1>Editar Livro</h1>
        <span></span>

        <section class="body-form">
            <form action="{{ route('books.update', ['book' => is_object($book) ? $book->id : ($book['id'] ?? '')]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-author-name">
                    <label for="name">Nome</label>
                    <input placeholder="Nome" type="text" name="name" id="name" value="{{ is_object($book) ? $book->name : ($book['name'] ?? '') }}">
                    <label for="author">Autor</label>
                    <input placeholder="Autor" type="text" name="author" id="author" value="{{ is_object($book) ? $book->author : ($book['author'] ?? '') }}">
                </div>

                <div class="form-genre">
                    <div id="genre">
                        <strong>Ficção:</strong><br>
                        @php
                            $selectedGenres = is_object($book)
                                ? (is_array($book->genre) ? $book->genre : json_decode($book->genre, true) ?? [])
                                : (isset($book['genre']) ? (is_array($book['genre']) ? $book['genre'] : json_decode($book['genre'], true) ?? []) : []);
                        @endphp
                        @foreach (['fantasia' => 'Fantasia', 'ficcao-cientifica' => 'Ficção Científica', 'distopia-utopia' => 'Distopia/Utopia', 'ficcao-historica' => 'Ficção Histórica', 'ficcao-contemporanea' => 'Ficção Contemporânea', 'ficcao-realista' => 'Ficção Realista', 'romance' => 'Romance', 'aventura' => 'Aventura', 'terror-horror' => 'Terror/Horror', 'suspense-thriller' => 'Suspense/Thriller', 'policial-crime' => 'Policial/Crime', 'western' => 'Western', 'chick-lit' => 'Chick-lit'] as $value => $label)
                            <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label><br>
                        @endforeach
                        <br>

                        <strong>Não Ficção:</strong><br>
                        @foreach (['biografia-autobiografia' => 'Biografia/Autobiografia', 'memorias' => 'Memórias', 'ensaios' => 'Ensaios', 'autoajuda' => 'Autoajuda e Desenvolvimento Pessoal', 'ciencia-tecnologia' => 'Ciência e Tecnologia', 'historia' => 'História', 'filosofia' => 'Filosofia', 'religiao-espiritualidade' => 'Religião e Espiritualidade', 'psicologia-psicanalise' => 'Psicologia e Psicanálise'] as $value => $label)
                            <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label><br>
                        @endforeach
                        <br>

                        <strong>Literatura Infantojuvenil:</strong><br>
                        @foreach (['contos-fadas' => 'Contos de Fadas', 'fabulas' => 'Fábulas', 'livros-infantis' => 'Livros Infantis Ilustrados', 'young-adult' => 'Young Adult (YA)', 'middle-grade' => 'Middle Grade (MG)'] as $value => $label)
                            <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label><br>
                        @endforeach
                        <br>

                        <strong>Poesia e Teatro:</strong><br>
                        @foreach (['poesia' => 'Poesia', 'teatro-drama' => 'Teatro/Drama'] as $value => $label)
                            <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label><br>
                        @endforeach
                        <br>

                        <strong>Mangás, HQs e Graphic Novels:</strong><br>
                        @foreach (['mangas' => 'Mangás', 'hqs' => 'Histórias em Quadrinhos (HQs)', 'graphic-novels' => 'Graphic Novels'] as $value => $label)
                            <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label><br>
                        @endforeach
                    </div>
                </div>

                <div class="form-condition-price">
                    <select name="condition" id="condition">
                        <option value="new" {{ (is_object($book) ? $book->condition : ($book['condition'] ?? '')) == 'new' ? 'selected' : '' }}>Novo</option>
                        <option value="used" {{ (is_object($book) ? $book->condition : ($book['condition'] ?? '')) == 'used' ? 'selected' : '' }}>Usado</option>
                    </select>
                    <input placeholder="Preço" type="text" name="price" id="price" value="{{ is_object($book) ? $book->price : ($book['price'] ?? '') }}">
                </div>

                <div class="form-condition-price">
                    <label for="product_type">Tipo de Produto</label>
                    <select name="product_type" id="product_type">
                        <option value="fisico" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? 'fisico')) == 'fisico' ? 'selected' : '' }}>Livro Físico</option>
                        <option value="ebook" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? '')) == 'ebook' ? 'selected' : '' }}>Ebook</option>
                        <option value="gibi" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? '')) == 'gibi' ? 'selected' : '' }}>Gibi</option>
                        <option value="box" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? '')) == 'box' ? 'selected' : '' }}>Box de Livros</option>
                    </select>
                </div>

                @include('books.partials.product_fields')

                <div class="form-description">
                    <textarea placeholder="Descrição" name="description" id="description">{{ is_object($book) ? $book->description : ($book['description'] ?? '') }}</textarea>
                </div>
                <div class="form-images">
                    <label for="images" class="upload-label">Selecionar Imagens</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*">
                    <div class="image-preview">
                        @php
                            $images = is_object($book)
                                ? (isset($book->images) ? (is_array($book->images) ? $book->images : json_decode($book->images, true)) : [])
                                : (isset($book['images']) ? (is_array($book['images']) ? $book['images'] : json_decode($book['images'], true)) : []);
                        @endphp
                        @if(!empty($images))
                            @foreach($images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Imagem do livro" width="80">
                            @endforeach
                        @endif
                    </div>
                </div>

                <div>
                    <button class="button-cad" type="submit">Salvar Alterações</button>
                </div>
                <button type="button" class="button-genre">Gênero</button>
            </form>
        </section>
    </main>
@endsection