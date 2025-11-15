@extends('layouts.app')

@section('title', 'Avaliar Produto')

@section('content')
<style>
.review-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 0;
}

.review-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
}

.review-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 3rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.review-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.review-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.review-subtitle {
    color: #64748b;
    font-size: 1rem;
}

/* Informações do produto */
.product-info {
    display: flex;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.product-image {
    width: 100px;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.product-author {
    color: #64748b;
    margin-bottom: 0.25rem;
}

.product-order {
    color: #667eea;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Sistema de estrelas */
.rating-section {
    margin-bottom: 2rem;
}

.section-label {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.star-rating {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 1rem;
}

.star-input {
    display: none;
}

.star-label {
    font-size: 3rem;
    color: #e5e7eb;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.star-label:hover,
.star-label.active {
    color: #fbbf24;
    transform: scale(1.2);
}

.rating-text {
    text-align: center;
    font-size: 1.1rem;
    font-weight: 600;
    color: #667eea;
    min-height: 30px;
}

/* Comentário */
.comment-section {
    margin-bottom: 2rem;
}

.form-control {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 150px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.char-count {
    text-align: right;
    font-size: 0.85rem;
    color: #64748b;
    margin-top: 0.5rem;
}

/* Upload de imagens */
.images-section {
    margin-bottom: 2rem;
}

.upload-area {
    border: 2px dashed #e5e7eb;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f8fafc;
}

.upload-area:hover {
    border-color: #667eea;
    background: #f1f5f9;
}

.upload-icon {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.upload-text {
    color: #64748b;
    margin-bottom: 0.5rem;
}

.upload-hint {
    font-size: 0.85rem;
    color: #9ca3af;
}

.file-input {
    display: none;
}

/* Preview das imagens */
.image-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.preview-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ef4444;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.remove-image:hover {
    background: #dc2626;
    transform: scale(1.1);
}

/* Botões */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: white;
    color: #64748b;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    border-color: #667eea;
    color: #667eea;
}

/* Alerta */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
}

/* Responsividade */
@media (max-width: 768px) {
    .review-container {
        padding: 0 1rem;
    }
    
    .review-card {
        padding: 2rem 1.5rem;
    }
    
    .product-info {
        flex-direction: column;
        text-align: center;
    }
    
    .star-label {
        font-size: 2.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="review-wrapper">
    <div class="review-container">
        <div class="review-card">
            <div class="review-header">
                <h1 class="review-title">
                    <i class="fas fa-star"></i>
                    Avaliar Produto
                </h1>
                <p class="review-subtitle">Compartilhe sua experiência com este produto</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informações do Produto -->
            <div class="product-info">
                @php
                    $bookImages = [];
                    if (isset($book->images)) {
                        if (is_string($book->images)) {
                            $decoded = json_decode($book->images, true);
                            $bookImages = is_array($decoded) ? $decoded : [];
                        } elseif (is_array($book->images)) {
                            $bookImages = $book->images;
                        }
                    }
                    $firstImage = !empty($bookImages) ? $bookImages[0] : null;
                @endphp
                
                @if($firstImage)
                    <img src="{{ asset('storage/' . $firstImage) }}" 
                         alt="{{ $book->name }}" 
                         class="product-image">
                @else
                    <div class="product-image" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                        <i class="fas fa-book" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <div class="product-details">
                    <h2 class="product-name">{{ $book->name }}</h2>
                    <p class="product-author">{{ $book->author }}</p>
                    <p class="product-order">Pedido #{{ $order->id }} - {{ $order->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Formulário de Avaliação -->
            <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" id="reviewForm">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <!-- Sistema de Estrelas -->
                <div class="rating-section">
                    <label class="section-label">
                        <i class="fas fa-star"></i>
                        Sua Avaliação *
                    </label>
                    <div class="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" 
                                   name="rating" 
                                   value="{{ $i }}" 
                                   id="star{{ $i }}" 
                                   class="star-input"
                                   required>
                            <label for="star{{ $i }}" 
                                   class="star-label" 
                                   data-rating="{{ $i }}">★</label>
                        @endfor
                    </div>
                    <div class="rating-text" id="ratingText"></div>
                    @error('rating')
                        <div style="color: #ef4444; text-align: center; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Comentário -->
                <div class="comment-section">
                    <label class="section-label" for="comment">
                        <i class="fas fa-comment-alt"></i>
                        Seu Comentário *
                    </label>
                    <textarea name="comment" 
                              id="comment" 
                              class="form-control" 
                              placeholder="Conte-nos o que você achou do produto... (mínimo 10 caracteres)"
                              required
                              minlength="10"
                              maxlength="1000">{{ old('comment') }}</textarea>
                    <div class="char-count">
                        <span id="charCount">0</span> / 1000 caracteres
                    </div>
                    @error('comment')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload de Imagens -->
                <div class="images-section">
                    <label class="section-label">
                        <i class="fas fa-images"></i>
                        Fotos do Produto (Opcional)
                    </label>
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="upload-text">Clique ou arraste fotos aqui</div>
                        <div class="upload-hint">PNG, JPG ou WEBP (máx. 2MB cada)</div>
                    </div>
                    <input type="file" 
                           name="images[]" 
                           id="imageInput" 
                           class="file-input" 
                           accept="image/png,image/jpeg,image/jpg,image/webp"
                           multiple>
                    <div class="image-preview" id="imagePreview"></div>
                    @error('images.*')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="form-actions">
                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sistema de estrelas
    const starLabels = document.querySelectorAll('.star-label');
    const starInputs = document.querySelectorAll('.star-input');
    const ratingText = document.getElementById('ratingText');
    
    const ratingTexts = {
        1: '⭐ Muito Ruim',
        2: '⭐⭐ Ruim',
        3: '⭐⭐⭐ Regular',
        4: '⭐⭐⭐⭐ Bom',
        5: '⭐⭐⭐⭐⭐ Excelente'
    };
    
    starLabels.forEach((label, index) => {
        label.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            highlightStars(rating);
            ratingText.textContent = ratingTexts[rating];
        });
        
        label.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('star' + rating).checked = true;
            highlightStars(rating);
            ratingText.textContent = ratingTexts[rating];
        });
    });
    
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        const checkedStar = document.querySelector('.star-input:checked');
        if (checkedStar) {
            const rating = checkedStar.value;
            highlightStars(rating);
            ratingText.textContent = ratingTexts[rating];
        } else {
            starLabels.forEach(label => label.classList.remove('active'));
            ratingText.textContent = '';
        }
    });
    
    function highlightStars(rating) {
        starLabels.forEach((label, index) => {
            if (index < rating) {
                label.classList.add('active');
            } else {
                label.classList.remove('active');
            }
        });
    }
    
    // Contador de caracteres
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');
    
    commentTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Inicializar contador
    charCount.textContent = commentTextarea.value.length;
    
    // Upload de imagens
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    let selectedFiles = [];
    
    uploadArea.addEventListener('click', () => imageInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#667eea';
        uploadArea.style.background = '#f1f5f9';
    });
    
    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#e5e7eb';
        uploadArea.style.background = '#f8fafc';
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#e5e7eb';
        uploadArea.style.background = '#f8fafc';
        
        const files = Array.from(e.dataTransfer.files).filter(file => 
            file.type.match('image.*')
        );
        handleFiles(files);
    });
    
    imageInput.addEventListener('change', (e) => {
        handleFiles(Array.from(e.target.files));
    });
    
    function handleFiles(files) {
        selectedFiles = [...selectedFiles, ...files].slice(0, 5); // Máximo 5 imagens
        updateImagePreview();
        updateFileInput();
    }
    
    function updateImagePreview() {
        imagePreview.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" class="preview-image" alt="Preview">
                    <button type="button" class="remove-image" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
    
    imagePreview.addEventListener('click', (e) => {
        if (e.target.closest('.remove-image')) {
            const index = parseInt(e.target.closest('.remove-image').dataset.index);
            selectedFiles.splice(index, 1);
            updateImagePreview();
            updateFileInput();
        }
    });
    
    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }
    
    // Validação do formulário
    const form = document.getElementById('reviewForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        const rating = document.querySelector('.star-input:checked');
        const comment = commentTextarea.value.trim();
        
        if (!rating) {
            e.preventDefault();
            alert('Por favor, selecione uma avaliação de 1 a 5 estrelas.');
            return;
        }
        
        if (comment.length < 10) {
            e.preventDefault();
            alert('O comentário deve ter no mínimo 10 caracteres.');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    });
});
</script>

@endsection
