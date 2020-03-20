<?php

namespace App\Repository;

use App\Entity\ServeurUserPeuple;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ServeurUserPeuple|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServeurUserPeuple|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServeurUserPeuple[]    findAll()
 * @method ServeurUserPeuple[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServeurUserPeupleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServeurUserPeuple::class);
    }

    // /**
    //  * @return ServeurUserPeuple[] Returns an array of ServeurUserPeuple objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ServeurUserPeuple
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
