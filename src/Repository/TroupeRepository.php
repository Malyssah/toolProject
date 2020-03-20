<?php

namespace App\Repository;

use App\Entity\Troupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Troupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Troupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Troupe[]    findAll()
 * @method Troupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Troupe::class);
    }

    // /**
    //  * @return Troupe[] Returns an array of Troupe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Troupe
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
