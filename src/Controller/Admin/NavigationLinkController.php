<?php

namespace App\Controller\Admin;

use App\Entity\NavigationLink;
use App\Form\NavigationLinkType;
use App\Repository\NavigationLinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/navigation/link')]
class NavigationLinkController extends AbstractController
{
    #[Route(name: 'admin_navigation_link_index', methods: ['GET'])]
    public function index(NavigationLinkRepository $navigationLinkRepository): Response
    {
        return $this->render('admin/navigation_link/index.html.twig', [
            'navigation_links' => $navigationLinkRepository->findAllOrderedByPosition(),
        ]);
    }

    #[Route('/new', name: 'admin_navigation_link_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $navigationLink = new NavigationLink();
        $form = $this->createForm(NavigationLinkType::class, $navigationLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir la date de mise à jour
            $navigationLink->setUpdatedAt(new \DateTimeImmutable());
            
            $entityManager->persist($navigationLink);
            $entityManager->flush();

            $this->addFlash('success', 'Le lien de navigation a été créé avec succès.');

            return $this->redirectToRoute('admin_navigation_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/navigation_link/new.html.twig', [
            'navigation_link' => $navigationLink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_navigation_link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NavigationLink $navigationLink, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NavigationLinkType::class, $navigationLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir la date de mise à jour
            $navigationLink->setUpdatedAt(new \DateTimeImmutable());
            
            $entityManager->flush();

            $this->addFlash('success', 'Le lien de navigation a été mis à jour avec succès.');

            return $this->redirectToRoute('admin_navigation_link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/navigation_link/edit.html.twig', [
            'navigation_link' => $navigationLink,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_navigation_link_delete', methods: ['POST'])]
    public function delete(Request $request, NavigationLink $navigationLink, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$navigationLink->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($navigationLink);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le lien de navigation a été supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_navigation_link_index', [], Response::HTTP_SEE_OTHER);
    }
}
