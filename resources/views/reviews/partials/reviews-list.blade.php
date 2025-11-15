<div class="reviews-section">
    <div class="reviews-header">
        <div class="reviews-header-top">
            <h3 class="reviews-title">
                <i class="fas fa-star"></i>
                Avaliações de Clientes
            </h3>
            
            @auth
                @if(isset($userCanReview) && $userCanReview)
                    <a href="{{ route('reviews.create', ['order' => $userOrderId, 'book' => $bookId]) }}" class="btn-write-review">
                        <i class="fas fa-edit"></i>
                        Avaliar Produto
                    </a>
                @endif
            @endauth
        </div>
        
        @if($totalReviews > 0)
            <div class="rating-summary">
                <div class="rating-score">
                    <div class="score-number">{{ number_format($averageRating, 1) }}</div>
                    <div class="score-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= round($averageRating) ? 'filled' : '' }}"></i>
                        @endfor
                    </div>
                    <div class="score-text">{{ $totalReviews }} {{ $totalReviews == 1 ? 'avaliação' : 'avaliações' }}</div>
                </div>
                
                <div class="rating-bars">
                    @foreach($ratingDistribution as $stars => $data)
                        <div class="rating-bar-item">
                            <span class="bar-label">{{ $stars }} <i class="fas fa-star"></i></span>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: {{ $data['percentage'] }}%"></div>
                            </div>
                            <span class="bar-count">{{ $data['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="no-reviews">Seja o primeiro a avaliar este produto!</p>
        @endif
    </div>

    @if($reviews->count() > 0)
        <div class="reviews-list">
            @foreach($reviews as $review)
                <div class="review-item">
                    <div class="review-header-item">
                        <div class="reviewer-info">
                            <img src="{{ $review->user->image ? asset('storage/' . $review->user->image) : asset('images/perfil.png') }}" 
                                 alt="{{ $review->user->name }}" 
                                 class="reviewer-avatar">
                            <div>
                                <div class="reviewer-name">{{ $review->user->name }}</div>
                                <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                            @endfor
                            @if($review->is_verified_purchase)
                                <span class="verified-badge">
                                    <i class="fas fa-check-circle"></i> Compra Verificada
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="review-content">
                        <p class="review-comment">{{ $review->comment }}</p>
                        
                        @if($review->hasImages())
                            <div class="review-images">
                                @foreach($review->images as $image)
                                    <img src="{{ $image }}" 
                                         alt="Foto do produto" 
                                         class="review-image"
                                         onclick="openImageModal('{{ $image }}')">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->is_admin))
                        <div class="review-actions">
                            <form action="{{ route('reviews.destroy', $review->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete-review">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="reviews-pagination">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

<!-- Modal para visualizar imagem -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<style>
.reviews-section {
    margin-top: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.reviews-header {
    margin-bottom: 2rem;
}

.reviews-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.reviews-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.btn-write-review {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-write-review:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.rating-summary {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
}

.rating-score {
    text-align: center;
    padding: 1rem;
}

.score-number {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

.score-stars {
    margin: 0.5rem 0;
}

.score-stars i {
    color: #e5e7eb;
    font-size: 1.2rem;
}

.score-stars i.filled {
    color: #fbbf24;
}

.score-text {
    color: #64748b;
    font-size: 0.9rem;
}

.rating-bars {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.rating-bar-item {
    display: grid;
    grid-template-columns: 50px 1fr 40px;
    align-items: center;
    gap: 1rem;
}

.bar-label {
    font-weight: 600;
    color: #64748b;
    font-size: 0.9rem;
}

.bar-label i {
    color: #fbbf24;
    font-size: 0.8rem;
}

.bar-container {
    height: 8px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(45deg, #fbbf24, #f59e0b);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.bar-count {
    text-align: right;
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
}

.no-reviews {
    text-align: center;
    color: #64748b;
    font-style: italic;
    padding: 2rem;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.review-item {
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 15px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s;
}

.review-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.review-header-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.reviewer-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.reviewer-name {
    font-weight: 700;
    color: #1e293b;
}

.review-date {
    font-size: 0.85rem;
    color: #64748b;
}

.review-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.review-rating i {
    color: #e5e7eb;
    font-size: 1.1rem;
}

.review-rating i.filled {
    color: #fbbf24;
}

.verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    background: #d1fae5;
    color: #065f46;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

.review-content {
    margin-top: 1rem;
}

.review-comment {
    color: #1e293b;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.review-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
}

.review-image {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.review-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.review-actions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.btn-delete-review {
    background: #fee2e2;
    color: #991b1b;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-delete-review:hover {
    background: #fecaca;
}

/* Modal de imagem */
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
    cursor: pointer;
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 90%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 10px;
}

.close-modal {
    position: absolute;
    top: 20px;
    right: 40px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

.reviews-pagination {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .rating-summary {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .review-header-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .review-images {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'block';
    modalImg.src = imageSrc;
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
