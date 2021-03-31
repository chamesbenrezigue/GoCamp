<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request , EntityManagerInterface  $entityManager): Response
    {
        $contact =new Contact();
        $form =$this->CreateForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $this->addFlash('success','your email is sent successfully');
            $entityManager ->persist($contact);
            $entityManager ->flush();
            return $this->redirectToRoute('home');

        }
        return $this->render('contact/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
