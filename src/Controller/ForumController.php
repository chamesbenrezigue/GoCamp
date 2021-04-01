<?php

namespace App\Controller;


use App\Entity\Jaime;

use App\Entity\SubjectSearch;
use App\Entity\Subject;
use App\Entity\SubjectResponse;
use App\Form\SubjectResponseType;
use App\Form\SubjectType;
use App\Repository\JaimeRepository;
use App\Form\SubjectSearchType;
use App\Repository\SubjectRepository;
use App\Repository\SubjectResponseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(SubjectRepository $subjectRepository,SubjectResponseRepository $subjectResponseRepository,Request $request): Response
    {
        $session = new Session();
        $session->set('id',1);
        $limit=2;
        $page=(int)$request->query->get("page",2);
        $comm=$subjectRepository->paginatedSubjects($page,$limit);
        $total=$subjectRepository->getTotalSubjects();
        return $this->render('forum/index.html.twig', [
            'subjects' => $comm,
            'subject_responses' => $subjectResponseRepository->findAll(),
            'pagination' => true,
            'total'=>$total,
            'limit'=>$limit,
            'page'=>$page

        ]);
    }

    /**
     * @Route("/new", name="subject_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subject_show", methods={"GET"})
     */
    public function show(Subject $subject,$id,SubjectRepository $repository): Response
    {
        $sub = new Subject();
        $entityManager = $this->getDoctrine()->getManager();
        $sub = $entityManager->getRepository(Subject::class)->find($id);
        $val = $sub->getViews() ;
        $val = $val + 1 ;
        $sub->setViews($val);
        $entityManager->flush();
        return $this->render('forum/subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="subject_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subject_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum');
    }
    /**
     * @Route("/new/Respnse", name="subject_response_new", methods={"GET","POST"})
     */
    public function newResponse(Request $request): Response
    {
        $subjectResponse = new SubjectResponse();
        $form = $this->createForm(SubjectResponseType::class, $subjectResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subjectResponse);
            $entityManager->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/subject_response/new.html.twig', [
            'subject_response' => $subjectResponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="subject_response_show", methods={"GET"})
     */
    public function showResponse(SubjectResponse $subjectResponse): Response
    {
        return $this->render('forum/subject_response/show.html.twig', [
            'subject_response' => $subjectResponse,
        ]);
    }

    /**
     * @Route("/{id}/editResponse", name="subject_response_edit", methods={"GET","POST"})
     */
    public function editResponse(Request $request, SubjectResponse $subjectResponse): Response
    {
        $form = $this->createForm(SubjectResponseType::class, $subjectResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/subject_response/edit.html.twig', [
            'subject_response' => $subjectResponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subject_response_delete", methods={"DELETE"})
     */
    public function deleteResponse(Request $request, SubjectResponse $subjectResponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subjectResponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subjectResponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum');
    }


    /**
     *  @Route("/{id}/like", name="post_like")
     * @param Subject $subject
     * @param JaimeRepository $jaimeRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function like(Subject $subject,JaimeRepository $jaimeRepository,UserRepository $userRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user=$userRepository->find($this->get('session')->get('id'));
        if(!$user) return $this->json([
            'code'=>403,
            'message'=>"Unauthorized"
        ],403);
        if($subject->isLikedByUser($user))
        {
            $like = $jaimeRepository->findOneBy([
                'subject'=>$subject,
                'user'=>$user
            ]);

            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json([
                'code'=>200,
                'message'=>'like is removed',
                'likes'=>$jaimeRepository->count(['subject'=>$subject])
            ],200);
        }
        $like = new Jaime();
        $like->setSubject($subject);
        $like->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();
        return  $this->json([
            'code'=>200,
            'message'=>'like is added',
            'likes'=>$jaimeRepository->count(['subject'=>$subject])
        ],200);
    }

    /**
     * @Route("/search", name="search")
     */
    function RechercheSubject(SubjectRepository $subjectRepository, Request $request)
    {

        $category=$request->get('search');
        $subject=$subjectRepository->SearchSubject($category);
        $total=$subjectRepository->getTotalSubjects();
        return $this->render('forum/subject/Recherche.html.twig' , ['subjects'=>$subject

        ]);
    }

}


