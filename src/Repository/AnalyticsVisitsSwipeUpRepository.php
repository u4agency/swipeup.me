<?php

namespace App\Repository;

use App\Entity\AnalyticsVisitsSwipeUp;
use App\Entity\SwipeUp;
use App\Entity\User;
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

    public function findByDateBetween(SwipeUp $swipeUp, $startDate, $endDate): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.visitedAt BETWEEN :startDate AND :endDate')
            ->andWhere('s.swipeup = :swipeup')
            ->setParameter('swipeup', $swipeUp->getId()->toBinary())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    public function findByUser(User $user, $startDate, $endDate): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.visitedAt BETWEEN :startDate AND :endDate')
            ->leftJoin('s.swipeup', 'i1')
            ->andWhere('i1.author = :user')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
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
