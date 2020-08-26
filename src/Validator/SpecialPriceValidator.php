<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class SpecialPriceValidator
 *
 * @package App\Validator
 */
class SpecialPriceValidator
{
    /**
     * Check if specialPrice value is lower than normalPrice value.
     *
     * @param $object
     * @param ExecutionContextInterface $context
     *
     * @return void
     */
    public static function validate($object, ExecutionContextInterface $context): void
    {
        $product = $context->getObject();
        $normalPrice = $product->normalPrice;
        $specialPrice = $product->specialPrice;

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $specialPrice || '' === $specialPrice) {
            return;
        }

        if (!is_string($specialPrice)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($specialPrice, 'float');
        }

        if ($specialPrice >= $normalPrice) {
            $context
                ->buildViolation('Value `{{ float }}` for specialPrice must be lower than normalPrice')
                ->setParameter('{{ float }}', $specialPrice)
                ->addViolation();
        }
    }
}