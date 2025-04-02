@extends('layouts.about')

@section('content')
<header class="header-container">
    <nav class="nav-container">
        <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                alt=""></a>
        <ul class="nav-links">
            <li>
                <a href="{{ route('index') }}">Home</a>
            </li>
            <li>
                <a href="{{ route('about') }}">Quem Somos</a>
            </li>
            <li>
                <a href="{{route('books.index') }}">Livros</a>
            </li>
        </ul>

        @if(Auth::check())
            <a class="button-login" href="{{ route('user.profile') }}">Perfil</a>
        @else
            <a class="button-login" href="{{ route('login') }}">Entrar</a>
        @endif
    </nav>

    <div class="banner_container">
        <h1>BookStyle</h1>
        <p>Encontre suas Histórias </p>

    </div>
    <div class="elemento">
        <img src="{{ Vite::asset('resources/img/wave.svg') }}" alt="">
    </div>
</header>
<main>

</main>