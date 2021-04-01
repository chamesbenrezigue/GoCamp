<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class EventFrontController extends AbstractController
{
    /**
     * @Route("/event/front", name="event_front")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response


            {
             return $this->render('event_front/index.html.twig', [
                 'events' => $eventRepository->findAll(),]);
}

    /**
     * @Route("front/{id}", name="event_show", methods={"GET"})
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {
        return $this->render('event_front/show.html.twig', [
            'event' => $event,
        ]);

    }

}
