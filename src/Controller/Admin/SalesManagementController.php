<?php

namespace App\Controller\Admin;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("Admin")
**/

class SalesManagementController extends AbstractController
{
   /**
     * @Route("/sales/management", name="sales_management")
     * @param MaterielRepository $materielRepository
     * @return Response
     */
    public function index(MaterielRepository $materielRepository): Response
    {
        return $this->render('admin/sales_management/materiel/index.html.twig', [
            'materiels' => $materielRepository->findAll(),
        ]);
    }

    /**
     * @Route("/sales/new", name="materiel_management_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['photo']->getData();
            $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
            $uploadedFile->move($this->getParameter('upload_directory'),$filename);
            $materiel->setPhoto($filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('sales_management');
        }

        return $this->render('admin/sales_management/materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/listo", name="listo", methods={"GET"})
     */
    public function listo(MaterielRepository $materielRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('admin/sales_management/materiel/list.html.twig', [
            'materiels' => $materielRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
    /**
     * @Route("/{id}", name="materiel_management_show", methods={"GET"})
     * @param Materiel $materiel
     * @return Response
     */
    public function show(Materiel $materiel): Response
    {
        return $this->render('admin/sales_management/materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materiel_management_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Materiel $materiel
     * @return Response
     */
    public function edit(Request $request, Materiel $materiel): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sales_management');
        }

        return $this->render('admin/sales_management/materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_management_delete", methods={"DELETE"})
     * @param Request $request
     * @param Materiel $materiel
     * @return Response
     */
    public function delete(Request $request, Materiel $materiel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sales_management');
    }
    /**
     * @Route("/recherche/back", name="rechercheback")
     * @param Request $request
     * @return mixed
     */
    public function rechNomback (Request $request){
        $data=$request->get('recherche');
        $listmateriel =$this->getDoctrine()
            ->getRepository(Materiel::class)
            ->rechercheParNom($data);
        return $this->render('admin/sales_management/materiel/index.html.twig',['materiels'=>$listmateriel]);
    }
}

