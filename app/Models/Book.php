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
     * Retorna o campo `genre` como um array ao invés de JSON.
     *
     * @return array
     */
    public function getGenreAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Define o campo `genre` como JSON ao salvar.
     *
     * @param array $value
     */
    public function setGenreAttribute($value)
    {
        $this->attributes['genre'] = json_encode($value);
    }
}
