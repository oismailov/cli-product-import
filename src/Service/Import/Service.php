<?php

namespace App\Service\Import;

use App\Dto;
use App\Service\Product\Product;
use App\Service\Statistics\Errors;
use App\Service\Statistics\ResultCounter\ResultCounter;
use App\Validator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Ramsey\Collection\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Service implements Import
{
    /**
     * @var ResultCounter
     */
    private $resultCounter;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Collection
     */
    private $errors;

    /**
     * @var Product
     */
    private $productService;

    public function __construct(
        EntityManagerInterface $em,
        ResultCounter $resultCounter,
        Product $productService,
        LoggerInterface $logger
    )
    {
        $this->em = $em;
        $this->resultCounter = $resultCounter;
        $this->productService = $productService;
        $this->logger = $logger;
        $this->errors = new Collection(Errors::class);
    }

    public function import(Reader $reader, ValidatorInterface $validator): void
    {
        $this->initialValidation($reader, $validator);

        $this->processImport($reader, $validator);

        $this->output();
        $this->em->flush();
    }

    private function processImport(Reader $reader, ValidatorInterface $validator)
    {
        foreach ($reader->getRecords() as $rowId => $record) {
            if (!$this->isRowValid($rowId, $record, $validator)) {
                continue;
            }

            $productDto = new Dto\Product($record);

            if ($product = $this->productService->findOneBySku($productDto->getSku())) {
                $this->em->persist($this->productService->update($product, $productDto));
                $this->resultCounter->incrementRowsUpdated();
            } else {
                $this->em->persist($this->productService->create($productDto));
                $this->resultCounter->incrementRowsCreated();
            }
        }
    }

    private function isRowValid(int $rowId, array $record, ValidatorInterface $validator): bool
    {
        $productRow = new Validator\Product($rowId, $record);
        $errors = $validator->validate($productRow);

        if ($errors->count() == 0) {
            return true;
        }

        $this->collectErrors($errors);

        return false;
    }

    /**
     * Collection of validation errors.
     *
     * @param ConstraintViolationListInterface $errors
     */
    private function collectErrors(ConstraintViolationListInterface $errors): void
    {
        foreach ($errors as $item) {
            $this->resultCounter->incrementRowsSkipped();
            $this->errors->add(
                new Errors(
                    $item->getRoot()->getRowId(), $item->getPropertyPath(), $item->getMessage()
                )
            );
        }
    }

    private function outputErrors(): void
    {
        if ($this->errors->count() == 0) {
            return;
        }

        $this->logger->info(sprintf('Number of rows with errors: %d', $this->errors->count()));

        /** @var Errors $error */
        foreach ($this->errors->getIterator() as $error) {
            $this->logger->debug(
                sprintf('Row: %d - Property: %s - Message: %s',
                    $error->getRowId(), $error->getProperty(), $error->getMessage())
            );
        }
    }
    
    private function output()
    {
        $this->outputErrors();
        $this->logger->info(sprintf('Number of products created: %d', $this->resultCounter->getRowsCreated()));
        $this->logger->info(sprintf('Number of products updated: %d', $this->resultCounter->getRowsUpdated()));
        $this->logger->info(sprintf('Number of products skipped: %d', $this->resultCounter->getRowsSkipped()));
    }

    private function initialValidation(Reader $reader, ValidatorInterface $validator)
    {
        if ($reader->count() == 0) {
            throw new \InvalidArgumentException('Number of rows must be greater than 0.');
        }

        if ($reader->count() > 50000) {
            throw new \InvalidArgumentException('Number of rows must be less than or equal 50,000.');
        }
    }
}