<?php

namespace App\Service;

use App\Repository\NavigationLinkRepository;
use App\Repository\PageRepository;

class NavigationService
{
    private NavigationLinkRepository $navigationLinkRepository;
    private PageRepository $pageRepository;

    public function __construct(
        NavigationLinkRepository $navigationLinkRepository,
        PageRepository $pageRepository
    ) {
        $this->navigationLinkRepository = $navigationLinkRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Récupère les liens de navigation visibles triés par position
     *
     * @return array
     */
    public function getVisibleLinks(): array
    {
        $navigationLinks = $this->navigationLinkRepository->findVisibleOrderedByPosition();
        $pagesInMenu = $this->pageRepository->findPagesToShowInMenu();
        
        // Combiner les liens de navigation et les pages du menu
        $allLinks = array_merge($navigationLinks, $pagesInMenu);
        
        // Trier d'abord par position (liens de navigation) puis par titre
        usort($allLinks, function($a, $b) {
            // Si les deux éléments ont une position, trier par position
            if (method_exists($a, 'getPosition') && method_exists($b, 'getPosition')) {
                return $a->getPosition() <=> $b->getPosition();
            }
            
            // Si un seul élément a une position, il vient en premier
            if (method_exists($a, 'getPosition') && !method_exists($b, 'getPosition')) {
                return -1;
            }
            
            if (!method_exists($a, 'getPosition') && method_exists($b, 'getPosition')) {
                return 1;
            }
            
            // Sinon, trier par titre
            if (method_exists($a, 'getTitle') && method_exists($b, 'getTitle')) {
                return strcmp($a->getTitle(), $b->getTitle());
            }
            
            return 0;
        });
        
        return $allLinks;
    }
}