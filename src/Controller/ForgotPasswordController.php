<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/forgot_password", name="forgot_password")
     */
    public function index(): Response
    {
        return $this->render('auth/forgot_password/index.html.twig', [
            'controller_name' => 'ForgotPasswordController',
        ]);
    }
}
