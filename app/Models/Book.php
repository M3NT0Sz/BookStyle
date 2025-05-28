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
        // Product-specific fields
        "file_format",
        "file_size",
        "has_drm",
        "books_count",
        "titles",
        "extras",
        "issue_number",
        "illustrator",
        "is_colored",
        "pages",
        "cover_type",
        "weight",
        "dimensions",
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
        $sql = 'INSERT INTO books (name, author, genre, `condition`, price, description, images, product_type, user_id, file_format, file_size, has_drm, books_count, titles, extras, issue_number, illustrator, is_colored, pages, cover_type, weight, dimensions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
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
            $data['file_format'] ?? null,
            $data['file_size'] ?? null,
            $data['has_drm'] ?? null,
            $data['books_count'] ?? null,
            $data['titles'] ?? null,
            $data['extras'] ?? null,
            $data['issue_number'] ?? null,
            $data['illustrator'] ?? null,
            $data['is_colored'] ?? null,
            $data['pages'] ?? null,
            $data['cover_type'] ?? null,
            $data['weight'] ?? null,
            $data['dimensions'] ?? null,
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
        $sql = 'UPDATE books SET name=?, author=?, genre=?, `condition`=?, price=?, description=?, images=?, product_type=?, user_id=?, file_format=?, file_size=?, has_drm=?, books_count=?, titles=?, extras=?, issue_number=?, illustrator=?, is_colored=?, pages=?, cover_type=?, weight=?, dimensions=? WHERE id=?';
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
            $data['file_format'] ?? null,
            $data['file_size'] ?? null,
            $data['has_drm'] ?? null,
            $data['books_count'] ?? null,
            $data['titles'] ?? null,
            $data['extras'] ?? null,
            $data['issue_number'] ?? null,
            $data['illustrator'] ?? null,
            $data['is_colored'] ?? null,
            $data['pages'] ?? null,
            $data['cover_type'] ?? null,
            $data['weight'] ?? null,
            $data['dimensions'] ?? null,
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
