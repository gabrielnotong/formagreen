<?php

namespace App\Repository;

use App\Entity\TrainingStructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrainingStructure|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingStructure|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingStructure[]    findAll()
 * @method TrainingStructure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingStructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingStructure::class);
    }

    // /**
    //  * @return TrainingStructure[] Returns an array of TrainingStructure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrainingStructure
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
