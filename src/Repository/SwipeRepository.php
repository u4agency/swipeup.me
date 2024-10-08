<?php

namespace App\Repository;

use App\Entity\Swipe;
use App\Entity\SwipeUp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Swipe>
 *
 * @method Swipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Swipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Swipe[]    findAll()
 * @method Swipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Swipe::class);
    }

    public function add(Swipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Swipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAfterOrder(SwipeUp $swipeUp, int $oldOrder, int $newOrder): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.swipeup = :swipeup')
            ->andWhere('s.sequence > :oldSequence')
            ->andWhere('s.sequence <= :newSequence')
            ->setParameter('swipeup', $swipeUp->getId()->toBinary())
            ->setParameter('oldSequence', $oldOrder)
            ->setParameter('newSequence', $newOrder)
            ->getQuery()
            ->getResult();
    }

    public function getBeforeOrder(SwipeUp $swipeUp, int $oldOrder, int $newOrder): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.swipeup = :swipeup')
            ->andWhere('s.sequence < :oldSequence')
            ->andWhere('s.sequence >= :newSequence')
            ->setParameter('swipeup', $swipeUp->getId()->toBinary())
            ->setParameter('oldSequence', $oldOrder)
            ->setParameter('newSequence', $newOrder)
            ->getQuery()
            ->getResult();
    }

    public function countAll(): float|bool|int|string|null
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Swipe[] Returns an array of Swipe objects
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

//    public function findOneBySomeField($value): ?Swipe
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
