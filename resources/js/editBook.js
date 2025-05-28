// JS para melhorar a experiência do formulário de edição de livros

document.addEventListener('DOMContentLoaded', function () {
    // Preview do nome do(s) arquivo(s) selecionado(s)
    const fileInput = document.getElementById('images');
    const previewContainer = document.querySelector('.image-preview');

    if (fileInput && previewContainer) {
        fileInput.addEventListener('change', function (e) {
            previewContainer.innerHTML = '';
            Array.from(fileInput.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = 'preview-img';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Botão para rolar até gêneros
    const genreBtn = document.querySelector('.button-genre');
    const genreSection = document.querySelector('.form-genre-group');
    if (genreBtn && genreSection) {
        genreBtn.addEventListener('click', function () {
            genreSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            genreSection.classList.add('highlight-genre');
            setTimeout(() => genreSection.classList.remove('highlight-genre'), 1200);
        });
    }
});
