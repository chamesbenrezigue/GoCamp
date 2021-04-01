<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
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
     * @Route("/listdereservation", name="listdereservation",methods={"GET"})
     * @param ReservationRepository $reservationRepository
     * @param $pdfOptions
     * @return Response
     */
    public function listdereservation (ReservationRepository $reservationRepository, $pdfOptions):Response
    {

        $dompdf = new Dompdf($pdfOptions);
        $reservation = $reservationRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reserver/listdereservation.html.twig', ['reservations' => $reservation,]);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
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
    /**
     * @Route("/rechercheP", name="rechercheP")
     */
    public function recherchePartitre(Request $request){
        $title=$request->get('recherche');
        $listevent=$this->getDoctrine()
            ->getRepository(event::class)
            ->recherchePartitle($title);
        return $this->render('admin/event_management/index.html.twig',['events'=>$listevent]);
    }

}
