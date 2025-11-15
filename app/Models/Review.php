<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\SentimentAnalysisService;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'order_id',
        'rating',
        'comment',
        'images',
        'sentiment',
        'sentiment_score',
        'sentiment_confidence',
        'is_verified_purchase',
        'is_approved'
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'sentiment_score' => 'decimal:2',
        'sentiment_confidence' => 'decimal:2'
    ];

    /**
     * Boot do modelo - analisa sentimento automaticamente ao criar
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($review) {
            if (!empty($review->comment)) {
                $analysis = SentimentAnalysisService::analyze($review->comment);
                $review->sentiment = $analysis['sentiment'];
                $review->sentiment_score = $analysis['score'];
                $review->sentiment_confidence = $analysis['confidence'];
            }
        });
        
        static::updating(function ($review) {
            if ($review->isDirty('comment') && !empty($review->comment)) {
                $analysis = SentimentAnalysisService::analyze($review->comment);
                $review->sentiment = $analysis['sentiment'];
                $review->sentiment_score = $analysis['score'];
                $review->sentiment_confidence = $analysis['confidence'];
            }
        });
    }

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com livro
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relacionamento com pedido
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope para reviews aprovadas
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope para reviews verificadas
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Accessor para estrelas formatadas
     */
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Verificar se o review tem imagens
     */
    public function hasImages(): bool
    {
        return !empty($this->images) && is_array($this->images);
    }

    /**
     * Obter contagem de imagens
     */
    public function getImagesCount(): int
    {
        return $this->hasImages() ? count($this->images) : 0;
    }
}
