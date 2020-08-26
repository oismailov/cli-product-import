<?php

namespace App\Dto;

/**
 * Class Product
 *
 * @package App\Dto
 */
class Product
{
    /**
     * @var string
     */
    private $sku;
    /**
     * @var string
     */
    private $description;
    /**
     * @var float
     */
    private $normalPrice;
    /**
     * @var float|null
     */
    private $specialPrice;

    /**
     * Product constructor.
     *
     * @param array $product
     */
    public function __construct(array $product)
    {
        $this->sku = $product['SKU'];
        $this->description = $product['description'];
        $this->normalPrice = $product['normalPrice'];
        $this->specialPrice = $product['specialPrice'] ?? null;
    }

    /**
     * Get sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get normalPrice.
     *
     * @return float
     */
    public function getNormalPrice(): float
    {
        return $this->normalPrice;
    }

    /**
     * Get specialPrice
     *
     * @return float|null
     */
    public function getSpecialPrice(): ?float
    {
        return $this->specialPrice;
    }
}