@extends('layouts.about')

@section('content')
<header class="header-container">
    <nav class="nav-container">
        <a href="{{ route('index') }}"><img class="logo" src="{{ Vite::asset('resources/img/favicon.png') }}"
                alt="BookStyle Logo"></a>
        <ul class="nav-links">
            <li>
                <a href="{{ route('index') }}">Home</a>
            </li>
            <li>
                <a href="{{ route('about') }}" class="active">Quem Somos</a>
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
        <div class="banner-content">
            <h1 class="animate-title">BookStyle</h1>
            <p class="animate-subtitle">Conectando histórias, conectando pessoas</p>
            <div class="banner-stats">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Livros</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">200+</span>
                    <span class="stat-label">Usuários</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100+</span>
                    <span class="stat-label">Trocas</span>
                </div>
            </div>
        </div>
    </div>
    <div class="elemento">
        <img src="{{ Vite::asset('resources/img/wave.svg') }}" alt="Wave decoration">
        <img src="{{ Vite::asset('resources/img/wave.svg') }}" alt="Wave decoration">
    </div>
</header>

<main class="main_container">
    <!-- Seção About -->
    <section class="about-container fade-in">
        <div class="about-content">
            <div class="about-text">
                <h2 class="section-title">
                    <i class="fas fa-book-open"></i>
                    Nossa História
                </h2>
                <div class="text_about">
                    <p class="highlight-text">O BookStyle nasceu da paixão por livros e da vontade de tornar a leitura e a troca de livros algo simples e acessível para todos.</p>

                    <p>Acreditamos que cada obra carrega mais do que uma história: carrega também a alma de quem a leu e deseja compartilhá-la com o mundo.</p>

                    <p>Nosso objetivo é conectar leitores e vendedores em um espaço <strong>seguro</strong>, <strong>intuitivo</strong> e <strong>agradável</strong>. Seja para economizar, encontrar títulos especiais ou dar um novo destino a livros já lidos — o BookStyle é para você.</p>

                    <div class="team-intro">
                        <p><i class="fas fa-graduation-cap"></i> Somos estudantes apaixonados por tecnologia e literatura, criando essa plataforma com carinho para quem ama histórias.</p>
                        
                        <p class="cta-text"><i class="fas fa-heart"></i> <em>Junte-se a nós e espalhe o poder da leitura!</em></p>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <div class="image-placeholder">
                    <i class="fas fa-book-reader"></i>
                    <p>A comunidade que ama livros</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção Missão, Visão e Valores -->
    <section class="mv-container fade-in">
        <h3 class="section-title">
            <i class="fas fa-bullseye"></i>
            Nosso Propósito
        </h3>
        <div class="mv-grid">
            <div class="mv-item">
                <div class="mv-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h4>Missão</h4>
                <p>Democratizar o acesso à leitura e incentivar o compartilhamento de histórias entre pessoas apaixonadas por livros.</p>
            </div>
            <div class="mv-item">
                <div class="mv-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h4>Visão</h4>
                <p>Ser a principal plataforma brasileira de troca e venda de livros usados, criando uma comunidade sustentável de leitores.</p>
            </div>
            <div class="mv-item">
                <div class="mv-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h4>Valores</h4>
                <p>Comunidade, Sustentabilidade, Acessibilidade e Paixão pela leitura são os pilares que nos guiam.</p>
            </div>
        </div>
    </section>

    <!-- Seção da Equipe -->
    <section class="team-container fade-in">
        <h3 class="section-title">
            <i class="fas fa-users"></i>
            Nosso Time
        </h3>
        <p class="team-subtitle">Conheça as mentes criativas por trás do BookStyle</p>
        <div class="team-members">
            <div class="member">
                <div class="member-image">
                    <img src="{{ Vite::asset('resources/img/Paulo.png') }}" alt="Foto do Paulo" />
                    <div class="member-overlay">
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="member-info">
                    <h4>Paulo Diney</h4>
                    <span class="member-role">Front-end Developer</span>
                    <p>Estudante de ADS apaixonado por livros de ficção e criação de interfaces incríveis!</p>
                    <div class="member-skills">
                        <span class="skill">HTML/CSS</span>
                        <span class="skill">JavaScript</span>
                        <span class="skill">UI/UX</span>
                    </div>
                </div>
            </div>
            <div class="member">
                <div class="member-image">
                    <img src="{{ Vite::asset('resources/img/matheus.png') }}" alt="Foto do Matheus" />
                    <div class="member-overlay">
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="member-info">
                    <h4>Matheus Mendes</h4>
                    <span class="member-role">Back-end Developer</span>
                    <p>Responsável pela lógica do sistema, integração dos dados e arquitetura robusta da plataforma.</p>
                    <div class="member-skills">
                        <span class="skill">PHP</span>
                        <span class="skill">Laravel</span>
                        <span class="skill">MySQL</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Depoimentos -->
    <section class="depoimentos fade-in">
        <h3 class="section-title">
            <i class="fas fa-quote-left"></i>
            O que estão dizendo...
        </h3>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Finalmente encontrei aquele livro esgotado há anos! A plataforma é super intuitiva e o atendimento é excelente. Recomendo muito!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="author-info">
                        <span class="author-name">Ana Silva</span>
                        <span class="author-role">Leitora apaixonada</span>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Vendi meus livros parados e além de ganhar um dinheiro extra, ainda dei uma nova vida para eles. Melhor ideia que já tive!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="author-info">
                        <span class="author-name">Lucas Costa</span>
                        <span class="author-role">Vendedor independente</span>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Como professora, adoro poder encontrar livros didáticos usados por preços acessíveis. Isso me permite variar mais o material das aulas!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="author-info">
                        <span class="author-name">Prof. Maria</span>
                        <span class="author-role">Educadora</span>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Estatísticas -->
    <section class="stats-section fade-in">
        <h3 class="section-title">
            <i class="fas fa-chart-line"></i>
            BookStyle em Números
        </h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number" data-target="500">0</div>
                <div class="stat-label">Livros Cadastrados</div>
                <div class="stat-description">Títulos únicos disponíveis na plataforma</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number" data-target="200">0</div>
                <div class="stat-label">Usuários Ativos</div>
                <div class="stat-description">Comunidade crescendo a cada dia</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-number" data-target="100">0</div>
                <div class="stat-label">Trocas Realizadas</div>
                <div class="stat-description">Livros que encontraram novos lares</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="stat-number" data-target="50">0</div>
                <div class="stat-label">Kg de Papel Reutilizado</div>
                <div class="stat-description">Contribuição para um mundo mais sustentável</div>
            </div>
        </div>
    </section>

    <!-- Seção de Curiosidades -->
    <section class="curiosidades fade-in">
        <h3 class="section-title">
            <i class="fas fa-lightbulb"></i>
            Você Sabia?
        </h3>
        <div class="curiosities-grid">
            <div class="curiosity-card">
                <div class="curiosity-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4>Origem Acadêmica</h4>
                <p>O nome BookStyle surgiu durante uma aula de "Modelagem e Padrões de Projetos", quando estávamos discutindo diferentes padrões de design!</p>
            </div>
            <div class="curiosity-card">
                <div class="curiosity-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h4>100% Estudantil</h4>
                <p>Todo o sistema foi desenvolvido por estudantes, do zero! Cada linha de código foi escrita com muito café e dedicação.</p>
            </div>
            <div class="curiosity-card">
                <div class="curiosity-icon">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h4>Design Próprio</h4>
                <p>Até mesmo nosso logo foi desenhado por nós! Acreditamos que cada detalhe faz a diferença na experiência do usuário.</p>
            </div>
            <div class="curiosity-card">
                <div class="curiosity-icon">
                    <i class="fas fa-recycle"></i>
                </div>
                <h4>Sustentabilidade</h4>
                <p>A cada livro reutilizado, estamos contribuindo para reduzir o desperdício e promover a economia circular!</p>
            </div>
        </div>
    </section>

    <!-- Seção FAQ -->
    <section class="faq-section fade-in">
        <h3 class="section-title">
            <i class="fas fa-question-circle"></i>
            Perguntas Frequentes
        </h3>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Como funciona a troca de livros?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Você pode cadastrar seus livros na plataforma e navegar pelos livros disponíveis de outros usuários. Quando encontrar algo interessante, pode propor uma troca ou compra diretamente pelo sistema.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>A plataforma é segura para transações?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Sim! Implementamos várias camadas de segurança e verificação. Além disso, todas as transações são mediadas pela plataforma para garantir a segurança de ambas as partes.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Posso vender livros além de trocar?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Claro! Além de trocas, nossa plataforma também permite a venda de livros. Você define o preço e as condições de venda.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Como entro em contato com o suporte?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Você pode nos contatar através do email contato@bookstyle.com ou pelas redes sociais. Respondemos em até 24 horas!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section fade-in">
        <div class="cta-content">
            <h3>Pronto para começar sua jornada literária?</h3>
            <p>Junte-se à nossa comunidade e descubra um mundo de possibilidades para seus livros!</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="cta-button primary">
                    <i class="fas fa-user-plus"></i>
                    Criar Conta Gratuita
                </a>
                <a href="{{ route('books.index') }}" class="cta-button secondary">
                    <i class="fas fa-search"></i>
                    Explorar Livros
                </a>
            </div>
        </div>
        <div class="cta-decoration">
            <i class="fas fa-book-open"></i>
        </div>
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