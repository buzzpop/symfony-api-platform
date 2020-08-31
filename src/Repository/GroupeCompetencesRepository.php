<?php

namespace App\Repository;

use App\Entity\GroupeCompetences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupeCompetences|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeCompetences|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeCompetences[]    findAll()
 * @method GroupeCompetences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeCompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeCompetences::class);
    }

    // /**
    //  * @return GroupeCompetences[] Returns an array of GroupeCompetences objects
    //  */


    public function getGcompCompetences()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.referentiels', 'r')
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCompetencesByGroupId($id)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.competence', 'c')
            ->andWhere('g.id = :val')
            ->setParameter('val', $id)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?GroupeCompetences
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
