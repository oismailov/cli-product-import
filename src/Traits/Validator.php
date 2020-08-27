<?php

namespace App\Traits;

use App\Entity;
use App\Dto;
use InvalidArgumentException;
use League\Csv\Reader;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Trait Validator
 *
 * @package App\Traits
 */
trait Validator
{
    /**
     * Validation for rows limit in file:
     * min rows: 1
     * max rows: 50,000
     *
     * @param Reader $reader
     * @param ValidatorInterface $validator
     *
     * @throws InvalidArgumentException
     */
    public function validateRowsLimit(Reader $reader, ValidatorInterface $validator): void
    {
        if ($reader->count() === 0) {
            throw new InvalidArgumentException('Number of rows must be greater than 0.');
        }

        if ($reader->count() > 50000) {
            throw new InvalidArgumentException('Number of rows must be less than or equal 50,000.');
        }
    }

    /**
     * Check if given path is csv file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isCsvFile(string $path): bool
    {
        $fileInfo = pathinfo($path);

        return $fileInfo["extension"] === "csv";

    }

    /**
     * Check if 2 products are equal.
     *
     * @param Entity\Product $productEntity
     * @param Dto\Product $productDto
     *
     * @return bool
     */
    public function equalRecords(Entity\Product $productEntity, Dto\Product $productDto): bool
    {
        $areEqual = true;

        if ($productEntity->getSku() != $productDto->getSku()) {
            $areEqual = false;
        }

        if ($productEntity->getDescription() != $productDto->getDescription()) {
            $areEqual = false;
        }

        if ($productEntity->getNormalPrice() != $productDto->getNormalPrice()) {
            $areEqual = false;
        }

        if ($productEntity->getSpecialPrice() != $productDto->getSpecialPrice()) {
            $areEqual = false;
        }

        return $areEqual;
    }
}