@extends('layouts.bookRegister')

@section('content')
<main>
    <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
    alt=""></a>
    <h1>Cadastrar Livro</h1>
    <span></span>

    <section class="body-form">
        <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-author-name">
                <label for="name">Nome</label>
                <input placeholder="Nome" type="text" name="name" id="name" value="{{ old('name') }}">
                <label for="author">Autor</label>
                <input placeholder="Autor" type="text" name="author" id="author" value="{{ old('author') }}">
            </div>

            <div class="form-genre">
                <div id="genre">
                    <strong>Ficção:</strong><br>
                    @foreach (['fantasia' => 'Fantasia', 'ficcao-cientifica' => 'Ficção Científica', 'distopia-utopia' => 'Distopia/Utopia', 'ficcao-historica' => 'Ficção Histórica', 'ficcao-contemporanea' => 'Ficção Contemporânea', 'ficcao-realista' => 'Ficção Realista', 'romance' => 'Romance', 'aventura' => 'Aventura', 'terror-horror' => 'Terror/Horror', 'suspense-thriller' => 'Suspense/Thriller', 'policial-crime' => 'Policial/Crime', 'western' => 'Western', 'chick-lit' => 'Chick-lit'] as $value => $label)
                        <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, old('genre', [])) ? 'checked' : '' }}> {{ $label }}</label><br>
                    @endforeach
                    <br>

                    <strong>Não Ficção:</strong><br>
                    @foreach (['biografia-autobiografia' => 'Biografia/Autobiografia', 'memorias' => 'Memórias', 'ensaios' => 'Ensaios', 'autoajuda' => 'Autoajuda e Desenvolvimento Pessoal', 'ciencia-tecnologia' => 'Ciência e Tecnologia', 'historia' => 'História', 'filosofia' => 'Filosofia', 'religiao-espiritualidade' => 'Religião e Espiritualidade', 'psicologia-psicanalise' => 'Psicologia e Psicanálise'] as $value => $label)
                        <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, old('genre', [])) ? 'checked' : '' }}> {{ $label }}</label><br>
                    @endforeach
                    <br>

                    <strong>Literatura Infantojuvenil:</strong><br>
                    @foreach (['contos-fadas' => 'Contos de Fadas', 'fabulas' => 'Fábulas', 'livros-infantis' => 'Livros Infantis Ilustrados', 'young-adult' => 'Young Adult (YA)', 'middle-grade' => 'Middle Grade (MG)'] as $value => $label)
                        <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, old('genre', [])) ? 'checked' : '' }}> {{ $label }}</label><br>
                    @endforeach
                    <br>

                    <strong>Poesia e Teatro:</strong><br>
                    @foreach (['poesia' => 'Poesia', 'teatro-drama' => 'Teatro/Drama'] as $value => $label)
                        <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, old('genre', [])) ? 'checked' : '' }}> {{ $label }}</label><br>
                    @endforeach
                    <br>

                    <strong>Mangás, HQs e Graphic Novels:</strong><br>
                    @foreach (['mangas' => 'Mangás', 'hqs' => 'Histórias em Quadrinhos (HQs)', 'graphic-novels' => 'Graphic Novels'] as $value => $label)
                        <label><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, old('genre', [])) ? 'checked' : '' }}> {{ $label }}</label><br>
                    @endforeach
                </div>
            </div>

            <div class="form-condition-price">

                <select name="condition" id="condition">
                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Novo</option>
                    <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Usado</option>
                </select>
                <input placeholder="Preço" type="text" name="price" id="price" value="{{ old('price') }}">
            </div>

            <div class="form-condition-price">
                <label for="product_type">Tipo de Produto</label>
                <select name="product_type" id="product_type">
                    <option value="fisico" {{ old('product_type') == 'fisico' ? 'selected' : '' }}>Livro Físico</option>
                    <option value="ebook" {{ old('product_type') == 'ebook' ? 'selected' : '' }}>Ebook</option>
                    <option value="gibi" {{ old('product_type') == 'gibi' ? 'selected' : '' }}>Gibi</option>
                    <option value="box" {{ old('product_type') == 'box' ? 'selected' : '' }}>Box de Livros</option>
                </select>
            </div>

            @include('books.partials.product_fields')

            <div class="form-description">

                <textarea placeholder="Descrição" name="description" id="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-images">
                <label for="images" class="upload-label">Selecionar Imagens</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*">
                <div class="image-preview"></div>
            </div>

            <div>
                <button class="button-cad" type="submit">Cadastrar</button>
            </div>
        </form>
        <button class="button-genre">Gênero</button>
    </section>

</main>

@endsection