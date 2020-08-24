<?php

namespace App\Command;

use App\Service;
use League\Csv;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductImport extends Command
{
    /**
     * @var Service\Import\Import
     */
    private $importService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, Service\Import\Import $importService)
    {
        parent::__construct();

        $this->importService = $importService;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('product:import')
            ->setDescription('Import products from csv file.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Starting products import...');

        try {
            $this->importService->import($this->getReader(), $this->getValidator());
            $this->logger->info('Finished product import!');
        } catch (\Throwable $exception) {
            $this->logger->debug(sprintf('There was an error during product import: %s!', $exception->getMessage()));
        }

        //added return statement to hide this error https://github.com/symfony/symfony/issues/33747
        return 0;
    }

    /**
     * @return Csv\AbstractCsv|Reader
     *
     * @throws Csv\Exception
     */
    private function getReader()
    {
        $reader = Reader::createFromPath('%kernel.root_dir%/../data/products.csv');
        $reader->setHeaderOffset(0);

        return $reader;
    }

    /**
     * @return RecursiveValidator|ValidatorInterface
     */
    private function getValidator()
    {
        return Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
    }
}