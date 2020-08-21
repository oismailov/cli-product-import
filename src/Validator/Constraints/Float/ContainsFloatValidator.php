<?php

namespace App\Validator\Constraints\Float;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsFloatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
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