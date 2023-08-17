<?php

namespace App\Repository;

use App\Entity\AnalyticsVisitsSwipeUp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnalyticsVisitsSwipeUp>
 *
 * @method AnalyticsVisitsSwipeUp|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnalyticsVisitsSwipeUp|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnalyticsVisitsSwipeUp[]    findAll()
 * @method AnalyticsVisitsSwipeUp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyticsVisitsSwipeUpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyticsVisitsSwipeUp::class);
    }

//    /**
//     * @return AnalyticsVisitsSwipeUp[] Returns an array of AnalyticsVisitsSwipeUp objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnalyticsVisitsSwipeUp
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
