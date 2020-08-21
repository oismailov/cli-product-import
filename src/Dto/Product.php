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

    public function __construct(array $product)
    {
        $this->sku = $product['sku'];
        $this->description = $product['description'];
        $this->normalPrice = $product['normalPrice'];
        $this->specialPrice = $product['specialPrice'];
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNormalPrice(): float
    {
        return $this->normalPrice;
    }

    public function getSpecialPrice(): ?float
    {
        return $this->specialPrice;
    }
}