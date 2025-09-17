<?php

namespace App\Controller\Front;

use App\Entity\Page;
use App\Repository\PageRepository;
use App\Repository\CategoryRepository;
use App\Service\NavigationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/page')]
class PageFrontController extends AbstractController
{
    #[Route('/{slug}', name: 'app_page_show')]
    public function show(
        string $slug, 
        PageRepository $pageRepository, 
        CategoryRepository $categoryRepository,
        NavigationService $navigationService
    ): Response {
        $page = $pageRepository->findOneBySlug($slug);
        
        if (!$page) {
            throw $this->createNotFoundException('Page non trouvée');
        }
        
        $categories = $categoryRepository->findAllWithPostCount();
        $navigationLinks = $navigationService->getVisibleLinks();
        $pagesInMenu = $pageRepository->findPagesToShowInMenu();
        
        return $this->render('front/page/show.html.twig', [
            'page' => $page,
            'categories' => $categories,
            'navigationLinks' => $navigationLinks,
            'pagesInMenu' => $pagesInMenu,
        ]);
    }
}