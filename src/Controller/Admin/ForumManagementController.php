<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Entity\SubjectResponse;
use App\Repository\SubjectRepository;
use App\Repository\SubjectResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumManagementController extends AbstractController
{
    /**
     * @Route("/forum/management", name="forum_management")
     */
    public function index(SubjectRepository $subjectRepository,SubjectResponseRepository $subjectResponseRepository,Request $request): Response
    {
        return $this->render('admin/forum_management/index.html.twig', [
            'subjects' => $subjectRepository->findAll(),
            'subject_responses' => $subjectResponseRepository->findAll(),


        ]);
    }
    /**
     * @Route("/{id}/showSubject", name="back_subject_show", methods={"GET"})
     */
    public function showSubject(Subject $subject): Response
    {
        return $this->render('admin/forum_management/subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }
    /**
     * @Route("/{id}/deleleSubject", name="back_subject_delete", methods={"DELETE"})
     */
    public function deleteSubject(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_management');
    }
    /**
     * @Route("/{id}/showResponse", name="back_subject_response_show", methods={"GET"})
     */
    public function showResponseBack(SubjectResponse $subjectResponse): Response
    {
        return $this->render('admin/forum_management/subject_response/show.html.twig', [
            'subject_response' => $subjectResponse,
        ]);
    }
    /**
     * @Route("/{id}/deleteResponseBack", name="back_subject_response_delete", methods={"DELETE"})
     */
    public function deleteResponseBack(Request $request, SubjectResponse $subjectResponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subjectResponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subjectResponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_management');
    }

}

