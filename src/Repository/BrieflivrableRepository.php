<?php

namespace App\Repository;

use App\Entity\Brieflivrable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brieflivrable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brieflivrable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brieflivrable[]    findAll()
 * @method Brieflivrable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrieflivrableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brieflivrable::class);
    }

    // /**
    //  * @return Brieflivrable[] Returns an array of Brieflivrable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Brieflivrable
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
