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



