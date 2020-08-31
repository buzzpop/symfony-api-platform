<?php

namespace App\Repository;

use App\Entity\BriefApprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefApprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefApprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefApprenant[]    findAll()
 * @method BriefApprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefApprenant::class);
    }

    // /**
    //  * @return BriefApprenant[] Returns an array of BriefApprenant objects
    //  */
    public function ifApprenantBrief($idAp, $idBr)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.apprenant', 'a')
            ->innerJoin('b.briefMaPromo', 'br')
            ->andWhere('br.id = :val')
            ->setParameter('val', $idBr)
            ->andWhere('a.id = :value')
            ->setParameter('value', $idAp)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?BriefApprenant
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
