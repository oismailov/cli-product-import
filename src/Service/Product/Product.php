<?php

namespace App\Service\Product;

use App\Dto;
use App\Entity;

interface Product
{
    public function create(Dto\Product $productDto): Entity\Product;

    public function update(Entity\Product $product, Dto\Product $productDto): Entity\Product;

    public function findOneBySku(string $sku): ?Entity\Product;
}