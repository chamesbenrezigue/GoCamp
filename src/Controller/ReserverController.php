<?php

namespace App\Controller;

use App\Entity\Reservation;

use App\Form\ReserverType;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reserver")
 */
class ReserverController extends AbstractController
{

    /**
     * @Route("/listr", name="list_reservation")
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('App:Reservation')->findAll();
        return $this->render('Reserver/show.html.twig',array('reservations'=>$reservation));


    }
    /**
     * @Route("/backreservation", name="backreservation")
     *
     */
    public function backreservation()
    {

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('App:Reservation')->findAll();
        return $this->render('admin/event_management/reservation.html.twig',array('reservations'=>$reservation));


    }
    /**
     * @Route("/ajoutreservation", name="addreservation", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $reserver = new Reservation();
        $form = $this->createForm(ReserverType::class, $reserver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ef = $this->getDoctrine()->getManager();
            $ef->persist($reserver);
            $ef->flush();

            $this->addFlash('success', 'Ajout effectuée avec succées');
            return $this->redirectToRoute('list_reservation');
        }

        return $this->render('reserver/new.html.twig', [
            'reserver' => $reserver,
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
            "Attachment" => true
        ]);


    }

    /**
     * @Route("/{id}/edit", name="reserver_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reserver): Response
    {
        $form = $this->createForm(ReserverType::class, $reserver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reserver_index');
        }

        return $this->render('reserver/edit.html.twig', [
            'reserver' => $reserver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reserver_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reservation $reserver): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserver->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reserver);
            $entityManager->flush();
        }

        return $this->redirectToRoute('list_reservation');
    }
    /**
     * @param $id
     * @Route("/approuverReservation/{id}",name="approuverReservation")
     */
    public function approuverReservation($id,\Swift_Mailer $mailer)
    {
        $em= $this->getDoctrine()->getManager();
        $reservation=$em->getRepository( Reservation::class)->find($id);
        $reservation->setApprouve(1);
        $message = (new \Swift_Message('Validation Réservation'))
            ->setFrom('GoCamp315@gmail.com')
            ->setTo('taleb.islem@esprit.tn')
            ->setBody(
                $this->renderView(
                    'admin/event_management/confirmation_mail.html.twig'
                ),
                'text/html'
            );

        $em->merge($reservation);
        $em->flush();
        $mailer->send($message);


        return $this->redirectToRoute('backreservation',array('id'=>$id));

    }
}
