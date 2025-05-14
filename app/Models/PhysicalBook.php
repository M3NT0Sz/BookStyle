<?php
namespace App\Models;

class PhysicalBook extends BookProduct
{
    // Propriedades específicas
    public int $pages;
    public string $coverType;
    public float $weight; // em gramas
    public array $dimensions; // [largura, altura, profundidade]

    public function __construct($pages = 0, $coverType = 'brochura', $weight = 0.0, $dimensions = [0,0,0])
    {
        $this->pages = $pages;
        $this->coverType = $coverType;
        $this->weight = $weight;
        $this->dimensions = $dimensions;
    }

    public function getType(): string
    {
        return 'Livro Físico';
    }

    // Métodos específicos
    public function calculateShipping(): string
    {
        return 'Frete calculado com base no peso e dimensões.';
    }

    public function showPhysicalDetails(): string
    {
        return "{$this->pages} páginas, capa {$this->coverType}, peso {$this->weight}g.";
    }
}
