<?php

namespace App\Controller;
use App\Entity\Materiel;
use App\Repository\MaterielRepository;
use App\Repository\ReservationMaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentingFrontController extends AbstractController
{
    /**
     * @Route("/renting/front", name="renting_front")
     */
    public function index(MaterielRepository $materielRepository,ReservationMaterielRepository $reservationMaterielRepository): Response
    {
        return $this->render('renting_front/index.html.twig',  [
            'materiels' => $materielRepository->findAll(),
            'reservation_materiels' => $reservationMaterielRepository->findAll(),
        ]);
    }

    /**
     * @Route("/renting/{id}", name="renting_materiel_show", methods={"GET"})
     * @param Materiel $materiel
     * @return Response
     */
    public function showMateriel(Materiel $materiel): Response
    {
        return $this->render('renting_front/materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

}
