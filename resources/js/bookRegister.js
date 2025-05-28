// Remover ou proteger o código da .button-genre para evitar erro se não existir
const buttonGenre = document.querySelector('.button-genre');
if (buttonGenre) {
    buttonGenre.addEventListener('click', function() {
        const formGenre = document.querySelector('.form-genre');
        if (formGenre) formGenre.classList.toggle('active');
    });
}

// Multi-step e barra de progresso
window.addEventListener('DOMContentLoaded', function() {
    // Multi-step
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const progressBarFill = document.querySelector('.progress-bar-fill');
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    let currentStep = 0;

    function updateProgressBar(idx) {
        const total = progressSteps.length - 1;
        const percent = total === 0 ? 0 : (idx / total) * 100;
        progressBarFill.style.width = percent + '%';
    }

    function showStep(idx) {
        steps.forEach((step, i) => {
            step.classList.toggle('step-active', i === idx);
        });
        progressSteps.forEach((step, i) => {
            step.classList.toggle('step-active', i === idx);
            step.classList.toggle('step-done', i < idx);
        });
        updateProgressBar(idx);
    }

    function validateStep(idx) {
        // Validação simples por etapa
        let valid = true;
        const step = steps[idx];
        const requiredInputs = step.querySelectorAll('input[required], select[required], textarea[required]');
        requiredInputs.forEach(input => {
            if (!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.classList.add('input-error');
                if (valid) input.focus();
                valid = false;
            } else {
                input.classList.remove('input-error');
            }
        });
        // Validação customizada por etapa
        if (idx === 0) {
            // Nome e autor obrigatórios
            const nome = document.getElementById('name');
            const autor = document.getElementById('author');
            if (!nome.value.trim()) { nome.classList.add('input-error'); if(valid) nome.focus(); valid = false; }
            if (!autor.value.trim()) { autor.classList.add('input-error'); if(valid) autor.focus(); valid = false; }
        }
        if (idx === 1) {
            // Pelo menos um gênero
            const checkboxes = document.querySelectorAll('#genreOptions input[type="checkbox"]');
            let checked = false;
            checkboxes.forEach(cb => { if (cb.checked) checked = true; });
            if (!checked) {
                document.getElementById('genreOptions').classList.add('input-error');
                valid = false;
            } else {
                document.getElementById('genreOptions').classList.remove('input-error');
            }
        }
        if (idx === 2) {
            // Preço obrigatório
            const preco = document.getElementById('price');
            if (!preco.value.trim()) { preco.classList.add('input-error'); if(valid) preco.focus(); valid = false; }
        }
        return valid;
    }

    nextBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep < steps.length - 1) {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    });
    prevBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });
    showStep(currentStep);

    // Gêneros dinâmicos
    const genres = {
        'ficcao': [
            { value: 'fantasia', label: 'Fantasia' },
            { value: 'ficcao-cientifica', label: 'Ficção Científica' },
            { value: 'distopia-utopia', label: 'Distopia/Utopia' },
            { value: 'ficcao-historica', label: 'Ficção Histórica' },
            { value: 'ficcao-contemporanea', label: 'Ficção Contemporânea' },
            { value: 'ficcao-realista', label: 'Ficção Realista' },
            { value: 'romance', label: 'Romance' },
            { value: 'aventura', label: 'Aventura' },
            { value: 'terror-horror', label: 'Terror/Horror' },
            { value: 'suspense-thriller', label: 'Suspense/Thriller' },
            { value: 'policial-crime', label: 'Policial/Crime' },
            { value: 'western', label: 'Western' },
            { value: 'chick-lit', label: 'Chick-lit' }
        ],
        'nao-ficcao': [
            { value: 'biografia-autobiografia', label: 'Biografia/Autobiografia' },
            { value: 'memorias', label: 'Memórias' },
            { value: 'ensaios', label: 'Ensaios' },
            { value: 'autoajuda', label: 'Autoajuda e Desenvolvimento Pessoal' },
            { value: 'ciencia-tecnologia', label: 'Ciência e Tecnologia' },
            { value: 'historia', label: 'História' },
            { value: 'filosofia', label: 'Filosofia' },
            { value: 'religiao-espiritualidade', label: 'Religião e Espiritualidade' },
            { value: 'psicologia-psicanalise', label: 'Psicologia e Psicanálise' }
        ],
        'infantojuvenil': [
            { value: 'contos-fadas', label: 'Contos de Fadas' },
            { value: 'fabulas', label: 'Fábulas' },
            { value: 'livros-infantis', label: 'Livros Infantis Ilustrados' },
            { value: 'young-adult', label: 'Young Adult (YA)' },
            { value: 'middle-grade', label: 'Middle Grade (MG)' }
        ],
        'poesia': [
            { value: 'poesia', label: 'Poesia' },
            { value: 'teatro-drama', label: 'Teatro/Drama' }
        ],
        'hqs': [
            { value: 'mangas', label: 'Mangás' },
            { value: 'hqs', label: 'Histórias em Quadrinhos (HQs)' },
            { value: 'graphic-novels', label: 'Graphic Novels' }
        ]
    };
    const genreCategory = document.getElementById('genreCategory');
    const genreOptions = document.getElementById('genreOptions');
    function renderGenres(category) {
        genreOptions.innerHTML = '';
        if (genres[category]) {
            genres[category].forEach(g => {
                const label = document.createElement('label');
                label.innerHTML = `<input type='checkbox' name='genre[]' value='${g.value}'> ${g.label}`;
                genreOptions.appendChild(label);
            });
        }
    }
    genreCategory.addEventListener('change', function() {
        renderGenres(this.value);
    });
    renderGenres(genreCategory.value);

    // Preview de imagens
    const imagesInput = document.getElementById('images');
    if (imagesInput) {
        imagesInput.addEventListener('change', function(event) {
            let previewContainer = document.querySelector('.image-preview');
            previewContainer.innerHTML = '';
            Array.from(event.target.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Impede submit se houver campos obrigatórios não preenchidos na última etapa
    const form = document.getElementById('stepForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Última etapa
            if (!validateStep(steps.length - 1)) {
                e.preventDefault();
                showStep(steps.length - 1);
            }
        });
    }
});

