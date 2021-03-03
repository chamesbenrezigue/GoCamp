<?php

namespace App\Controller;

use App\Entity\ReservationMateriel;
use App\Form\ReservationMaterielType;
use App\Repository\ReservationMaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation/materiel")
 */
class ReservationMaterielController extends AbstractController
{
    /**
     * @Route("/", name="reservation_materiel_index", methods={"GET"})
     */
    public function index(ReservationMaterielRepository $reservationMaterielRepository): Response
    {
        return $this->render('reservation_materiel/index.html.twig', [
            'reservation_materiels' => $reservationMaterielRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="front_reservation_materiel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reservationMateriel = new ReservationMateriel();
        $form = $this->createForm(ReservationMaterielType::class, $reservationMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservationMateriel);
            $entityManager->flush();

            return $this->redirectToRoute('renting_front');
        }

        return $this->render('renting_front/reservation_materiel/new.html.twig', [
            'reservation_materiel' => $reservationMateriel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="front_reservation_materiel_show", methods={"GET"})
     */
    public function show(ReservationMateriel $reservationMateriel): Response
    {
        return $this->render('reservation_materiel/show.html.twig', [
            'reservation_materiel' => $reservationMateriel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="front_reservation_materiel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReservationMateriel $reservationMateriel): Response
    {
        $form = $this->createForm(ReservationMaterielType::class, $reservationMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_materiel_index');
        }

        return $this->render('reservation_materiel/edit.html.twig', [
            'reservation_materiel' => $reservationMateriel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="front_reservation_materiel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ReservationMateriel $reservationMateriel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationMateriel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservationMateriel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_materiel_index');
    }
}
