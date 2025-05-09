<?php
namespace App\Factories;

use App\Models\Book;

interface BookFactoryInterface
{
    public function create(array $data, array $images, int $userId): Book;
    public function update(Book $book, array $data, ?array $images = null): bool;
    public function delete(Book $book): bool;
}