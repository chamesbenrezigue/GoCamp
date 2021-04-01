<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('auth/login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response
    {
    }


}
