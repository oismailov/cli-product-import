<?php

namespace App\Service\Import;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use League\Csv\Reader;

/**
 * Interface Import
 *
 * @package App\Service\Import
 */
interface Import
{
    /**
     * Import data using provided csv reader and symfony validator.
     *
     * @param Reader $reader
     * @param ValidatorInterface $validator
     *
     * @return void
     */
    public function import(Reader $reader, ValidatorInterface $validator): void;
}