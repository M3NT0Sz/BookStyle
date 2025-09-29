<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Calcular subtotal automaticamente antes de salvar
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($orderItem) {
            $orderItem->subtotal = $orderItem->quantity * $orderItem->price;
        });
    }

    /**
     * Relacionamento com o pedido
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relacionamento com o livro
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Calcular subtotal
     */
    public function calculateSubtotal(): float
    {
        return $this->quantity * $this->price;
    }
}
