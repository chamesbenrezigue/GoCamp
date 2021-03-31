<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class SalesManagementController extends AbstractController
{
    /**
     * @Route("/sales/management", name="sales_management")
     */
    public function index(): Response
    {
        return $this->render('admin/sales_management/index.html.twig', [
            'controller_name' => 'SalesManagementController',
        ]);
    }
}
