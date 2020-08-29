<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GreenSpace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GreenSpace|null find($id, $lockMode = null, $lockVersion = null)
 * @method GreenSpace|null findOneBy(array $criteria, array $orderBy = null)
 * @method GreenSpace[]    findAll()
 * @method GreenSpace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GreenSpaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GreenSpace::class);
    }

    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('g')->getQuery();
    }
}
