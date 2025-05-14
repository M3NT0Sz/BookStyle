<?php
namespace App\Factories;

use App\Models\Book;

interface BookFactoryInterface
{
    public function create(array $data, array $images, int $userId);
    public function update($book, array $data, ?array $images = null);
    public function delete($book);
}