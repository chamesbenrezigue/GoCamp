<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentManagementController extends AbstractController
{
    /**
     * @Route("/rent/management", name="rent_management")
     */
    public function index(): Response
    {
        return $this->render('admin/rent_management/index.html.twig', [
            'controller_name' => 'RentManagementController',
        ]);
    }
}
