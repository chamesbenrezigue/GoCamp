<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripsController extends AbstractController
{
    /**
     * @Route("/trips", name="trips")
     */
    public function index(): Response
    {
        return $this->render('trips/index.html.twig', [
            'controller_name' => 'TripsController',
        ]);
    }
}
