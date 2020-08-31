<?php

namespace App\Repository;

use App\Entity\Referentiels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referentiels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referentiels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referentiels[]    findAll()
 * @method Referentiels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentielsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referentiels::class);
    }

    // /**
    //  * @return Referentiels[] Returns an array of Referentiels objects
    //  */




    /*
    public function findOneBySomeField($value): ?Referentiels
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getCompetencesByGroupIdByRefId(int $idr,int $idg)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.groupeCompetences','g')
            ->innerJoin('g.competence','c')
            ->andWhere('g.id = :val')
            ->setParameter('val', $idg)
            ->andWhere('r.id = :va')
            ->setParameter('va', $idr)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
