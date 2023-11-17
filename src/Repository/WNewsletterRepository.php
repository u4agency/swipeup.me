<?php

namespace App\Repository;

use App\Entity\WNewsletter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WNewsletter>
 *
 * @method WNewsletter|null find($id, $lockMode = null, $lockVersion = null)
 * @method WNewsletter|null findOneBy(array $criteria, array $orderBy = null)
 * @method WNewsletter[]    findAll()
 * @method WNewsletter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WNewsletterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WNewsletter::class);
    }

//    /**
//     * @return WNewsletter[] Returns an array of WNewsletter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WNewsletter
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
