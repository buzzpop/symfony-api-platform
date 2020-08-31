<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    const ITEMS_PER_PAGE = 2;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    public function findUsersByProfil($id, int $page = 1): Paginator
    {
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;
           $queryBuilder= $this->createQueryBuilder('u');
               $queryBuilder->innerJoin('u.profil', 'p')
               ->andWhere('p.id = :val')
               ->setParameter('val', $id)
               ->orderBy('u.id', 'ASC')
               ->getQuery()
               ->getResult()
               ;
        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);
        $queryBuilder->addCriteria($criteria);
        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }

    public function findApprenant($value)
    {
            return $this->createQueryBuilder('u')
                ->innerJoin('u.profil', 'p')
                ->andWhere('p.libelle = :val')
                ->setParameter('val', $value)
                ->orderBy('u.id', 'ASC')
                ->getQuery()
                ->getResult()
                ;
    }
    public function findApprenantById($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.profil', 'p')
            ->where('p.libelle = :va')
            ->setParameter('va', 'Apprenant')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFormateurById($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.profil', 'p')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->andWhere('p.libelle = :value')
            ->setParameter('value', 'Formateur')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFormateurs($value)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.profil', 'p')
            ->andWhere('p.libelle = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getApprenantByGroup($value, $p)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.groupes', 'g')
            ->innerJoin('u.profil', 'p')
            ->andWhere('g.nom = :val')
            ->setParameter('val', $value)
            ->andWhere('p.libelle = :vall')
            ->setParameter('vall', $p)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }





    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
