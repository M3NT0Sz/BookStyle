<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'price_alert',
        'notified'
    ];

    protected $casts = [
        'price_alert' => 'decimal:2',
        'notified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento: Wishlist pertence a um usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Wishlist pertence a um livro
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Verificar se o preço do livro está abaixo do alerta
     */
    public function isPriceBelowAlert(): bool
    {
        if (!$this->price_alert) {
            return false;
        }

        return $this->book->price <= $this->price_alert;
    }

    /**
     * Scope: Itens com alerta de preço ativo
     */
    public function scopeWithPriceAlert($query)
    {
        return $query->whereNotNull('price_alert')
                    ->where('notified', false);
    }

    /**
     * Scope: Itens de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Marcar como notificado
     */
    public function markAsNotified(): void
    {
        $this->notified = true;
        $this->save();
    }
}
