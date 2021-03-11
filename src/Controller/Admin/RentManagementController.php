<?php

namespace App\Controller\Admin;

use App\Entity\Material;
use App\Entity\MaterialReservation;
use App\Form\MaterialReservationType;
use App\Form\MaterialType;
use App\Repository\MaterialReservationRepository;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class RentManagementController extends AbstractController
{
    /**
     * @Route("/material", name="material_index", methods={"GET"})
     */
    public function index(MaterielRepository $materielRepository,MaterialReservationRepository $materialReservationRepository): Response
    {
        return $this->render('material/index.html.twig', [
            'materials' => $materielRepository->findAll(),
            'material_reservations' => $materialReservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="material_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($material);
            $entityManager->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="material_show", methods={"GET"})
     */
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', [
            'material' => $material,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="material_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Material $material): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="material_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Material $material): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($material);
            $entityManager->flush();
        }

        return $this->redirectToRoute('material_index');
    }
    /**
     * @Route("/reservation/{id}/edit", name="material_reservation_edit_back", methods={"GET","POST"})
     */
    public function editReservation(Request $request, MaterialReservation $materialReservation): Response
    {
        $form = $this->createForm(MaterialReservationType::class, $materialReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('admin/Rent_Management/edit.html.twig', [
            'material_reservation' => $materialReservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reservation/{id}", name="material_reservation_show_back", methods={"GET"})
     */
    public function showReservation(MaterialReservation $materialReservation): Response
    {
        return $this->render('admin/Rent_Management/show.html.twig', [
            'material_reservation' => $materialReservation,
        ]);
    }
    /**
     * @Route("/reservation/{id}", name="material_reservation_delete_back", methods={"DELETE"})
     */
    public function deleteReservation(Request $request, MaterialReservation $materialReservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materialReservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $ax = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->setAvailability(true);
            $entityManager->persist($ax);
            $entityManager->remove($materialReservation);
            $entityManager->flush();

        }

        return $this->redirectToRoute('material_index');
    }
}
