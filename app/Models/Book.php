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
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $sql = 'INSERT INTO books (name, author, genre, condition, price, description, images, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['author'],
            $data['genre'],
            $data['condition'],
            $data['price'],
            $data['description'],
            $data['images'],
            $data['user_id'],
        ]);
        $data['id'] = $pdo->lastInsertId();
        return $data;
    }

    public static function update($id, array $data)
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $sql = 'UPDATE books SET name=?, author=?, genre=?, condition=?, price=?, description=?, images=?, user_id=? WHERE id=?';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['author'],
            $data['genre'],
            $data['condition'],
            $data['price'],
            $data['description'],
            $data['images'],
            $data['user_id'],
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
