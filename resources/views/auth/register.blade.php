@extends('layouts.register')

@section('content')
    <main>
        <section class="book-left">

        </section>
        <section class="form-book">
            <section class="form-container">
                <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                        alt=""></a>
                <h1>Registrar</h1>
                <form action="{{ route('register') }}" method="post">
                    @csrf

                    <label class="nome" for="name" id="nameLabel">Nome</label>
                    <input type="text" placeholder="Nome" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                        <div>{{ $message }}</div>
                    @enderror


                    <label class="email" for="email" id="emailLabel">Email</label>
                    <input type="email" placeholder="Email" name="email" id="email" value="{{ old('email') }}">
                    @error('email')
                        <div>{{ $message }}</div>
                    @enderror

                    <label class="senha" for="password" id="passwordLabel">Senha</label>
                    <input type="password" placeholder="Senha" name="password" id="password">
                    @error('password')
                        <div>{{ $message }}</div>
                    @enderror


                    <label class="conf-senha" for="password_confirmation" id="passwordConfirmationLabel">Confirmar
                        Senha</label>
                    <input type="password" placeholder="Confirmar senha" name="password_confirmation"
                        id="password_confirmation">


                    <button type="submit">Register</button>

                    <a href="{{ route('login') }}">Login</a>
                </form>
            </section>

        </section>
    </main>

@endsection