<?php

namespace App\Validator;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class Product
 *
 * @package App\Dto
 */
class Product
{
    /**
     * @var int
     */
    private $rowId;
    /**
     * @var string
     */
    public $sku;
    /**
     * @var string
     */
    public $description;
    /**
     * @var float
     */
    public $normalPrice;
    /**
     * @var float|null
     */
    public $specialPrice;

    public function __construct(int $rowId, array $product)
    {
        $this->rowId = $rowId;
        $this->sku = $product['sku'];
        $this->description = $product['description'];
        $this->normalPrice = $product['normalPrice'];
        $this->specialPrice = $product['specialPrice'];
    }

    public function getRowId(): int
    {
        return $this->rowId;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('sku', new Assert\NotBlank());
        $metadata->addPropertyConstraint('sku', new CustomAssert\XSSProtection\ContainsMaliciousCharacters());
        $metadata->addPropertyConstraint('sku', new Assert\Type('string'));
        $metadata->addPropertyConstraint('sku', new Assert\Length([
            'min' => $_ENV['SKU_MIN_LENGTH'], 'max' => $_ENV['SKU_MAX_LENGTH']
        ]));

        $metadata->addPropertyConstraint('description', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'description',
            new CustomAssert\XSSProtection\ContainsMaliciousCharacters()
        );
        $metadata->addPropertyConstraint('description', new Assert\Type('string'));

        $metadata->addPropertyConstraint('normalPrice', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'normalPrice',
            new CustomAssert\XSSProtection\ContainsMaliciousCharacters()
        );
        $metadata->addPropertyConstraint('normalPrice', new CustomAssert\Float\ContainsFloat());

        $metadata->addPropertyConstraint('specialPrice', new CustomAssert\Float\ContainsFloat());
        $metadata->addPropertyConstraint(
            'specialPrice',
            new CustomAssert\XSSProtection\ContainsMaliciousCharacters()
        );
        $metadata->addPropertyConstraint(
            'specialPrice',
            new Assert\Callback([SpecialPriceValidator::class, 'validate'])
        );
    }
}