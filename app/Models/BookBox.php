<?php
namespace App\Models;

class BookBox extends BookProduct
{
    public function getType(): string
    {
        return 'Box de Livros';
    }
}
