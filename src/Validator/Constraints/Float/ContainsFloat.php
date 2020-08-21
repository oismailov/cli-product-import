<?php

namespace App\Validator\Constraints\Float;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsFloat extends Constraint
{
    public $message = 'The string "{{ float }}" contains an illegal character: it can only contains float values.';
}