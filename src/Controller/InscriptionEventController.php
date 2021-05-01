<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\InscriptionEvent;
use App\Form\InscriptionEventType;
use App\Repository\InscriptionEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription/event")
 */
class InscriptionEventController extends AbstractController
{
    /**
     * @Route("/", name="inscription_event_index", methods={"GET"})
     * @param InscriptionEventRepository $inscriptionEventRepository
     * @return Response
     */
    public function index(InscriptionEventRepository $inscriptionEventRepository): Response
    {
        return $this->render('inscription_event/index.html.twig', [
            'inscription_events' => $inscriptionEventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="inscription_event_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $inscriptionEvent = new InscriptionEvent();
        $form = $this->createForm(InscriptionEventType::class, $inscriptionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'),$fileName);
            $inscriptionEvent->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inscriptionEvent);
            $entityManager->flush();

            return $this->redirectToRoute('inscription_event_index');
        }

        return $this->render('inscription_event/new.html.twig', [
            'inscription_event' => $inscriptionEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inscription_event_show", methods={"GET"})
     * @param InscriptionEvent $inscriptionEvent
     * @return Response
     */
    public function show(InscriptionEvent $inscriptionEvent): Response
    {
        return $this->render('inscription_event/show.html.twig', [
            'inscription_event' => $inscriptionEvent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="inscription_event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param InscriptionEvent $inscriptionEvent
     * @return Response
     */
    public function edit(Request $request, InscriptionEvent $inscriptionEvent): Response
    {
        $form = $this->createForm(InscriptionEventType::class, $inscriptionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'),$fileName);
            $inscriptionEvent->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inscription_event_index');
        }

        return $this->render('inscription_event/edit.html.twig', [
            'inscription_event' => $inscriptionEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inscription_event_delete", methods={"DELETE"})
     * @param Request $request
     * @param InscriptionEvent $inscriptionEvent
     * @return Response
     */
    public function delete(Request $request, InscriptionEvent $inscriptionEvent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscriptionEvent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($inscriptionEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('inscription_event_index');
    }


}
