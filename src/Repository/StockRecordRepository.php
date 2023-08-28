<?php

namespace App\Repository;

use App\Entity\StockRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockRecord>
 *
 * @method StockRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockRecord[]    findAll()
 * @method StockRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockRecord::class);
    }

//    /**
//     * @return StockRecord[] Returns an array of StockRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StockRecord
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
