<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class CommentController extends AbstractController
{
    #[Route('/comments', name: 'admin_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy([], ['createdAt' => 'DESC']);
        
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/comments/{id}/approve', name: 'admin_comment_approve', methods: ['POST'])]
    public function approve(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('approve'.$comment->getId(), $request->request->get('_token'))) {
            $comment->setStatus('approved');
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_comment_index');
    }

    #[Route('/comments/{id}/spam', name: 'admin_comment_spam', methods: ['POST'])]
    public function spam(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('spam'.$comment->getId(), $request->request->get('_token'))) {
            $comment->setStatus('spam');
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_comment_index');
    }

    #[Route('/comments/{id}', name: 'admin_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_comment_index');
    }
}