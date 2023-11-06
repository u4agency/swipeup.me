<?php

namespace App\Repository;

use App\Entity\AnalyticsVisitsSwipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnalyticsVisitsSwipe>
 *
 * @method AnalyticsVisitsSwipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnalyticsVisitsSwipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnalyticsVisitsSwipe[]    findAll()
 * @method AnalyticsVisitsSwipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyticsVisitsSwipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyticsVisitsSwipe::class);
    }

    public function countAll(): float|bool|int|string|null
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return AnalyticsVisitsSwipe[] Returns an array of AnalyticsVisitsSwipe objects
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

//    public function findOneBySomeField($value): ?AnalyticsVisitsSwipe
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
