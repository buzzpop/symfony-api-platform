<?php

namespace App\Repository;

use App\Entity\LivrableAttenduApprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrableAttenduApprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrableAttenduApprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrableAttenduApprenant[]    findAll()
 * @method LivrableAttenduApprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrableAttenduApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrableAttenduApprenant::class);
    }

    // /**
    //  * @return LivrableAttenduApprenant[] Returns an array of LivrableAttenduApprenant objects
    //  */
    public function findByApprenant($id)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.apprenant', 'a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?LivrableAttenduApprenant
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
