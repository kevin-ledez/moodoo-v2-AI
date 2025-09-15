<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPublishedPosts()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->orderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPublishedPosts(int $page = 1, int $limit = 9)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->orderBy('p.publishedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findPublishedPostsByCategory(int $categoryId)
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('c.id = :categoryId')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPublishedPostsByCategoryPaginated(int $categoryId, int $page = 1, int $limit = 9)
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('c.id = :categoryId')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.publishedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function searchPublishedPosts(string $query)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('p.title LIKE :query OR p.content LIKE :query OR p.excerpt LIKE :query')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    public function searchPublishedPostsPaginated(string $query, int $page = 1, int $limit = 9)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('p.title LIKE :query OR p.content LIKE :query OR p.excerpt LIKE :query')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.publishedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        return new Paginator($query);
    }
    
    public function countPublishedPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function countPublishedPostsByCategory(int $categoryId)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->join('p.categories', 'c')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('c.id = :categoryId')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function countSearchPublishedPosts(string $query)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.status = :status')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('p.title LIKE :query OR p.content LIKE :query OR p.excerpt LIKE :query')
            ->setParameter('status', 'published')
            ->setParameter('now', new \DateTime())
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }
}