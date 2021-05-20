<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Materiel;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PurchaseFrontController extends AbstractController
{
    /**
     * @Route("/materiel/view", name ="Allmateriel", methods={"GET"})
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface

     */
    public function AllmaterielJSON (NormalizerInterface $Normalizer)
    {
        $repository=$this->getDoctrine()->getRepository(Materiel::class);
        $mat=$repository->findAll();

        $jsonContent = $Normalizer->normalize($mat, 'json',['groups'=>'post:read']);
        /* return $this->render('user/allusersJSON.html.twig',[
             'data' => $jsonContent,
         ]);**/
        return new Response(json_encode($jsonContent));;

    }
    /**
     * @Route("/purchase/front", name="purchase_front")
     * @param MaterielRepository $materielRepository
     * @return Response
     */

    public function index(MaterielRepository $materielRepository): Response
    {/*
      $recruteurCheck = $repository->findOneBy(['mail' => $recruteur->getMail()]);
            if($recruteur->getMdp()==$recruteurCheck->getMdp())
            {
                $session= new Session();
                $session->set('id',$recruteurCheck->getId());
                $session->set('nom',$recruteurCheck->getNom());
                $session->set('type',$recruteurCheck->getType());
                $session->set('mail',$recruteur->getMail());
                $session->set('competence',$recruteurCheck->getCompetence());
            }
    */

        return $this->render('purchase_front/materiel/index.html.twig', [
            'materiels' => $materielRepository->findAll(),
        ]);
    }
    /**
     * @Route("/purchase_back", name="purchase_back")
     * @param MaterielRepository $materielRepository
     * @return Response
     */

    public function listsdecommande (MaterielRepository $materielRepository): Response
    {
        return $this->render('admin/materiel_commander/index.html.twig', [
            'materiels' => $materielRepository->findAll(),
        ]);
    }

    /**
     * @Route("materiel_show/{id}", name="materiel_show", methods={"GET","POST"})
     * @param Request $request
     * @param Materiel $materiel
     * @param CommentRepository $commentRepository
     * @param $id
     * @return Response
     */

    public function show (Request $request,Materiel $materiel,CommentRepository $commentRepository,$id): Response
    {
        $user = $this->getUser();

        $comment = new Comment();
        $form1 = $this->createForm(CommentType::class,$comment);
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setIdmateriel($materiel);
            $comment->setUserName($user->getFirstName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('materiel_show',['id'=>$id]);
        }
        $offrepost = $commentRepository->findBy(['idmateriel'=>$materiel]);

        return $this->render('purchase_front/materiel/show.html.twig', [
            'materiel' => $materiel,
            'comments'=> $offrepost,
            'commentForm'=>$form1->createView(),
        ]);
    }
    /**
     * @Route("confirmer/{id}",name="confirmer", methods={"GET","POST"})
     */
    public function confirmer(\Swift_Mailer $mailer,$id): Response
    {

        $val = $this->getUser();

        // Configure Dompdf according to your needs

        $message = (new \Swift_Message('confirmation achat '))
            //
            ->setFrom('GoCamp315@gmail.com')
            //
            ->setTo($val->getEmail())
            //
            ->setBody(
                $this->renderView('email/mailerDhia.html.twig'),
                "text/html"
            );
        //on envoie l'email
        $mailer->send($message);
        return $this->redirectToRoute('purchase_front');
    }
    /**
     * @Route("/recherche/front", name="recherchefront")
     * @param Request $request
     * @return mixed
     */
    public function rechNomfront (Request $request){
        $data=$request->get('recherche');
        $listmateriel =$this->getDoctrine()
            ->getRepository(Materiel::class)
            ->rechercheParNom($data);
        return $this->render('purchase_front/materiel/index.html.twig',['materiels'=>$listmateriel]);
    }



}

