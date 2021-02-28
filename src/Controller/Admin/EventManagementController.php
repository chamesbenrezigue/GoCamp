<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventManagementController extends AbstractController
{
    /**
     * @Route("/event/management", name="event_management")
     */
    public function index(): Response
    {
        return $this->render('admin/event_management/index.html.twig', [
            'controller_name' => 'EventManagementController',
        ]);
    }
}
