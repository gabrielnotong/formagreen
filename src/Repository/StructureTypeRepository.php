<?php

namespace App\Repository;

use App\Entity\StructureType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StructureType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StructureType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StructureType[]    findAll()
 * @method StructureType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructureTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StructureType::class);
    }

    // /**
    //  * @return StructureType[] Returns an array of StructureType objects
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
    public function findOneBySomeField($value): ?StructureType
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
