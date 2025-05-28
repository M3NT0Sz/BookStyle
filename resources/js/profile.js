document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggle-books');
    const containerBook = document.getElementById('container-book');

    toggleButton.addEventListener('click', () => {
        containerBook.style.transition = 'all 0.5s ease-in-out';
        containerBook.classList.toggle('show');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.body.style.opacity = 0;
    setTimeout(() => {
        document.body.style.transition = 'opacity 1s';
        document.body.style.opacity = 1;
    }, 100);
});

document.addEventListener('DOMContentLoaded', () => {
    const navObjects = document.querySelectorAll('.nav_objects');
    const ball = document.querySelector('.ball');

    navObjects.forEach((item, index) => {
        item.addEventListener('click', function () {
            // Remove a classe 'active' de todos os itens
            navObjects.forEach((obj) => obj.classList.remove('active'));

            // Adiciona a classe 'active' ao item clicado
            this.classList.add('active');

            // Move a bola para o item clicado
            const ballPosition = index * 175; // Ajuste o valor conforme necessário
            ball.style.transform = `translateX(${ballPosition}px)`;
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.nav_objects a');
    const sections = document.querySelectorAll('main > section:not(#container-book)'); // Exclui container-book
    const containerBook = document.getElementById('container-book');
    const myMenu = document.getElementById('livros');

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();

            // Remove 'visible' de todas as seções e adiciona 'hidden'
            sections.forEach(section => {
                section.classList.remove('visible');
                section.classList.add('hidden');
            });

            // Mostra a seção correspondente ao link clicado
            const targetId = link.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.remove('hidden');
                targetSection.classList.add('visible');
            }

            // Adiciona ou remove a classe 'show' do container-book com base na visibilidade de my-menu
            if (myMenu.classList.contains('visible')) {
                containerBook.classList.add('show');
            } else {
                containerBook.classList.remove('show');
            }
        });
    });
});

// NOVO: Destacar botão ativo na navegação do perfil

document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.profile-nav-link');
    const sections = document.querySelectorAll('.profile-section');

    function setActiveSection(hash) {
        navLinks.forEach(link => {
            link.classList.remove('active');
            // Remove fundo azul de todos
            link.style.background = '#ecf0f1';
            link.style.color = '#3498db';
        });
        sections.forEach(section => {
            section.classList.remove('active');
        });
        // Ativa o link e a seção correspondente
        const activeLink = Array.from(navLinks).find(link => link.getAttribute('href') === hash);
        const activeSection = document.querySelector(hash);
        if (activeLink) {
            activeLink.classList.add('active');
            activeLink.style.background = '#3498db';
            activeLink.style.color = '#fff';
        }
        if (activeSection) {
            activeSection.classList.add('active');
        }
    }

    // Ao clicar em um link
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const hash = this.getAttribute('href');
            history.replaceState(null, '', hash);
            setActiveSection(hash);
        });
    });

    // Ao carregar a página, ativa a seção correta
    setActiveSection(window.location.hash || '#dados');
});



