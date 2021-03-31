<?php

namespace App\Controller;

use App\Form\EditProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
    /**
     * @Route("/profil_edit", name="edit_profil")
     */
    public function editPrfoil(Request $request,UserPasswordEncoderInterface  $encoder): Response
    {
            $user =$this->getUser();
            $form = $this->createForm(EditProfilType::class, $user );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $hash= $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $entityManager = $this->getDoctrine()->getManager();


                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('message','ADD');
                return $this->redirectToRoute('profil');
            }
        return $this->render('profil/edit_profil.html.twig', [
            'controller_name' => 'ProfilController',
            'form' => $form->createView(),
        ]);
    }
}
