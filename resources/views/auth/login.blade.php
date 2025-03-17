@extends('layouts.login')

@section('content')
<main>
    <section class="login-container">
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


                <a href="{{ route('register') }}">Register</a>

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
</main>

@endsection