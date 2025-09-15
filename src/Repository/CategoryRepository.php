<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllWithPostCount()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->leftJoin('c.posts', 'p')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->groupBy('c.id')
            ->orderBy('COUNT(p.id)', 'DESC');
            
        $query = $qb->getQuery();
        $results = $query->getResult();
        
        // Add post count to each category
        $categoriesWithCount = [];
        foreach ($results as $category) {
            $category->postCount = $this->getPostCountForCategory($category->getId());
            $categoriesWithCount[] = $category;
        }
        
        return $categoriesWithCount;
    }
    
    private function getPostCountForCategory(int $categoryId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(p.id)')
            ->leftJoin('c.posts', 'p')
            ->andWhere('c.id = :categoryId')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->setParameter('categoryId', $categoryId)
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }
}