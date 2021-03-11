<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class EventManagementController extends AbstractController
{
    /**
     * @Route("/event/management", name="event_management", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response


    {
        return $this->render('admin/event_management/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);

        }

    /**
     * @Route("/new", name="event_management_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
            * @var UploadedFile $file
            */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'),$fileName);
            $event->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_management');
        }

        return $this->render('admin/event_management/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_management_show", methods={"GET"})
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {
        return $this->render('admin/event_management/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_management_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
            * @var UploadedFile $file
            */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'),$fileName);
            $event->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_management');
        }

        return $this->render('admin/event_management/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_management_delete", methods={"DELETE"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_management');
    }
}
