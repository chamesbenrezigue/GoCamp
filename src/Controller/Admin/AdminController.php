
<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
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
    public function Admin( UserRepository $userRepository): Response
    {
        $userMen = $userRepository->findBy(array('sexe'=>'Men'));
        $userWomen = $userRepository->findBy(array('sexe'=>'Women'));
            $userMen=count($userMen);
            $userWomen=count($userWomen);


        return $this->render('admin/home.html.twig', [
            'controller_name' => 'AdminController',
            'userMen'=>$userMen,
            'userWomen'=>$userWomen,

        ]);
    }
}
