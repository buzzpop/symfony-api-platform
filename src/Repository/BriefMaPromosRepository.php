<?php

namespace App\Repository;

use App\Entity\BriefMaPromos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefMaPromos|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefMaPromos|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefMaPromos[]    findAll()
 * @method BriefMaPromos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefMaPromosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefMaPromos::class);
    }

    // /**
    //  * @return BriefMaPromos[] Returns an array of BriefMaPromos objects
    //  */

    public function findBriefPromo($id_br, $id_pr)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.Promos', 'p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id_pr)
            ->innerJoin('b.brief', 'br')
            ->andWhere('br.id = :brief')
            ->setParameter('brief', $id_br)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?BriefMaPromos
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
