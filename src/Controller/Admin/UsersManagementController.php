<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersManagementController extends AbstractController
{
    /**
     * @Route("/users/management", name="users_management")
     */
    public function index(): Response
    {
        return $this->render('admin/users_management/index.html.twig', [
            'controller_name' => 'UsersManagementController',
        ]);
    }
}
