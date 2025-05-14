<?php
namespace App\Factories;

use App\Models\Book;
use App\Models\DatabaseSingleton;
use Illuminate\Support\Facades\Storage;
use App\Models\PhysicalBook;
use App\Models\Ebook;
use App\Models\ComicBook;
use App\Models\BookBox;
use App\Models\BookProduct;

class BookFactory implements BookFactoryInterface
{
    public function create(array $data, array $images, int $userId)
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $image->store('img.books');
        }
        $data['images'] = json_encode($paths);
        $data['user_id'] = $userId;
        return Book::create($data);
    }

    public function update($book, array $data, ?array $images = null): bool
    {
        if ($images) {
            $paths = [];
            foreach ($images as $image) {
                $paths[] = $image->store('img.books');
            }
            $data['images'] = json_encode($paths);
        } else {
            // Se não enviar novas imagens, mantém as antigas
            $data['images'] = $book['images'] ?? (is_object($book) ? $book->images : null);
        }

        // Atualiza o tipo do produto apenas se vier do formulário
        if (!array_key_exists('product_type', $data) || !$data['product_type']) {
            $data['product_type'] = $book['product_type'] ?? (is_object($book) ? $book->product_type : 'fisico');
        }
        $data['user_id'] = $book['user_id'] ?? $data['user_id'] ?? null;
        $result = Book::update($book['id'], $data);
        return $result !== null ? $result : false;
    }

    public function delete($book): bool
    {
        $images = json_decode($book['images'], true);
        if ($images) {
            foreach ($images as $image) {
                // Storage::delete($image); // Remover se não usar Laravel Storage
            }
        }
        return Book::delete($book['id']);
    }

    /**
     * Factory Method para criar diferentes tipos de produtos de livro
     */
    public function createProduct(string $type, array $data, array $images, int $userId): BookProduct
    {
        switch (strtolower($type)) {
            case 'fisico':
                return new PhysicalBook();
            case 'ebook':
                return new Ebook();
            case 'gibi':
                return new ComicBook();
            case 'box':
                return new BookBox();
            default:
                throw new \InvalidArgumentException('Tipo de produto desconhecido');
        }
    }
}