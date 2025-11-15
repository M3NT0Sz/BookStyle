<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'is_admin',
        'last_coupon_used_at',
        'coupons_cooldown_days',
    ];

    protected $casts = [
        'last_coupon_used_at' => 'datetime',
        'coupons_cooldown_days' => 'integer',
        'is_admin' => 'boolean',
    ];

    /**
     * Relacionamento com itens do carrinho
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Relacionamento com pedidos
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relacionamento com livros (que o usuário cadastrou)
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Relacionamento com avaliações
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relacionamento com notificações
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Obter notificações não lidas
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread()->orderBy('created_at', 'desc');
    }

    /**
     * Contar notificações não lidas
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Relacionamento com lista de desejos (wishlist)
     */
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Verificar se um livro está na wishlist
     */
    public function hasInWishlist($bookId): bool
    {
        return $this->wishlist()->where('book_id', $bookId)->exists();
    }

    /**
     * Adicionar livro à wishlist
     */
    public function addToWishlist($bookId, $priceAlert = null): Wishlist
    {
        return $this->wishlist()->create([
            'book_id' => $bookId,
            'price_alert' => $priceAlert
        ]);
    }

    /**
     * Remover livro da wishlist
     */
    public function removeFromWishlist($bookId): bool
    {
        return $this->wishlist()->where('book_id', $bookId)->delete();
    }

    /**
     * Verificar se o usuário pode avaliar um produto
     */
    public function canReviewBook($bookId, $orderId): bool
    {
        // Verificar se já avaliou este livro neste pedido
        $existingReview = Review::where('user_id', $this->id)
            ->where('book_id', $bookId)
            ->where('order_id', $orderId)
            ->exists();
        
        if ($existingReview) {
            return false;
        }

        // Verificar se o pedido foi entregue
        $order = Order::where('id', $orderId)
            ->where('user_id', $this->id)
            ->where('status', 'delivered')
            ->first();

        if (!$order) {
            return false;
        }

        // Verificar se o livro está no pedido
        return $order->orderItems()->where('book_id', $bookId)->exists();
    }

    /**
     * Relacionamento com cupons usados
     */
    public function usedCoupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user')
            ->withPivot('order_id', 'used_at')
            ->withTimestamps();
    }

    /**
     * Verificar se o usuário já usou um cupom específico
     */
    public function hasUsedCoupon($couponId): bool
    {
        return $this->usedCoupons()->where('coupon_id', $couponId)->exists();
    }

    /**
     * Verificar se o usuário pode usar cupons automáticos (cooldown apenas para IA)
     */
    public function canUseAutoCoupons(): bool
    {
        if (!$this->last_coupon_used_at) {
            return true; // Nunca usou cupom automático
        }

        $cooldownDays = 7; // 7 dias de cooldown para cupons IA
        $nextAvailableDate = $this->last_coupon_used_at->addDays($cooldownDays);
        
        return now()->greaterThanOrEqualTo($nextAvailableDate);
    }

    /**
     * Obter data quando próximo cupom automático estará disponível
     */
    public function getNextAutoCouponAvailableDate()
    {
        if (!$this->last_coupon_used_at) {
            return now();
        }

        $cooldownDays = 7; // 7 dias
        return $this->last_coupon_used_at->addDays($cooldownDays);
    }

    /**
     * Obter dias restantes até próximo cupom automático
     */
    public function getDaysUntilNextAutoCoupon(): int
    {
        if ($this->canUseAutoCoupons()) {
            return 0;
        }

        return now()->diffInDays($this->getNextAutoCouponAvailableDate(), false);
    }

    /**
     * Marcar cupom como usado pelo usuário
     * Atualiza cooldown APENAS se for cupom automático (gerado por IA)
     */
    public function markCouponAsUsed($couponId, $orderId = null)
    {
        // Registrar uso na tabela pivot
        $this->usedCoupons()->attach($couponId, [
            'order_id' => $orderId,
            'used_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verificar se é cupom automático (gerado por IA)
        $coupon = \App\Models\Coupon::find($couponId);
        if ($coupon && $coupon->is_auto_generated) {
            // Atualizar último uso APENAS para cupons automáticos
            $this->update([
                'last_coupon_used_at' => now(),
            ]);
        }
    }

    public static function find($id)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function updateProfile($id, array $data)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        
        // Buscar dados atuais do usuário
        $stmt = $pdo->prepare('SELECT name, email, image FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $current = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$current) {
            return false;
        }
        
        // Usar dados atuais se não forem fornecidos
        $name = $data['name'] ?? $current['name'];
        $email = $data['email'] ?? $current['email'];
        $image = $data['image'] ?? $current['image'] ?? 'perfil.png';
        
        $sql = 'UPDATE users SET name=?, email=?, image=? WHERE id=?';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $name,
            $email,
            $image,
            $id
        ]);
    }
}
