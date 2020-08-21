<?php

namespace App\Validator\Constraints\XSSProtection;

use Symfony\Component\Validator\Constraint;

class ContainsMaliciousCharacters extends Constraint
{
    public $message = 'The string "{{ xss }}" contains illegal characters.';
}