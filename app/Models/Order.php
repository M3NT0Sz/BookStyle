<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'billing_address',
        'shipping_address',
        'notes',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'billing_address' => 'json',
        'shipping_address' => 'json',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Gerar número do pedido automaticamente
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');
            }
        });
    }

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com os itens do pedido
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calcular total do pedido baseado nos itens
     */
    public function calculateTotal(): float
    {
        return $this->orderItems->sum('subtotal');
    }

    /**
     * Atualizar o total do pedido
     */
    public function updateTotal(): void
    {
        $this->update(['total' => $this->calculateTotal()]);
    }

    /**
     * Verificar se o pedido pode ser cancelado
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Cancelar pedido
     */
    public function cancel(): void
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => 'cancelled']);
        }
    }

    /**
     * Marcar como enviado
     */
    public function markAsShipped(): void
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now()
        ]);
    }

    /**
     * Marcar como entregue
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    /**
     * Scope para pedidos de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para pedidos por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessor para formatar o status
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado'
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
