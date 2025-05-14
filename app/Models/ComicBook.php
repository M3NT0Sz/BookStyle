<?php
namespace App\Models;

class ComicBook extends BookProduct
{
    public function getType(): string
    {
        return 'Gibi';
    }
}
