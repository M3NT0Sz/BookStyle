<?php
namespace App\Factories;

use App\Models\Book;
use App\Models\DatabaseSingleton;
use Illuminate\Support\Facades\Storage;

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
        }
        return Book::update($book['id'], $data);
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
}