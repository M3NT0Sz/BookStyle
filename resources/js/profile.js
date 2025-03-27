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



