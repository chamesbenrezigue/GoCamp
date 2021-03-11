<?php

namespace App\Controller;

use App\Entity\Material;
use App\Entity\MaterialReservation;
use App\Entity\User;
use App\Form\MaterialReservationType;
use App\Repository\MaterialReservationRepository;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentingController extends AbstractController
{
    /**
     * @Route("/renting", name="renting")
     */
    public function FrontRenting(MaterielRepository $materielRepository,MaterialReservationRepository $materialReservationRepository): Response
    {
        return $this->render('renting/index.html.twig', [
            'materials' => $materielRepository->findAll(),
            'material_reservations' => $materialReservationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}", name="material_show_front", methods={"GET"})
     */
    public function show(Material $material): Response
    {
        return $this->render('renting/show.html.twig', [
            'material' => $material,
        ]);
    }

    /**
     * @Route("/reservation/{id}", name="material_reservation_Front", methods={"GET","POST"})
     */
    public function new(Request $request,$id): Response
    {
        $materialReservation = new MaterialReservation();
        $form = $this->createForm(MaterialReservationType::class, $materialReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
           $abn = $entityManager->getRepository(Material::class)->find($id);
            $us = $entityManager->getRepository(User::class)->find(1);
            $materialReservation->setMaterial($abn);
            $materialReservation->setUser($us);
            $ax = $entityManager->getRepository(Material::class)->find($id)->setAvailability(false);
            $entityManager->persist($materialReservation,$ax);
            $entityManager->flush();
            return $this->redirectToRoute('renting');
        }
        return $this->render('material_reservation/new.html.twig', [
            'material_reservation' => $materialReservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit", name="material_reservation_edit_front", methods={"GET","POST"})
     */
    public function edit(Request $request, MaterialReservation $materialReservation): Response
    {
        $form = $this->createForm(MaterialReservationType::class, $materialReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('renting');
        }

        return $this->render('material_reservation/edit.html.twig', [
            'material_reservation' => $materialReservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reservation/{id}", name="material_reservation_show_front", methods={"GET"})
     */
    public function showReservation(MaterialReservation $materialReservation): Response
    {
        return $this->render('material_reservation/show.html.twig', [
            'material_reservation' => $materialReservation,
        ]);
    }
    /**
     * @Route("/{id}", name="material_reservation_delete_front", methods={"DELETE"})
     */
    public function delete(Request $request, MaterialReservation $materialReservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materialReservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $ax = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->setAvailability(true);
            $entityManager->persist($ax);
            $entityManager->remove($materialReservation);
            $entityManager->flush();

        }

        return $this->redirectToRoute('renting');
    }
}
