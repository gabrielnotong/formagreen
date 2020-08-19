<?php

namespace App\Repository;

use App\Entity\UserMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMember[]    findAll()
 * @method UserMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMember::class);
    }

    // /**
    //  * @return UserMember[] Returns an array of UserMember objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMember
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
