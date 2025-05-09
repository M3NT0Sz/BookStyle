<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Book extends Model
{
    use HasFactory, Notifiable;

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

    /**
     * Create a new book.
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile[] $images
     * @param int $userId
     * @return self
     */
    public static function createBook(array $data, array $images, int $userId): self
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $image->store('img.books');
        }

        $data['images'] = json_encode($paths);
        $data['user_id'] = $userId;

        return self::create($data);
    }

    /**
     * Update the book.
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile[]|null $images
     * @return bool
     */
    public function updateBook(array $data, ?array $images = null): bool
    {
        if ($images) {
            $paths = [];
            foreach ($images as $image) {
                $paths[] = $image->store('img.books');
            }
            $data['images'] = json_encode($paths);
        }

        return $this->update($data);
    }

    /**
     * Delete the book and its images.
     *
     * @return bool|null
     */
    public function deleteBook(): ?bool
    {
        $images = json_decode($this->images, true);
        if ($images) {
            foreach ($images as $image) {
                \Storage::delete($image);
            }
        }

        return $this->delete();
    }
}
