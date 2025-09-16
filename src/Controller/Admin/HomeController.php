<?php

namespace App\Controller\Admin;

use App\Repository\PageRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'admin_home')]
    public function index(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        CommentRepository $commentRepository,
        PageRepository $pageRepository
    ): Response {
        $postCount = $postRepository->count([]);
        $categoryCount = $categoryRepository->count([]);
        $commentCount = $commentRepository->count([]);
        $pageCount = $pageRepository->count([]);
        
        return $this->render('admin/home/index.html.twig', [
            'postCount' => $postCount,
            'categoryCount' => $categoryCount,
            'commentCount' => $commentCount,
            'pageCount' => $pageCount,
        ]);
    }
}