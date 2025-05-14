<?php
namespace App\Models;

abstract class BookProduct
{
    abstract public function getType(): string;
}
