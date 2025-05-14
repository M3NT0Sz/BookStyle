<?php
namespace App\Models;

class Ebook extends BookProduct
{
    // Propriedades específicas
    public string $fileFormat;
    public int $fileSize; // em KB
    public bool $hasDrm;

    public function __construct($fileFormat = 'PDF', $fileSize = 0, $hasDrm = false)
    {
        $this->fileFormat = $fileFormat;
        $this->fileSize = $fileSize;
        $this->hasDrm = $hasDrm;
    }

    public function getType(): string
    {
        return 'Ebook';
    }

    // Métodos específicos
    public function download(): string
    {
        return 'Iniciando download do ebook.';
    }

    public function preview(): string
    {
        return 'Visualizando amostra do ebook.';
    }
}
