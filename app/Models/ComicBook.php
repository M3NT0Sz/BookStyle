<?php
namespace App\Models;

class ComicBook extends BookProduct
{
    // Propriedades específicas
    public int $issueNumber;
    public string $illustrator;
    public bool $isColored;

    public function __construct($issueNumber = 1, $illustrator = '', $isColored = true)
    {
        $this->issueNumber = $issueNumber;
        $this->illustrator = $illustrator;
        $this->isColored = $isColored;
    }

    public function getType(): string
    {
        return 'Gibi';
    }

    // Métodos específicos
    public function showCover(): string
    {
        return 'Exibindo capa do gibi.';
    }

    public function listAuthors(): string
    {
        return 'Autores: ' . $this->illustrator;
    }
}
