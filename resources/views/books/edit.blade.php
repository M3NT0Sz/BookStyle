@extends('layouts.bookRegister')

@section('content')
    <main class="edit-book-main">
        <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt=""></a>
        <h1 class="edit-title">Editar Livro</h1>
        <span></span>

        <a href="{{ route('user.profile') }}" class="button-back-profile">&larr; Voltar para o Perfil</a>

        <section class="body-form edit-form-section">
            <form action="{{ route('books.update', ['book' => is_object($book) ? $book->id : ($book['id'] ?? '')]) }}" method="post" enctype="multipart/form-data" class="edit-form">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input placeholder="Nome" type="text" name="name" id="name" value="{{ is_object($book) ? $book->name : ($book['name'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="author">Autor</label>
                        <input placeholder="Autor" type="text" name="author" id="author" value="{{ is_object($book) ? $book->author : ($book['author'] ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group form-genre-group">
                        <label class="genre-label">Gêneros</label>
                        <div class="genre-grid">
                            @php
                                $selectedGenres = is_object($book)
                                    ? (is_array($book->genre) ? $book->genre : json_decode($book->genre, true) ?? [])
                                    : (isset($book['genre']) ? (is_array($book['genre']) ? $book['genre'] : json_decode($book['genre'], true) ?? []) : []);
                            @endphp
                            <div class="genre-column">
                                <strong>Ficção</strong>
                                @foreach ([
                                    'fantasia' => 'Fantasia',
                                    'ficcao-cientifica' => 'Ficção Científica',
                                    'distopia-utopia' => 'Distopia/Utopia',
                                    'ficcao-historica' => 'Ficção Histórica',
                                    'ficcao-contemporanea' => 'Ficção Contemporânea',
                                    'ficcao-realista' => 'Ficção Realista',
                                    'romance' => 'Romance',
                                    'aventura' => 'Aventura',
                                    'terror-horror' => 'Terror/Horror',
                                    'suspense-thriller' => 'Suspense/Thriller',
                                    'policial-crime' => 'Policial/Crime',
                                    'western' => 'Western',
                                    'chick-lit' => 'Chick-lit',
                                ] as $value => $label)
                                    <label class="genre-checkbox"><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label>
                                @endforeach
                            </div>
                            <div class="genre-column">
                                <strong>Não Ficção</strong>
                                @foreach ([
                                    'biografia-autobiografia' => 'Biografia/Autobiografia',
                                    'memorias' => 'Memórias',
                                    'ensaios' => 'Ensaios',
                                    'autoajuda' => 'Autoajuda e Desenvolvimento Pessoal',
                                    'ciencia-tecnologia' => 'Ciência e Tecnologia',
                                    'historia' => 'História',
                                    'filosofia' => 'Filosofia',
                                    'religiao-espiritualidade' => 'Religião e Espiritualidade',
                                    'psicologia-psicanalise' => 'Psicologia e Psicanálise',
                                ] as $value => $label)
                                    <label class="genre-checkbox"><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label>
                                @endforeach
                            </div>
                            <div class="genre-column">
                                <strong>Infantojuvenil</strong>
                                @foreach ([
                                    'contos-fadas' => 'Contos de Fadas',
                                    'fabulas' => 'Fábulas',
                                    'livros-infantis' => 'Livros Infantis Ilustrados',
                                    'young-adult' => 'Young Adult (YA)',
                                    'middle-grade' => 'Middle Grade (MG)',
                                ] as $value => $label)
                                    <label class="genre-checkbox"><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label>
                                @endforeach
                            </div>
                            <div class="genre-column">
                                <strong>Poesia e Teatro</strong>
                                @foreach ([
                                    'poesia' => 'Poesia',
                                    'teatro-drama' => 'Teatro/Drama',
                                ] as $value => $label)
                                    <label class="genre-checkbox"><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label>
                                @endforeach
                                <strong>Mangás, HQs e Graphic Novels</strong>
                                @foreach ([
                                    'mangas' => 'Mangás',
                                    'hqs' => 'Histórias em Quadrinhos (HQs)',
                                    'graphic-novels' => 'Graphic Novels',
                                ] as $value => $label)
                                    <label class="genre-checkbox"><input type="checkbox" name="genre[]" value="{{ $value }}" {{ in_array($value, $selectedGenres) ? 'checked' : '' }}> {{ $label }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="condition">Condição</label>
                        <select name="condition" id="condition">
                            <option value="new" {{ (is_object($book) ? $book->condition : ($book['condition'] ?? '')) == 'new' ? 'selected' : '' }}>Novo</option>
                            <option value="used" {{ (is_object($book) ? $book->condition : ($book['condition'] ?? '')) == 'used' ? 'selected' : '' }}>Usado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Preço</label>
                        <input placeholder="Preço" type="text" name="price" id="price" value="{{ is_object($book) ? $book->price : ($book['price'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="product_type">Tipo de Produto</label>
                        <select name="product_type" id="product_type">
                            <option value="fisico" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? 'fisico')) == 'fisico' ? 'selected' : '' }}>Livro Físico</option>
                            <option value="ebook" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? '')) == 'ebook' ? 'selected' : '' }}>Ebook</option>
                            <option value="gibi" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? '')) == 'gibi' ? 'selected' : '' }}>Gibi</option>
                            <option value="box" {{ (is_object($book) ? $book->product_type : ($book['product_type'] ?? '')) == 'box' ? 'selected' : '' }}>Box de Livros</option>
                        </select>
                    </div>
                </div>

                @include('books.partials.product_fields')

                <div class="form-row">
                    <div class="form-group form-description-group">
                        <label for="description">Descrição</label>
                        <textarea placeholder="Descrição" name="description" id="description">{{ is_object($book) ? $book->description : ($book['description'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group form-images-group">
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
                                    <img src="{{ asset('storage/' . $img) }}" alt="Imagem do livro" class="preview-img">
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-row form-actions">
                    <button class="button-cad" type="submit">Salvar Alterações</button>
                
                </div>
            </form>
        </section>
    </main>
@endsection