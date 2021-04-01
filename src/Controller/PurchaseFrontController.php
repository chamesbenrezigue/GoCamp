<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Materiel;
use App\Form\CommentType;
use App\Repository\ClientRepository;
use App\Repository\CommentRepository;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
class PurchaseFrontController extends AbstractController
{
    /**
     * @Route("/purchase/front", name="purchase_front")
     * @param MaterielRepository $materielRepository
     * @return Response
     */

    public function index(MaterielRepository $materielRepository,ClientRepository $clientRepository): Response
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
        $val = $clientRepository->find(1);
        $session= new Session();
        $session->set('id',$val->getId());
        $session->set('mail',$val->getMail());
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
     * @param ClientRepository $repository
     * @return Response
     */

    public function show (Request $request,Materiel $materiel,CommentRepository $commentRepository,$id,ClientRepository $repository): Response
    {
        $comment = new Comment();
        $form1 = $this->createForm(CommentType::class,$comment);
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setIdmateriel($materiel);
            $value=$repository->find($this->get('session')->get('id'));
            $comment->setIdclient($value);
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
    public function confirmer(\Swift_Mailer $mailer,$id,ClientRepository $clientRepository): Response
    {

        $val = $clientRepository->find($id);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('purchase_front/materiel/listP.html.twig', [
            'clients' => $val,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('upload_directory');
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf';

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Send some text response

        $message = (new \Swift_Message('Confirmation for offer'))
            ->setFrom('noreplay.espritwork@gmail.com')
            ->setTo($val->getMail())
            ->setBody(
                $this->renderView(
                    'purchase_front/materiel/confirm.html.twig'
                    , [
                    'clients' => $val,
                ]),
                'text/html'
            )
            ->attach(\Swift_Attachment::fromPath($pdfFilepath))
        ;
        $mailer->send($message);
        return $this->redirectToRoute('purchase_front');
    }
}
