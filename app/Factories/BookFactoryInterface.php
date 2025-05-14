<?php
namespace App\Factories;

use App\Models\Book;

interface BookFactoryInterface
{
    public function create(array $data, array $images, int $userId);
    public function update($book, array $data, ?array $images = null);
    public function delete($book);

    /**
     * Cria um produto de livro de acordo com o tipo
     */
    public function createProduct(string $type, array $data, array $images, int $userId);
}