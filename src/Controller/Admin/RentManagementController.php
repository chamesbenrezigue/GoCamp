<?php

namespace App\Controller\Admin;
use App\Entity\ReservationMateriel;
use App\Form\ReservationMaterielType;
use App\Repository\ReservationMaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Materiel;
use App\Form\Materiel1Type;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Request;

class RentManagementController extends AbstractController
{
    /**
     * @Route("/rent/management", name="rent_management",methods={"GET"})
     */
    public function index(MaterielRepository $materielRepository,ReservationMaterielRepository $reservationMaterielRepository): Response
    {
        return $this->render('admin/rent_management/index.html.twig',[
            'materiels' => $materielRepository->findAll(),
            'reservation_materiels' => $reservationMaterielRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="materiel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(Materiel1Type::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('rent_management');
        }

        return $this->render('admin/rent_management/materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_show", methods={"GET"})
     */
    public function show(Materiel $materiel): Response
    {
        return $this->render('admin/rent_management/materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materiel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Materiel $materiel): Response
    {
        $form = $this->createForm(Materiel1Type::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rent_management');
        }

        return $this->render('admin/rent_management/materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Materiel $materiel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rent_management');
    }

    /*********************RES******************************************************************/
    /**
     * @Route("/showreservation", name="reservation_materiel_show", methods={"GET"})
     */
    public function showreservation(ReservationMateriel $reservationMateriel): Response
    {
        return $this->render('admin/rent_management/reservation_materiel/show.html.twig', [
            'reservation_materiel' => $reservationMateriel,
        ]);
    }

    /**
     * @Route("/editreservation", name="reservation_materiel_edit", methods={"GET","POST"})
     */
    public function editreservation(Request $request, ReservationMateriel $reservationMateriel): Response
    {
        $form = $this->createForm(ReservationMaterielType::class, $reservationMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rent_management');
        }

        return $this->render('admin/rent_management/reservation_materiel/edit.html.twig', [
            'reservation_materiel' => $reservationMateriel,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/deletereservation", name="reservation_materiel_delete", methods={"DELETE"})
     */
    public function deletereservation(Request $request, ReservationMateriel $reservationMateriel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationMateriel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservationMateriel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rent_management');
    }




}
