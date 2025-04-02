const toggleButton = document.getElementById('toggleFilter');
const filterContainer = document.querySelector('.filter_containers');
const toggleIcon = document.getElementById('toggleIcon');

let isOpen = false;

toggleButton.addEventListener('click', () => {
    isOpen = !isOpen;
    filterContainer.classList.toggle('open', isOpen);
    
    if (isOpen) {
        toggleIcon.classList.replace('fa-book', 'fa-book-open');
    } else {
        toggleIcon.classList.replace('fa-book-open', 'fa-book');
    }
});
