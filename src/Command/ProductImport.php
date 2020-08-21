<?php

namespace App\Command;

use App\Dto;
use App\Service;
use App\Validator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductImport extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Service\Product\Product
     */
    private $productService;

    public function __construct(EntityManagerInterface $em, Service\Product\Product $productService)
    {
        parent::__construct();

        $this->em = $em;
        $this->productService = $productService;
    }

    protected function configure()
    {
        $this
            ->setName('product:import')
            ->setDescription('Import products from csv file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Starting products import...');

        try {
            $reader = $this->getReader();
            $validator = $this->getValidator();

            foreach ($reader->getRecords() as $rowId => $record) {
                $productRow = new Validator\Product($rowId, $record);

                if ($errors = $validator->validate($productRow)) {
                    foreach ($errors as $item) {
                        echo "At row:"
                            . $item->getRoot()->getRowId()
                            . " - Property: "
                            . $item->getPropertyPath()
                            . ' - Message: '
                            . $item->getMessage()
                            . "\n";
                    }
                    continue;
                }

                $productDto = new Dto\Product($record);

                if ($product = $this->productService->findOneBySku($productDto->getSku())) {
                    $this->em->persist($this->productService->update($product, $productDto));
                } else {
                    $this->em->persist($this->productService->create($productDto));
                }
            }

            $this->em->flush();
            $io->success('Finished product import!');
        } catch (\Throwable $exception) {
            $io->error(sprintf('There was an error during product import: %s!', $exception->getMessage()));
        }
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