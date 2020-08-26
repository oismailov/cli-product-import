<?php

namespace App\Service\Product;

use App\Dto;
use App\Entity;

/**
 * Interface Product
 *
 * @package App\Service\Product
 */
interface Product
{
    /**
     * Create product from provided dto object.
     *
     * @param Dto\Product $productDto
     *
     * @return Entity\Product
     */
    public function create(Dto\Product $productDto): Entity\Product;

    /**
     * Update product from provided dto object.
     *
     * @param Entity\Product $product
     * @param Dto\Product $productDto
     *
     * @return Entity\Product
     */
    public function update(Entity\Product $product, Dto\Product $productDto): Entity\Product;

    /**
     * Find product by sku value.
     *
     * @param string $sku
     *
     * @return Entity\Product|null
     */
    public function findOneBySku(string $sku): ?Entity\Product;
}