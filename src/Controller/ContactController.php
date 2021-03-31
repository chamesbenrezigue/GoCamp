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
    public function index(Request $request ,\Swift_Mailer $mailer): Response
    {
        $contact =new Contact();
        $form =$this->CreateForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $message = (new \Swift_Message("Subject :".$contact->getSubject()))
                ->setFrom($contact->getEmail())
                ->setTo('GoCamp315@gmail.com')
                ->setReplyTo($contact->getEmail())
                ->setBody(
                    "<br><br>first name : ". $contact->getFirstName() . "<br> <br>last name : ". $contact->getlastName() . "<br> <br>Email : ". $contact->getEmail() ."<br><br>Message :" . $contact->getMessage(), 'text/html'
                );

            $mailer->send($message);


            return $this->redirectToRoute('home');

        }
        return $this->render('contact/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
