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
     * Relacionamento com livros (que o usuário cadastrou)
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public static function find($id)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function updateProfile($id, array $data)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        // Busca imagem atual se não vier nova
        if (!isset($data['image'])) {
            $stmt = $pdo->prepare('SELECT image FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $current = $stmt->fetch();
            $data['image'] = $current ? $current['image'] : null;
        }
        $sql = 'UPDATE users SET name=?, email=?, image=? WHERE id=?';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['image'],
            $id
        ]);
    }
}
