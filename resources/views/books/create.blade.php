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
                    <label><input type="checkbox" name="genre[]" value="fantasia"> Fantasia</label><br>
                    <label><input type="checkbox" name="genre[]" value="ficcao-cientifica"> Ficção Científica</label><br>
                    <label><input type="checkbox" name="genre[]" value="distopia-utopia"> Distopia/Utopia</label><br>
                    <label><input type="checkbox" name="genre[]" value="ficcao-historica"> Ficção Histórica</label><br>
                    <label><input type="checkbox" name="genre[]" value="ficcao-contemporanea"> Ficção Contemporânea</label><br>
                    <label><input type="checkbox" name="genre[]" value="ficcao-realista"> Ficção Realista</label><br>
                    <label><input type="checkbox" name="genre[]" value="romance"> Romance</label><br>
                    <label><input type="checkbox" name="genre[]" value="aventura"> Aventura</label><br>
                    <label><input type="checkbox" name="genre[]" value="terror-horror"> Terror/Horror</label><br>
                    <label><input type="checkbox" name="genre[]" value="suspense-thriller"> Suspense/Thriller</label><br>
                    <label><input type="checkbox" name="genre[]" value="policial-crime"> Policial/Crime</label><br>
                    <label><input type="checkbox" name="genre[]" value="western"> Western</label><br>
                    <label><input type="checkbox" name="genre[]" value="chick-lit"> Chick-lit</label><br><br>

                    <strong>Não Ficção:</strong><br>
                    <label><input type="checkbox" name="genre[]" value="biografia-autobiografia"> Biografia/Autobiografia</label><br>
                    <label><input type="checkbox" name="genre[]" value="memorias"> Memórias</label><br>
                    <label><input type="checkbox" name="genre[]" value="ensaios"> Ensaios</label><br>
                    <label><input type="checkbox" name="genre[]" value="autoajuda"> Autoajuda e Desenvolvimento Pessoal</label><br>
                    <label><input type="checkbox" name="genre[]" value="ciencia-tecnologia"> Ciência e Tecnologia</label><br>
                    <label><input type="checkbox" name="genre[]" value="historia"> História</label><br>
                    <label><input type="checkbox" name="genre[]" value="filosofia"> Filosofia</label><br>
                    <label><input type="checkbox" name="genre[]" value="religiao-espiritualidade"> Religião e Espiritualidade</label><br>
                    <label><input type="checkbox" name="genre[]" value="psicologia-psicanalise"> Psicologia e Psicanálise</label><br><br>

                    <strong>Literatura Infantojuvenil:</strong><br>
                    <label><input type="checkbox" name="genre[]" value="contos-fadas"> Contos de Fadas</label><br>
                    <label><input type="checkbox" name="genre[]" value="fabulas"> Fábulas</label><br>
                    <label><input type="checkbox" name="genre[]" value="livros-infantis"> Livros Infantis Ilustrados</label><br>
                    <label><input type="checkbox" name="genre[]" value="young-adult"> Young Adult (YA)</label><br>
                    <label><input type="checkbox" name="genre[]" value="middle-grade"> Middle Grade (MG)</label><br><br>

                    <strong>Poesia e Teatro:</strong><br>
                    <label><input type="checkbox" name="genre[]" value="poesia"> Poesia</label><br>
                    <label><input type="checkbox" name="genre[]" value="teatro-drama"> Teatro/Drama</label><br><br>

                    <strong>Mangás, HQs e Graphic Novels:</strong><br>
                    <label><input type="checkbox" name="genre[]" value="mangas"> Mangás</label><br>
                    <label><input type="checkbox" name="genre[]" value="hqs"> Histórias em Quadrinhos (HQs)</label><br>
                    <label><input type="checkbox" name="genre[]" value="graphic-novels"> Graphic Novels</label><br>
                </div>
            </div>

            <div class="form-condition-price">

                <select name="condition" id="condition">
                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Novo</option>
                    <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Usado</option>
                </select>
                <input placeholder="Preço" type="text" name="price" id="price" value="{{ old('price') }}">
            </div>
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