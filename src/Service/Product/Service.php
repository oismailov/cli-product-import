<?php

namespace App\Service\Product;

use App\Dto;
use App\Entity;
use App\Repository;

/**
 * Class Service
 * @package App\Service\Product
 */
class Service implements Product
{
    /**
     * @var Repository\ProductRepository
     */
    private $productRepository;

    /**
     * Service constructor.
     *
     * @param Repository\ProductRepository $productRepository
     */
    public function __construct(Repository\ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Create product from provided dto object.
     *
     * @param Dto\Product $productDto
     *
     * @return Entity\Product
     */
    public function create(Dto\Product $productDto): Entity\Product
    {
        return (new Entity\Product())
            ->setSku($productDto->getSku())
            ->setDescription($productDto->getDescription())
            ->setNormalPrice($productDto->getNormalPrice())
            ->setSpecialPrice($productDto->getSpecialPrice());
    }

    /**
     * Update product from provided dto object.
     *
     * @param Entity\Product $product
     * @param Dto\Product $productDto
     *
     * @return Entity\Product
     */
    public function update(Entity\Product $product, Dto\Product $productDto): Entity\Product
    {
        return $product
            ->setDescription($productDto->getDescription())
            ->setNormalPrice($productDto->getNormalPrice())
            ->setSpecialPrice($productDto->getSpecialPrice());
    }

    /**
     * Find product by sku value.
     *
     * @param string $sku
     *
     * @return Entity\Product|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySku(string $sku): ?Entity\Product
    {
        return $this->productRepository->findOneBySku($sku);
    }
}
