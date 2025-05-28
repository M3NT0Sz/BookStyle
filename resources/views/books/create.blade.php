@extends('layouts.bookRegister')

@section('content')
<div class="book-register-container">
    <div class="book-register-header">
        <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt=""></a>
        <h1>Cadastrar Livro</h1>
    </div>
    <div class="progress-bar-container">
        <div class="progress-bar">
            <div class="progress-bar-fill"></div>
            <div class="progress-step step-active">1</div>
            <div class="progress-step">2</div>
            <div class="progress-step">3</div>
            <div class="progress-step">4</div>
        </div>
    </div>
    <form class="book-register-form" id="stepForm" action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- Etapa 1: Dados básicos -->
        <div class="form-step step-active" id="step-1">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input placeholder="Nome do livro" type="text" name="name" id="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="author">Autor</label>
                    <input placeholder="Nome do autor" type="text" name="author" id="author" value="{{ old('author') }}">
                </div>
            </div>
            <button type="button" class="button-cad next-step">Próxima etapa</button>
        </div>
        <!-- Etapa 2: Gêneros -->
        <div class="form-step" id="step-2">
            <div class="form-group">
                <label for="genreCategory">Categoria de Gênero</label>
                <select id="genreCategory" class="genre-category-select">
                    <option value="ficcao">Ficção</option>
                    <option value="nao-ficcao">Não Ficção</option>
                    <option value="infantojuvenil">Infantojuvenil</option>
                    <option value="poesia">Poesia e Teatro</option>
                    <option value="hqs">Mangás, HQs e Graphic Novels</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gêneros</label>
                <div class="checkbox-group" id="genreOptions">
                    <!-- Opções de gênero via JS -->
                </div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <button type="button" class="button-cad prev-step">Voltar</button>
                <button type="button" class="button-cad next-step">Próxima etapa</button>
            </div>
        </div>
        <!-- Etapa 3: Detalhes do produto -->
        <div class="form-step" id="step-3">
            <div class="form-row">
                <div class="form-group">
                    <label for="condition">Condição</label>
                    <select name="condition" id="condition">
                        <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Novo</option>
                        <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Usado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Preço</label>
                    <input placeholder="Preço" type="text" name="price" id="price" value="{{ old('price') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="product_type">Tipo de Produto</label>
                    <select name="product_type" id="product_type">
                        <option value="fisico" {{ old('product_type') == 'fisico' ? 'selected' : '' }}>Livro Físico</option>
                        <option value="ebook" {{ old('product_type') == 'ebook' ? 'selected' : '' }}>Ebook</option>
                        <option value="gibi" {{ old('product_type') == 'gibi' ? 'selected' : '' }}>Gibi</option>
                        <option value="box" {{ old('product_type') == 'box' ? 'selected' : '' }}>Box de Livros</option>
                    </select>
                </div>
            </div>
            @include('books.partials.product_fields')
            <div style="display: flex; justify-content: space-between;">
                <button type="button" class="button-cad prev-step">Voltar</button>
                <button type="button" class="button-cad next-step">Próxima etapa</button>
            </div>
        </div>
        <!-- Etapa 4: Descrição e imagens -->
        <div class="form-step" id="step-4">
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea placeholder="Descrição do livro" name="description" id="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="images" class="upload-label">Selecionar Imagens</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*">
                <div class="image-preview"></div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <button type="button" class="button-cad prev-step">Voltar</button>
                <button class="button-cad" type="submit">Cadastrar</button>
            </div>
        </div>
    </form>
</div>
<script src="{{ Vite::asset('resources/js/bookRegister.js') }}"></script>
<style>
    .progress-bar-container {
        width: 100%;
        margin-bottom: 2rem;
    }
    .progress-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        width: 100%;
        max-width: 40rem;
        margin: 0 auto 1.5rem auto;
    }
    .progress-bar::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 0.25rem;
        background: #e0e0e0;
        z-index: 0;
        border-radius: 1rem;
        transform: translateY(-50%);
    }
    .progress-bar-fill {
        position: absolute;
        top: 50%;
        left: 0;
        height: 0.25rem;
        background: #4f8cff;
        z-index: 1;
        border-radius: 1rem;
        transform: translateY(-50%);
        transition: width 0.4s cubic-bezier(.4,1.3,.6,1);
        width: 0;
        pointer-events: none;
    }
    .progress-step {
        position: relative;
        z-index: 2;
        width: 2.2rem;
        height: 2.2rem;
        background: #fff;
        border: 0.2rem solid #bdbdbd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #bdbdbd;
        font-size: 1.1rem;
        transition: all 0.3s;
    }
    .progress-step.step-active, .progress-step.step-done {
        border-color: #4f8cff;
        color: #4f8cff;
        background: #eaf2ff;
    }
    .progress-step.step-done {
        background: #4f8cff;
        color: #fff;
    }
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: calc(100% + 0.5rem);
        height: 0.25rem;
        background: transparent;
        z-index: -1;
        border-radius: 1rem;
        transform: translateY(-50%);
    }
    @media (max-width: 600px) {
        .progress-bar { max-width: 100%; }
        .progress-step { width: 1.7rem; height: 1.7rem; font-size: 0.95rem; }
    }
    .book-register-container {
        max-width: 600px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px rgba(79,140,255,0.08);
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .book-register-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .book-register-header .logo {
        width: 48px;
        height: 48px;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(79,140,255,0.10);
    }
    .book-register-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #4f8cff;
        margin: 0;
    }
    .book-register-form {
        width: 100%;
    }
    .form-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        flex: 1 1 200px;
        margin-bottom: 1.2rem;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.7rem 1rem;
        border: 1.5px solid #d0d7e2;
        border-radius: 0.6rem;
        font-size: 1rem;
        background: #f8faff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #4f8cff;
        box-shadow: 0 0 0 2px #eaf2ff;
        background: #fff;
    }
    .form-group textarea {
        min-height: 90px;
        resize: vertical;
    }
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem 1.2rem;
        margin-top: 0.3rem;
    }
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
        color: #444;
        cursor: pointer;
    }
    .upload-label {
        display: inline-block;
        background: #eaf2ff;
        color: #4f8cff;
        padding: 0.5rem 1.2rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        margin-bottom: 0.5rem;
        transition: background 0.2s;
    }
    .upload-label:hover {
        background: #d6e7ff;
    }
    .image-preview {
        display: flex;
        gap: 0.7rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }
    .image-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.4rem;
        border: 1.5px solid #d0d7e2;
        box-shadow: 0 2px 8px rgba(79,140,255,0.08);
    }
    .button-cad {
        background: #4f8cff;
        color: #fff;
        border: none;
        border-radius: 0.6rem;
        padding: 0.7rem 2.2rem;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(79,140,255,0.10);
    }
    .button-cad:hover, .button-cad:focus {
        background: #2563eb;
        box-shadow: 0 4px 16px rgba(79,140,255,0.13);
    }
    .form-step {
        display: none;
    }
    .form-step.step-active {
        display: block;
        animation: fadeIn 0.4s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px);} 
        to { opacity: 1; transform: none; }
    }
    .input-error, .input-error input, .input-error select, .input-error textarea {
        border-color: #ff4f4f !important;
        box-shadow: 0 0 0 2px #ffbdbd;
        background: #fff6f6;
    }
    @media (max-width: 600px) {
        .book-register-container {
            padding: 1.2rem 0.5rem;
        }
        .form-row {
            flex-direction: column;
            gap: 0.7rem;
        }
        .book-register-header h1 {
            font-size: 1.3rem;
        }
        .progress-bar { max-width: 100%; }
        .progress-step { width: 1.7rem; height: 1.7rem; font-size: 0.95rem; }
    }
</style>
@endsection