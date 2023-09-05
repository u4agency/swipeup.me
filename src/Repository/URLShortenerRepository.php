<?php

namespace App\Repository;

use App\Entity\URLShortener;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<URLShortener>
 *
 * @method URLShortener|null find($id, $lockMode = null, $lockVersion = null)
 * @method URLShortener|null findOneBy(array $criteria, array $orderBy = null)
 * @method URLShortener[]    findAll()
 * @method URLShortener[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class URLShortenerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, URLShortener::class);
    }

//    /**
//     * @return URLShortener[] Returns an array of URLShortener objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?URLShortener
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
