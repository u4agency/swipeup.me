<?php

namespace App\Repository;

use App\Entity\AnalyticsVisitsURLShortener;
use App\Entity\URLShortener;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnalyticsVisitsURLShortener>
 *
 * @method AnalyticsVisitsURLShortener|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnalyticsVisitsURLShortener|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnalyticsVisitsURLShortener[]    findAll()
 * @method AnalyticsVisitsURLShortener[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyticsVisitsURLShortenerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnalyticsVisitsURLShortener::class);
    }

    public function findByDateBetween(URLShortener $URLShortener, $startDate, $endDate): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.visitedAt BETWEEN :startDate AND :endDate')
            ->andWhere('s.URLShortener = :URLShortener')
            ->setParameter('URLShortener', $URLShortener->getId())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return AnalyticsVisitsURLShortener[] Returns an array of AnalyticsVisitsURLShortener objects
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

//    public function findOneBySomeField($value): ?AnalyticsVisitsURLShortener
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
