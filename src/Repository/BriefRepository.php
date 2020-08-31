<?php

namespace App\Repository;

use App\Entity\Brief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brief|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brief|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brief[]    findAll()
 * @method Brief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brief::class);
    }

    // /**
    //  * @return Brief[] Returns an array of Brief objects
    //  */

    public function getBriefByGroupByPromo($id_g,$id_p)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.etatBriefGroupes','ebg')
            ->innerJoin('ebg.groupe','g')
            ->innerJoin('g.promos','p')
            ->andWhere('g.id = :val')
            ->setParameter('val', $id_g)
            ->andWhere('g.statut = :va')
            ->setParameter('va', 'en cours')
            ->andWhere('p.id = :value')
            ->setParameter('value', $id_p)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBriefPromo($id)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.briefMaPromos','bmp')
            ->innerJoin('bmp.Promos','p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function getBriefBrouillons($id)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.formateurs','f')
            ->andWhere('f.id = :value')
            ->setParameter('value', $id)
            ->andWhere('b.etat_brouillons_assigne_valide = :val')
            ->setParameter('val', 'brouillon')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBriefFValid($id)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.formateurs','f')
            ->andWhere('f.id = :val')
            ->setParameter('val', $id)
            ->andWhere('b.etat_brouillons_assigne_valide= :value')
            ->setParameter('value', 'valide')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBriefOfPromos($id_p,$id_b)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.briefMaPromos','bmp')
            ->innerJoin('bmp.Promos','p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id_p)
            ->andWhere('b.id= :value')
            ->setParameter('value', $id_b)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Brief
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
