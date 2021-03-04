<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumManagementController extends AbstractController
{
    /**
     * @Route("/forum/management", name="forum_management")
     * @param CommentaireRepository $commentaireRepository
     * @return Response
     */
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('admin/forum_management/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}/showCommentaire", name="back_commentaire_show", methods={"GET"})
     */
    public function showCommentaire(Commentaire $commentaire): Response
    {
        return $this->render('forum_management/commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/deleteCommentaire", name="back_commentaire_delete", methods={"DELETE"})
     */
    public function deleteCommentaire(Request $request, Commentaire $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_management');
    }

}
