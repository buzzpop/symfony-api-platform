<?php

namespace App\Repository;

use App\Entity\Apprenant;
use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Apprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apprenant[]    findAll()
 * @method Apprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apprenant::class);
    }

    // /**
    //  * @return Apprenant[] Returns an array of Apprenant objects
    //  */

    public function ifApprenantGroupe($idAp, $idGr)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.groupes', 'g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $idGr)
            ->andWhere('a.id = :app')
            ->setParameter('app', $idAp)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByGroupe($id)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.groupes', 'g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Apprenant
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
