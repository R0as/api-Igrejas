<?php

namespace App\Repository;

use App\Entity\Church;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function findPaginatedByChurch(Church $church, int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.church = :church')
            ->andWhere('m.deletedAt IS NULL')
            ->setParameter('church', $church)
            ->orderBy('m.name', 'ASC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }
}
