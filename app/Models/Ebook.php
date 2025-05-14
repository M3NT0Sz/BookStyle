<?php
namespace App\Models;

class Ebook extends BookProduct
{
    public function getType(): string
    {
        return 'Ebook';
    }
}
