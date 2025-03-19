@extends('layouts.login')

@section('content')
<main>
    <section id="login" class="login-container">
        <section class="login-form">
            <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                    alt=""></a>
            <h1>Login</h1>
            <form action="{{ route('login') }}" method="post">
                @csrf

                <label id="emailLabel" class="email" for="email">Email</label>
                <input type="email" placeholder="Email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                <div>{{ $message }}</div>
                @enderror

                <label id="senhaLabel" class="senha" for="password">Senha</label>
                <input type="password" placeholder="Senha" name="password" id="password">
                @error('password')
                <div>{{ $message }}</div>
                @enderror


                <button type="submit">Login</button>


                <a href="{{ route('password.request') }}">Forgot your password?</a>


                <a href="#register">Register</a>

            </form>
            <section class="login-ou">
                <p>OU</p>
                <span></span>
            </section>
            <section class="icon-logs">
                <ion-icon class="icon-google" name="logo-google"></ion-icon>
                <ion-icon class="icon-facebook" name="logo-facebook"></ion-icon>
            </section>
        </section>
    </section>
    <section class="banner-container">
        <img class="livro" src="{{ Vite::asset('resources/img/livro_aberto.png') }}" alt="">
        <img class="anel" src="{{ Vite::asset('resources/img/anel.png') }}" alt="">
        <img class="dragao" src="{{ Vite::asset('resources/img/dragao.png') }}" alt="">
        <img class="mont" src="{{ Vite::asset('resources/img/mont.png') }}" alt="">
        <img class="gandalf" src="{{ Vite::asset('resources/img/gandal.png') }}" alt="">
        <img class="varinha" src="{{ Vite::asset('resources/img/varinha.png') }}" alt="">
        <img class="espada" src="{{ Vite::asset('resources/img/espada.png') }}" alt="">
        <img class="coroa" src="{{ Vite::asset('resources/img/coroa.png') }}" alt="">
        <img class="banjo" src="{{ Vite::asset('resources/img/banjo.png') }}" alt="">

    </section>


    <section class="book-left">
        <img class="castelo" src="{{ Vite::asset('resources/img/castlle.png') }}" alt="">
        <img class="pedra" src="{{ Vite::asset('resources/img/pedra.png') }}" alt="">
        <img class="sword" src="{{ Vite::asset('resources/img/sword.png') }}" alt="">
        <img class="anelVerde" src="{{ Vite::asset('resources/img/anelVerde.png') }}" alt="">
        <img class="coruja" src="{{ Vite::asset('resources/img/coruja.png') }}" alt="">
        <img class="lua" src="{{ Vite::asset('resources/img/lua.png') }}" alt="">
    </section>
    <section id="register" class="form-book">
        <section class="form-container ">
            <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                    alt=""></a>
            <h1>Registrar</h1>
            <form action="{{ route('register') }}" method="post">
                @csrf

                <label class="nome" for="name" id="nameLabelRe">Nome</label>
                <input type="text" placeholder="Nome" name="name" id="name" value="{{ old('name') }}">
                @error('name')
                <div>{{ $message }}</div>
                @enderror


                <label class="emailRe" for="email" id="emailLabelRe">Email</label>
                <input type="email" placeholder="Email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                <div>{{ $message }}</div>
                @enderror

                <label class="senhaRe" for="password" id="passwordLabelRe">Senha</label>
                <input type="password" placeholder="Senha" name="password" id="password">
                @error('password')
                <div>{{ $message }}</div>
                @enderror


                <label class="conf-senha" for="password_confirmation" id="passwordConfirmationLabel">Confirmar
                    Senha</label>
                <input type="password" placeholder="Confirmar senha" name="password_confirmation"
                    id="password_confirmation">


                <button class="register-button" type="submit">Register</button>

                <a class="link-register" href="#login">Login</a>
            </form>
            <section class="login-ou2">
                <p>OU</p>
                <span></span>
            </section>
            <section class="icon-logs2">
                <ion-icon class="icon-google2" name="logo-google"></ion-icon>
                <ion-icon class="icon-facebook2" name="logo-facebook"></ion-icon>
            </section>
        </section>

    </section>


</main>

@endsection