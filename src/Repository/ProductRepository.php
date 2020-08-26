<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * ProductRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Find Product by sku value.
     *
     * @param string $sku
     *
     * @return Product|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySku(string $sku): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sku = :sku')
            ->setParameter('sku', $sku)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
