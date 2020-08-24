<?php

namespace App\Service\Import;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use League\Csv\Reader;

interface Import
{
    public function import(Reader $reader, ValidatorInterface $validator);
}