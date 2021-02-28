<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 * Class AdminController
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/home", name="Admin")
     */
    public function Admin(): Response
    {
        return $this->render('admin/home.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
