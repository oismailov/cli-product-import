<?php

namespace App\Validator\Constraints\Float;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class ContainsFloatValidator
 * Validator checks if argument is a float value.
 *
 * @package App\Validator\Constraints\Float
 */
class ContainsFloatValidator extends ConstraintValidator
{
    /**
     * Validate value against float rules.
     *
     * @param mixed $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContainsFloat) {
            throw new UnexpectedTypeException($constraint, ContainsFloat::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'float');
        }

        if (!preg_match('/^[0-9]*\.[0-9]+$/', $value, $matches)) {
            // the argument must be a float
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ float }}', $value)
                ->addViolation();
        }
    }
}