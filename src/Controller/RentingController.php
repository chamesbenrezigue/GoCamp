<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentingController extends AbstractController
{
    /**
     * @Route("/renting", name="renting")
     */
    public function index(): Response
    {
        return $this->render('renting/index.html.twig', [
            'controller_name' => 'RentingController',
        ]);
    }
}
