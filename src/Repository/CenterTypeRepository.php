<?php

namespace App\Repository;

use App\Entity\CenterType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CenterType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CenterType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CenterType[]    findAll()
 * @method CenterType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CenterTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CenterType::class);
    }

    // /**
    //  * @return CenterType[] Returns an array of CenterType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CenterType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
