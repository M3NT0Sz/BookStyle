<?php
namespace App\Factories;

use App\Models\PhysicalBook;
use App\Models\Ebook;
use App\Models\ComicBook;
use App\Models\BookBox;
use App\Models\BookProduct;

class BookFactory
{
    /**
     * Factory Method para criar diferentes tipos de produtos de livro
     */
    public function createProduct(string $type): BookProduct
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