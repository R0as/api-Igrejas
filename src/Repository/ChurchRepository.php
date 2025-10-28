<?php

namespace App\Repository;

use App\Entity\Church;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Church>
 */
class ChurchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Church::class);
    }

    public function findPaginated(int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }
}
