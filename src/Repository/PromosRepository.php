<?php

namespace App\Repository;

use App\Entity\Promos;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promos[]    findAll()
 * @method Promos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promos::class);
    }

    // /**
    //  * @return Promos[] Returns an array of Promos objects
    //  */

    public function getApprenant($value)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $apprenant = $builder
            ->select('a')
            ->from(User::class, 'a')
            ->innerJoin('a.profil', 'p')
            ->where('p.libelle= :value')
            ->setParameter('value', $value)
            ->getQuery();
        return $apprenant->getResult();
    }



        /*
                $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                $app_ref= $queryBuilder
                    ->select(['pr'])
                    ->from(Promos::class,'pr')
                    ->innerJoin('pr.groupe','gr')
                    ->innerJoin('gr.apprenant','ap')
                    ->where($queryBuilder->expr()->notIn('pr.id', $apprenant->getDQL()))
                    ->setParameter('profil', 'apprenant');
                    return $app_ref->getQuery();

            }
        */

    /*
    public function findOneBySomeField($value): ?Promos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
