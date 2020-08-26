<?php

namespace App\Service\Import;

use App\Dto;
use App\Entity;
use App\Service\Product\Product;
use App\Service\Statistics\Errors;
use App\Service\Statistics\ResultCounter\ResultCounter;
use App\Traits;
use App\Validator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Ramsey\Collection\Collection;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Service
 *
 * @package App\Service\Import
 */
class Service implements Import
{
    /**
     * Traits.
     */
    use Traits\Validator;

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

    /**
     * Service constructor.
     *
     * @param EntityManagerInterface $em
     * @param ResultCounter $resultCounter
     * @param Product $productService
     * @param LoggerInterface $logger
     */
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

    /**
     * Import data using provided csv reader and symfony validator.
     *
     * @param Reader $reader
     * @param ValidatorInterface $validator
     *
     * @return void
     */
    public function import(Reader $reader, ValidatorInterface $validator): void
    {
        $this->validateRowsLimit($reader, $validator);

        $this->processImport($reader, $validator);

        $this->output();
    }

    /**
     * Loop through provided file, run validation, collect statistics and save data to database.
     *
     * @param Reader $reader
     * @param ValidatorInterface $validator
     *
     * @return void
     */
    private function processImport(Reader $reader, ValidatorInterface $validator): void
    {
        foreach ($reader->getRecords() as $rowId => $record) {
            if (!$this->isRowValid($rowId, $record, $validator)) {
                continue;
            }

            $productDto = new Dto\Product($record);

            if ($product = $this->productService->findOneBySku($productDto->getSku())) {
                $this->compareProducts($product, $productDto);
            } else {
                $this->em->persist($this->productService->create($productDto));
                $this->resultCounter->incrementRowsCreated();
            }

            $this->em->flush();
            $this->em->clear();
        }
    }

    private function compareProducts(Entity\Product $productEntity, Dto\Product $productDto)
    {
        if ($this->equalRecords($productEntity, $productDto)) {
            $this->resultCounter->incrementRowsSkipped();
        } else {
            $this->em->persist($this->productService->update($productEntity, $productDto));
            $this->resultCounter->incrementRowsUpdated();
        }
    }

    /**
     * Check if provided row has valid data.
     *
     * @param int $rowId
     * @param array $record
     * @param ValidatorInterface $validator
     *
     * @return bool
     */
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
     *
     * @return void
     */
    private function collectErrors(ConstraintViolationListInterface $errors): void
    {
        foreach ($errors as $item) {
            $this->resultCounter->incrementRowsSkipped();
            $this->errors->add(
                new Errors(
                    $item->getRoot()->getRowId(),
                    $item->getRoot()->getSku(),
                    $item->getPropertyPath(),
                    $item->getMessage()
                )
            );
        }
    }

    /**
     * Output all collected errors which were found during import.
     *
     * @return void
     */
    private function outputErrors(): void
    {
        if ($this->errors->count() == 0) {
            return;
        }

        $this->logger->info(sprintf('Number of rows with errors: %d', $this->errors->count()));

        /** @var Errors $error */
        foreach ($this->errors->getIterator() as $error) {
            $this->logger->debug(
                sprintf('Row: %d - SKU: %d - Property: %s - Message: %s',
                    $error->getRowId(), $error->getSku(), $error->getProperty(), $error->getMessage())
            );
        }
    }

    /**
     * Output all collected errors and statistics:
     * 1. Number of products created
     * 2. Number of products updated
     * 3. Number of products skipped
     *
     * @return void
     */
    private function output(): void
    {
        $this->outputErrors();
        $this->logger->info(sprintf('Number of products created: %d', $this->resultCounter->getRowsCreated()));
        $this->logger->info(sprintf('Number of products updated: %d', $this->resultCounter->getRowsUpdated()));
        $this->logger->info(sprintf('Number of products skipped: %d', $this->resultCounter->getRowsSkipped()));
    }
}