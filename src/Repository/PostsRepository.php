<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Posts;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 *
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function add(Posts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Posts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAuthor(User $user)
    {
        $qb = $this->createQueryBuilder("p")
            ->where(':authors MEMBER OF p.authors')
            ->andWhere('p.status = :status')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameters(array(
                'authors' => $user,
                'status' => "Published")
            )
        ;
        return $qb->getQuery()->getResult();
    }

    public function findByCategory(Category $category)
    {
        $qb = $this->createQueryBuilder("p")
            ->where(':categories MEMBER OF p.categories')
            ->andWhere('p.status = :status')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameters(array(
                    'categories' => $category,
                    'status' => "Published")
            )
        ;
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Posts[] Returns an array of Posts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Posts
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
