<?php

namespace App\Repository;

use App\Entity\Peuple;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Peuple|null find($id, $lockMode = null, $lockVersion = null)
 * @method Peuple|null findOneBy(array $criteria, array $orderBy = null)
 * @method Peuple[]    findAll()
 * @method Peuple[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeupleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Peuple::class);
    }

    // /**
    //  * @return Peuple[] Returns an array of Peuple objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Peuple
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
