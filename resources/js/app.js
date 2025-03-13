import.meta.glob([
    '../img/**',
]);

document.addEventListener('DOMContentLoaded', function () {
    const booksContainer = document.querySelector('.books');
    const books = document.querySelectorAll('.book');
    const buttonLeft = document.querySelector('.button-left');
    const buttonRight = document.querySelector('.button-right');
    let currentIndex = 0;

    buttonLeft.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });

    buttonRight.addEventListener('click', () => {
        if (currentIndex < books.length - 4) {
            currentIndex++;
            updateCarousel();
        }
    });

    function updateCarousel() {
        const offset = -currentIndex * 25;
        booksContainer.style.transform = `translateX(${offset}%)`;
    }
});