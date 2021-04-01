
<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\MonologBundle\SwiftMailer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/auth")
 */
class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function Registration(Request $request , EntityManagerInterface  $entityManager ,UserPasswordEncoderInterface  $encoder, \Swift_Mailer $mailer)
    {
        $user =new User();
        $form =$this->CreateForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $hash= $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            // TOKEN
            $user->setActivationToken(md5(uniqid()));

            $entityManager ->persist($user);
            $entityManager ->flush();

            $message = (new \Swift_Message('Activation de votre compte'))
            //
            ->setFrom('GoCamp315@gmail.com')
                //
            ->setTo($user->getEmail())
                //
            ->setBody(
                $this->renderView('email/activation.html.twig',[ 'token' => $user->getActivationToken()]),
                    "text/html"
                );
            //on envoie l'email
            $mailer->send($message);




            return $this->redirectToRoute('login');

        }
        return $this->render('auth/register/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token , UserRepository $userRepo){
      // Token already exists
        $user = $userRepo->findOneBy(['activation_token'=> $token]);
          if(!$user){
              throw $this->createNotFoundException('User does not exist');
          }
          $user->setActivationToken(null);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($user);
          $entityManager->flush();

          $this->addFlash('message','Your account is activated');
          return $this->redirectToRoute('home');

    }
}
