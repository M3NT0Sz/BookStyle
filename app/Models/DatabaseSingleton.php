<?php

namespace App\Models;

use PDO;
use PDOException;

class DatabaseSingleton
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = env('DB_HOST', '127.0.0.1');
        $db   = env('DB_DATABASE', 'bookstyle');
        $user = env('DB_USERNAME', 'root');
        $pass = env('DB_PASSWORD', '');
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function __clone() {}
    public function __wakeup() {}
}
