<?php

namespace App\Service;

use App\Repository\NavigationLinkRepository;

class NavigationService
{
    private NavigationLinkRepository $navigationLinkRepository;

    public function __construct(NavigationLinkRepository $navigationLinkRepository)
    {
        $this->navigationLinkRepository = $navigationLinkRepository;
    }

    /**
     * Récupère les liens de navigation visibles triés par position
     *
     * @return array
     */
    public function getVisibleLinks(): array
    {
        return $this->navigationLinkRepository->findVisibleOrderedByPosition();
    }
}