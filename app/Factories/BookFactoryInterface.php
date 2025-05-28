<?php
namespace App\Factories;

interface BookFactoryInterface
{
    /**
     * Cria um produto de livro de acordo com o tipo
     */
    public function createProduct(string $type);
}