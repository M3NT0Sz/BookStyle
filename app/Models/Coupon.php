<?php
namespace App\Models;

class Coupon
{
    public static function all()
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->query('SELECT * FROM coupons');
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM coupons WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByCode($code)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM coupons WHERE code = ?');
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public static function create(array $data)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('INSERT INTO coupons (code, discount, type, expires_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['code'],
            $data['discount'],
            $data['type'],
            $data['expires_at'] ?? null
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, array $data)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('UPDATE coupons SET code = ?, discount = ?, type = ?, expires_at = ? WHERE id = ?');
        return $stmt->execute([
            $data['code'],
            $data['discount'],
            $data['type'],
            $data['expires_at'] ?? null,
            $id
        ]);
    }

    public static function delete($id)
    {
        $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('DELETE FROM coupons WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
