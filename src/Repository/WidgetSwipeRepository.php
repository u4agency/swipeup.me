<?php

namespace App\Repository;

use App\Entity\WidgetSwipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WidgetSwipe>
 *
 * @method WidgetSwipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method WidgetSwipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method WidgetSwipe[]    findAll()
 * @method WidgetSwipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WidgetSwipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WidgetSwipe::class);
    }

    public function save(WidgetSwipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WidgetSwipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return WidgetSwipe[] Returns an array of WidgetSwipe objects
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

//    public function findOneBySomeField($value): ?WidgetSwipe
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
