<nav class="nav-container">
    <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}" alt=""></a>
    <ul class="nav-links">
        <li><a href="{{ route('index') }}">Home</a></li>
        <li><a href="{{ route('about') }}">Quem Somos</a></li>
        <li><a href="{{ route('books.index') }}">Livros</a></li>
        <li><a href="{{ route('cart.index') }}">Carrinho</a></li>
    </ul>
    @if(Auth::check())
        <a class="button-login" href="{{ route('user.profile') }}">Perfil</a>
    @else
        <a class="button-login" href="{{ route('login') }}">Entrar</a>
    @endif
</nav>
