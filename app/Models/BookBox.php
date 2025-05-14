<?php
namespace App\Models;

class BookBox extends BookProduct
{
    // Propriedades específicas
    public int $booksCount;
    public array $titles;
    public array $extras;

    public function __construct($booksCount = 0, $titles = [], $extras = [])
    {
        $this->booksCount = $booksCount;
        $this->titles = $titles;
        $this->extras = $extras;
    }

    public function getType(): string
    {
        return 'Box de Livros';
    }

    // Métodos específicos
    public function listContents(): array
    {
        return array_merge($this->titles, $this->extras);
    }

    public function calculateBoxDiscount(): string
    {
        return 'Desconto aplicado para box de livros.';
    }
}
