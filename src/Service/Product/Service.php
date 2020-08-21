<?php

namespace App\Service\Product;

use App\Dto;
use App\Entity;
use App\Repository;

class Service implements Product
{
    /**
     * @var Repository\ProductRepository
     */
    private $productRepository;

    /**
     * @param Repository\ProductRepository $productRepository
     */
    public function __construct(Repository\ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
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