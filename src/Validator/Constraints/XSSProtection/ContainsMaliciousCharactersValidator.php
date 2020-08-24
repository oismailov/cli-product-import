<?php

namespace App\Validator\Constraints\XSSProtection;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsMaliciousCharactersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsMaliciousCharacters) {
            throw new UnexpectedTypeException($constraint, ContainsMaliciousCharacters::class);
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

        if (preg_match('/(\b)(on\S+)(\s*)=|javascript|(<\s*)(\/*)script/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ xss }}', $value)
                ->addViolation();
        }
    }
}