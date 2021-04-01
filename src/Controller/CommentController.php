<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use JardinBundle\Entity\Commentaire;
use JardinBundle\Entity\Evenement;
use JardinBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     * @param Comment $comment
     * @return Response
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }




    public function addCommentAction(Request $request, $id){

        $username =(string) $this->getUser();

        $em=$this->getDoctrine()->getManager();
        $currentUser = $em->getRepository(User::class)->findOneBy(array('username'=>$username));

        $text = $request->get('text');

        $comment =new Comment();
        $comment->setContenu($text);
        $comment->setDate(new \DateTime());

        $event = $em->getRepository(Event::class)->find($id);

        $comment->setEvenement($event);
        $comment->setUser($currentUser);

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('',array('id' => $id));
    }

    public function deleteCommentAction( $id){
        $em=$this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->find($id);
        $idEvent = $comment->getEvenement()->getId();
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('index.html.twig',array('id' => $idEvent));
    }


}
