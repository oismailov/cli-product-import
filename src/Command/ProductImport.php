<?php

namespace App\Command;

use App\Service;
use App\Traits;
use InvalidArgumentException;
use League\Csv;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProductImport
 *
 * @package App\Command
 */
class ProductImport extends Command
{
    /**
     * Traits
     */
    use Traits\Validator;

    /**
     * @var Service\Import\Import
     */
    private $importService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ProductImport constructor.
     *
     * @param LoggerInterface $logger
     * @param Service\Import\Import $importService
     */
    public function __construct(LoggerInterface $logger, Service\Import\Import $importService)
    {
        parent::__construct();

        $this->importService = $importService;
        $this->logger = $logger;
    }

    /**
     * Command configuration.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('product:import')
            ->setDescription('Import products from csv file.')
            ->addOption(
                'file_path',
                null,
                InputArgument::OPTIONAL,
                'Full file path',
                'data/products.csv'
            );
    }

    /**
     * Command execution handler.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Starting products import...');

        $file = $input->getOption('file_path');

        try {
            $this->validateFile($file);

            $this->importService->import($this->getReader($file), $this->getValidator());
            $this->logger->info('Finished product import!');
        } catch (\Throwable $exception) {
            $this->logger->debug(sprintf('There was an error during product import: %s!', $exception->getMessage()));
        }

        //added return statement to hide this error https://github.com/symfony/symfony/issues/33747
        return 0;
    }

    /**
     * Get csv file reader.
     *
     * @param string $file
     *
     * @return Csv\AbstractCsv|Reader
     *
     * @throws Csv\Exception
     */
    private function getReader(string $file)
    {
        $reader = Reader::createFromPath($file);
        $reader->setHeaderOffset(0);

        return $reader;
    }

    /**
     * Get symfony validator.
     *
     * @return RecursiveValidator|ValidatorInterface
     */
    private function getValidator()
    {
        return Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
    }

    /**
     * Validate file existence and format.
     *
     * @param string $path
     *
     * @return void
     */
    private function validateFile(string $path): void
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('File: %s doesn\'t exist', $path));
        }

        if (!$this->isCsvFile($path)) {
            throw new InvalidArgumentException(sprintf('Format of the file: %s is not csv', $path));
        }
    }
}