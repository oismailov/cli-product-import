<?php

namespace App\Validator\Constraints\XSSProtection;

use Symfony\Component\Validator\Constraint;

/**
 * Class ContainsMaliciousCharacters
 *
 * @package App\Validator\Constraints\XSSProtection
 */
class ContainsMaliciousCharacters extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The string "{{ xss }}" contains illegal characters.';
}