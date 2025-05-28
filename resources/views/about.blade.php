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
            <li>
                <a href="{{ route('cart.index') }}">Carrinho</a>
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
        <img src="{{ Vite::asset('resources/img/wave.svg') }}" alt="">
    </div>
</header>

<main class="main_container">
    <section class="about-container">
        <div class="title_about">
            <h2>Sobre nós</h2>
        </div>
        <div class="text_about">
            <p>O BookStyle nasceu da paixão por livros e da vontade de tornar a leitura e a troca de livros algo simples e acessível.</p>

            <p>Acreditamos que cada obra carrega mais do que uma história: carrega também quem a leu e deseja compartilhá-la.</p>

            <p>Nosso objetivo é conectar leitores e vendedores em um espaço seguro, intuitivo e agradável.
            Seja para economizar, encontrar títulos especiais ou dar um novo destino a livros já lidos — o BookStyle é pra você.</p>

            <p>👥 SSomos estudantes apaixonados por tecnologia e literatura, criando essa plataforma com carinho para quem ama histórias.</p>

            <p>✨ <em>Junte-se a nós e espalhe o poder da leitura!</em></p>
        </div>
    </section>


    <section class="mv-container">
        <h3>Nosso Propósito</h3>
        <ul>
            <li><strong>Missão:</strong> Democratizar o acesso à leitura e incentivar o compartilhamento de histórias.
            </li>
            <li><strong>Visão:</strong> Ser a principal plataforma brasileira de troca e venda de livros usados.</li>
            <li><strong>Valores:</strong> Comunidade, Sustentabilidade, Acessibilidade e Paixão pela leitura.</li>
        </ul>
    </section>

    <section class="team-container">
        <h3>Nosso Time</h3>
        <div class="team-members">
            <div class="member">
                <img src="{{ Vite::asset('resources/img/Paulo.png') }}" alt="Foto do Paulo" />
                <h4>Paulo Diney</h4>
                <p><span>Front-end Developer</span>, estudante de ADS e apaixonado por livros de ficção!</p>
            </div>
            <div class="member">
                <img src="{{ Vite::asset('resources/img/matheus.png') }}" alt="Foto do colega" />
                <h4>Matheus Mendes</h4>
                <p><span>Back-end Developer</span>, responsável pela lógica do sistema e integração dos dados.</p>
            </div>
        </div>
    </section>

    <section class="depoimentos">
        <h3>O que estão dizendo...</h3>
        <blockquote>"Finalmente encontrei aquele livro esgotado há anos! Obrigado BookStyle ❤️" – Ana, leitora desde
            2025</blockquote>
        <blockquote>"Vendi meus livros parados e ganhei espaço e grana. Melhor ideia!" – Lucas, vendedor independente
        </blockquote>
    </section>

    <section class="dados">
        <h3>Em números</h3>
        <ul>
            <li>+500 livros cadastrados</li>
            <li>+200 usuários ativos</li>
            <li>+100 trocas realizadas</li>
        </ul>
    </section>

    <section class="curiosidades">
        <h3>Sabia que...?</h3>
        <ul>
            <li>O nome BookStyle surgiu durante uma aula de Molagens e Padroes de projetos</li>
            <li>O sistema foi todo desenvolvido por estudantes, do zero</li>
            <li>Até o logo foi desenhado por nós!</li>
        </ul>
    </section>




</main>

<footer class="footer-container">
        <section class="footer-content">
            <div class="footer-left">
                <img src="{{ Vite::asset('resources/img/favicon.png') }}" alt="">
                <h1>BookStyle</h1>
                <p>Encontre suas Histórias</p>
            </div>
            <div class="footer-center">
                <div class="footer-list">
                    <h2>Blogs</h2>
                    <ul class="footer-links">
                        <li>
                            <a href="#">Comunidade</a>
                        </li>
                        <li>
                            <a href="#">Livros</a>
                        </li>
                        <li>
                            <a href="#">Historias</a>
                        </li>
                    </ul>
                </div>

                <div class="footer-list">
                    <h2>Products</h2>
                    <ul class="footer-links">
                        <li>Livros</li>
                        <li>HQs</li>
                        <li>Books</li>
                    </ul>
                </div>

            </div>
            <div class="footer-right">
                <h1>Contato</h1>
                <p>Caso exista alguma duviva ou problema entra em contato com nós atraves do E-mail</p>
                
                <a href="mailto:contato@bookstyle.com" class="footer-email">contato@bookstyle.com</a>

            </div>
        </section>

        <p class="footer-bottom">@2025 BookStyle. Todos os direitos reservados.</p>

    </footer>