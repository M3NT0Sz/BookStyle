<?php
namespace App\Models;

class PhysicalBook extends BookProduct
{
    public function getType(): string
    {
        return 'Livro Físico';
    }
}
