<?php

namespace App\Repository;

use App\Entity\LivrablePartiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrablePartiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrablePartiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrablePartiel[]    findAll()
 * @method LivrablePartiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrablePartielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrablePartiel::class);
    }

    // /**
    //  * @return LivrablePartiel[] Returns an array of LivrablePartiel objects
    //  */
    public function findByIdALP($id, $id_br)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.apprenantLivrablePartiels', 'a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id)
            ->innerJoin('l.briefMaPromos', 'b')
            ->andWhere('b.id = :brief')
            ->setParameter('brief', $id_br)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?LivrablePartiel
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
