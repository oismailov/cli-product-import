<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseService
 *
 * @package App\Tests\Service
 */
abstract class BaseService extends TestCase
{
    /**
     * Get symfony validator.
     *
     * @return RecursiveValidator|ValidatorInterface
     */
    protected function getValidator()
    {
        return Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
    }
}