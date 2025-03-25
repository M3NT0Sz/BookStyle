import.meta.glob([
    '../img/**',
]);

document.addEventListener("DOMContentLoaded", function () {
    const booksContainer = document.querySelector(".pages-book");
    const books = Array.from(document.querySelectorAll(".book"));
    const nextBtn = document.querySelector(".next");
    const prevBtn = document.querySelector(".prev");
    const booksPerPage = 3;

    function updateCarousel() {
        booksContainer.innerHTML = "";
        books.forEach(book => {
            booksContainer.appendChild(book);
            book.style.transition = "transform 0.3s ease-in-out, opacity 0.5s ease-in-out";
            book.addEventListener("mouseover", () => {
                book.style.transform = "scale(1.05)";
            });
            book.addEventListener("mouseout", () => {
                book.style.transform = "scale(1)";
            });
        });
    }

    function fadeAndMove(direction) {
        const bookToFade = direction === "next" ? books[0] : books[books.length - 1];
        bookToFade.style.opacity = "0";
        bookToFade.style.transform = "scale(0.9)";

        setTimeout(() => {
            if (direction === "next") {
                const firstBook = books.shift();
                books.push(firstBook);
            } else {
                const lastBook = books.pop();
                books.unshift(lastBook);
            }
            updateCarousel();
            const newBook = direction === "next" ? books[books.length - 1] : books[0];
            newBook.style.opacity = "0";
            newBook.style.transform = "scale(0.9)";
            setTimeout(() => {
                newBook.style.opacity = "1";
                newBook.style.transform = "scale(1)";
            }, 100);
        }, 500);
    }

    nextBtn.addEventListener("click", () => fadeAndMove("next"));
    prevBtn.addEventListener("click", () => fadeAndMove("prev"));

    booksContainer.style.display = "flex";
    booksContainer.style.overflow = "hidden";
    updateCarousel();
});


