<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'BookStyle - Livros')</title>
    
    <!-- Only load books-specific CSS -->
    @vite('resources/css/books.css')
    @stack('styles')
</head>

<body>
    <!-- Navigation Header -->
    <nav class="books-nav">
        <div class="nav-container">
            <div class="nav-left">
                <a href="{{ route('index') }}" class="nav-logo">
                    <img src="{{ Vite::asset('resources/img/logo1.png') }}" alt="BookStyle Logo">
                    <span>BookStyle</span>
                </a>
            </div>
            
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li><a href="{{ route('books.index') }}" class="active">Livros</a></li>
                    <li><a href="{{ route('about') }}">Sobre</a></li>
                </ul>
            </div>
            
            <div class="nav-right">
                @auth
                    <a href="{{ route('user.profile') }}" class="nav-profile">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <a href="#" class="nav-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-counter">0</span>
                    </a>
                @else
                    <a href="/login" class="nav-login">Entrar</a>
                    <a href="/register" class="nav-register">Cadastrar</a>
                @endauth
                
                <button class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Scripts -->
    @vite('resources/js/books.js')
    @stack('scripts')
</body>

</html>
