<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\NavigationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const POSTS_PER_PAGE = 9;
    
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PostRepository $postRepository, CategoryRepository $categoryRepository, NavigationService $navigationService): Response
    {
        $page = $request->query->getInt('page', 1);
        $paginator = $postRepository->findPublishedPosts($page, self::POSTS_PER_PAGE);
        $totalPosts = $postRepository->countPublishedPosts();
        $categories = $categoryRepository->findAllWithPostCount();
        $navigationLinks = $navigationService->getVisibleLinks();
        
        return $this->render('front/home/index.html.twig', [
            'posts' => $paginator,
            'totalPosts' => $totalPosts,
            'currentPage' => $page,
            'postsPerPage' => self::POSTS_PER_PAGE,
            'categories' => $categories,
            'navigationLinks' => $navigationLinks,
        ]);
    }
    
    #[Route('/category/{slug}', name: 'app_category_show')]
    public function category(string $slug, Request $request, CategoryRepository $categoryRepository, PostRepository $postRepository, NavigationService $navigationService): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }
        
        $page = $request->query->getInt('page', 1);
        $paginator = $postRepository->findPublishedPostsByCategoryPaginated($category->getId(), $page, self::POSTS_PER_PAGE);
        $totalPosts = $postRepository->countPublishedPostsByCategory($category->getId());
        $categories = $categoryRepository->findAllWithPostCount();
        $navigationLinks = $navigationService->getVisibleLinks();
        
        return $this->render('front/category/show.html.twig', [
            'category' => $category,
            'posts' => $paginator,
            'totalPosts' => $totalPosts,
            'currentPage' => $page,
            'postsPerPage' => self::POSTS_PER_PAGE,
            'categories' => $categories,
            'navigationLinks' => $navigationLinks,
        ]);
    }
    
    #[Route('/post/{slug}', name: 'app_post_show')]
    public function post(string $slug, PostRepository $postRepository, CategoryRepository $categoryRepository, NavigationService $navigationService): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug, 'status' => 'published']);
        
        if (!$post || ($post->getPublishedAt() > new \DateTime())) {
            throw $this->createNotFoundException('Article non trouvé');
        }
        
        $categories = $categoryRepository->findAllWithPostCount();
        $navigationLinks = $navigationService->getVisibleLinks();
        
        return $this->render('front/post/show.html.twig', [
            'post' => $post,
            'categories' => $categories,
            'navigationLinks' => $navigationLinks,
        ]);
    }
    
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, PostRepository $postRepository, CategoryRepository $categoryRepository, NavigationService $navigationService): Response
    {
        $query = $request->query->get('q', '');
        $page = $request->query->getInt('page', 1);
        $paginator = [];
        $totalPosts = 0;
        
        if (!empty($query)) {
            $paginator = $postRepository->searchPublishedPostsPaginated($query, $page, self::POSTS_PER_PAGE);
            $totalPosts = $postRepository->countSearchPublishedPosts($query);
        }
        
        $categories = $categoryRepository->findAllWithPostCount();
        $navigationLinks = $navigationService->getVisibleLinks();
        
        return $this->render('front/search/index.html.twig', [
            'posts' => $paginator,
            'totalPosts' => $totalPosts,
            'currentPage' => $page,
            'postsPerPage' => self::POSTS_PER_PAGE,
            'query' => $query,
            'categories' => $categories,
            'navigationLinks' => $navigationLinks,
        ]);
    }
}