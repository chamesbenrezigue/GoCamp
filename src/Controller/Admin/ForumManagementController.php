<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class ForumManagementController extends AbstractController
{
    /**
     * @Route("/forum/management", name="forum_management")
     */
    public function index(): Response
    {
        return $this->render('admin/forum_management/index.html.twig', [
            'controller_name' => 'ForumManagementController',
        ]);
    }
}
