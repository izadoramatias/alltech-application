<?php

namespace App\Repository;

use App\Entity\CompletedOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompletedOrder>
 *
 * @method CompletedOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompletedOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompletedOrder[]    findAll()
 * @method CompletedOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompletedOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompletedOrder::class);
    }

//    /**
//     * @return CompletedOrder[] Returns an array of CompletedOrder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CompletedOrder
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
