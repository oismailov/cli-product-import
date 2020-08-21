<?php

namespace App\Command;

use App\Dto;
use App\Service;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \League\Csv\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Starting products import...');

        try {
            $reader = Reader::createFromPath('%kernel.root_dir%/../data/products.csv');
            $reader->setHeaderOffset(0);

            foreach ($reader->getRecords() as $record) {
                $productDto = new Dto\Product($record);

                if ($product = $this->productService->findOneBySku($productDto->getSku())) {
                    $this->em->persist($this->productService->update($product, $productDto));
                } else {
                    $this->em->persist($this->productService->create($productDto));
                }
            }

            $this->em->flush();
        } catch (\Throwable $exception) {
            $this->em->rollback();
        }

        $io->success('Finished product import!');
    }
}