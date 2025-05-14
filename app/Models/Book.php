<?php

namespace App\Models;

use App\Models\DatabaseSingleton;

class Book
{
    protected $fillable = [
        "name",
        "author",
        "genre",
        "condition",
        "price",
        "description",
        "images",
        "product_type", // novo campo
        "user_id",
    ];

    public static function all()
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->query('SELECT * FROM books');
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create(array $data)
    {
        // Se não houver user_id válido, retorna null e não executa o insert/update
        if (!isset($data['user_id']) || empty($data['user_id'])) {
            return null;
        }

        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $sql = 'INSERT INTO books (name, author, genre, `condition`, price, description, images, product_type, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['author'],
            $data['genre'],
            $data['condition'],
            $data['price'],
            $data['description'],
            isset($data['images']) ? $data['images'] : json_encode([]),
            $data['product_type'] ?? 'fisico',
            isset($data['user_id']) ? $data['user_id'] : null,
        ]);
        $data['id'] = $pdo->lastInsertId();
        return $data;
    }

    public static function update($id, array $data)
    {
        // Se não houver user_id válido, retorna null e não executa o insert/update
        if (!isset($data['user_id']) || empty($data['user_id'])) {
            return null;
        }

        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $sql = 'UPDATE books SET name=?, author=?, genre=?, `condition`=?, price=?, description=?, images=?, product_type=?, user_id=? WHERE id=?';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['author'],
            $data['genre'],
            $data['condition'],
            $data['price'],
            $data['description'],
            isset($data['images']) ? $data['images'] : json_encode([]),
            $data['product_type'] ?? 'fisico',
            isset($data['user_id']) ? $data['user_id'] : null,
            $id
        ]);
    }

    public static function delete($id)
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
