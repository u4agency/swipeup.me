<?php

namespace App\Repository;

use App\Entity\SwipeUp;
use App\Service\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SwipeUp>
 *
 * @method SwipeUp|null find($id, $lockMode = null, $lockVersion = null)
 * @method SwipeUp|null findOneBy(array $criteria, array $orderBy = null)
 * @method SwipeUp[]    findAll()
 * @method SwipeUp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwipeUpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SwipeUp::class);
    }

    public function save(SwipeUp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SwipeUp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function randomRow(): array|float|int|string
    {
        return $this->createQueryBuilder('a')
            ->addSelect('a')
            ->select('a.slug')
            ->where('a.status = :status')
            ->setParameter('status', Status::PUBLIC)
//            ->where('a.featuredSwipeUp = :homepage')
//            ->setParameter('homepage', true)
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAll($author, $isAdmin = false): array|null
    {
        $qb = $this->createQueryBuilder('a')
            ->addSelect('a')
            ->orderBy('a.updatedAt', 'DESC')
            ->where('a.author = :author');


        if (!$isAdmin) $qb
            ->andWhere('a.status != :status')
            ->setParameter('status', Status::DELETED);

        return $qb
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAllExceptDeleted($author): int
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.author = :author')
            ->andWhere('a.status != :deletedStatus')
            ->setParameter('author', $author)
            ->setParameter('deletedStatus', Status::DELETED)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return SwipeUp[] Returns an array of SwipeUp objects
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

//    public function findOneBySomeField($value): ?SwipeUp
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
