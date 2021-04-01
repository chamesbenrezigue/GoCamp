<?php

namespace App\Controller;

use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseFrontController extends AbstractController
{
    /**
     * @Route("/purchase/front", name="purchase_front")
     * @param MaterielRepository $materielRepository
     * @return Response
     */

    public function index(MaterielRepository $materielRepository): Response
    {
        return $this->render('purchase_front/materiel/index.html.twig', [
            'materiels' => $materielRepository->findAll(),
        ]);
    }

}
