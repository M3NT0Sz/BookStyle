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
