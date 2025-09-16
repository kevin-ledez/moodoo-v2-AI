<?php

namespace App\Repository;

use App\Entity\NavigationLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NavigationLink>
 */
class NavigationLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavigationLink::class);
    }

    /**
     * @return NavigationLink[] Returns an array of visible NavigationLink objects ordered by position
     */
    public function findVisibleOrderedByPosition(): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.isVisible = :isVisible')
            ->setParameter('isVisible', true)
            ->orderBy('n.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return NavigationLink[] Returns an array of all NavigationLink objects ordered by position
     */
    public function findAllOrderedByPosition(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
