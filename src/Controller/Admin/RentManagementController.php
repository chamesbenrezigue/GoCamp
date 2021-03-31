<?php

namespace App\Controller\Admin;

use App\Entity\DateSearch;
use App\Entity\MaterialReservation;
use App\Entity\Material;
use App\Form\DateSearchType;
use App\Form\MaterialReservationType;
use App\Form\MaterialType;
use App\Repository\MaterialReservationRepository;
use App\Repository\MaterielRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/admin")
 */
class RentManagementController extends AbstractController
{
    /**
     * @Route("/material", name="material_index", methods={"GET","POST"})
     */
    public function index(MaterielRepository $materielRepository,MaterialReservationRepository $materialReservationRepository,Request $request,PaginatorInterface $paginator,UserRepository $userRepository): Response
    {
//recherche
        $dateSearch = new DateSearch();
        $form = $this->createForm(DateSearchType::class,$dateSearch);
        $form->handleRequest($request);
        $reservation=[];
        if($form->isSubmitted() && $form->isValid()){
            $minDate =$dateSearch->getDateS();
            $maxDate =$dateSearch->getDateE();
            $reservation = $this->getDoctrine()->getRepository(MaterialReservation::class)->findByDateRange($minDate,$maxDate);
        }
        //pagination
        else{
        $MR = $this->getDoctrine()->getRepository(MaterialReservation::class)->findAll();
        $materialReservationRepository = $paginator->paginate(
            $MR,
            $request->query->getInt('page', 1),
            3
        );}
        return $this->render('material/index.html.twig', [
            'materials' => $materielRepository->findAll(),
            'material_reservations' => $materialReservationRepository,
            'users' => $userRepository->findAll(),
            'form'=>$form->createView(),
            'reservation'=>$reservation

        ]);
    }
    /**
     * @Route("/list_material", name="material_list", methods={"GET"})
     */
    public function listMaterial(MaterielRepository $materielRepository): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('material/list_materiel.html.twig', [
            'materials' => $materielRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

    }
    /**
     * @Route("/list_material_reserver", name="material_reserver_list", methods={"GET"})
     */
    public function listMaterialReserver(MaterialReservationRepository $materialReservationRepository,MaterielRepository $materielRepository,UserRepository $userRepository): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('material/list_materiel_reserver.html.twig', [
            'material_reservations' => $materialReservationRepository->findAll(),
            'materials' => $materielRepository->findAll(),
            'users' => $userRepository->findAll(),

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

    }


    /**
     * @Route("/new", name="material_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $material->setNbrmatrres(0);
            $entityManager->persist($material);
            $entityManager->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="material_show", methods={"GET"})
     */
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', [
            'material' => $material,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="material_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Material $material): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="material_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Material $material): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($material);
            $entityManager->flush();
        }

        return $this->redirectToRoute('material_index');
    }
    /**
     * @Route("/reservation/{id}/edit", name="material_reservation_edit_back", methods={"GET","POST"})
     */
    public function editReservation(Request $request, MaterialReservation $materialReservation): Response
    {
        $form = $this->createForm(MaterialReservationType::class, $materialReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('admin/Rent_Management/edit.html.twig', [
            'material_reservation' => $materialReservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reservation/{id}", name="material_reservation_show_back", methods={"GET"})
     */
    public function showReservation(MaterialReservation $materialReservation): Response
    {
        return $this->render('admin/Rent_Management/show.html.twig', [
            'material_reservation' => $materialReservation,
        ]);
    }

    /**
     * @Route("/reservation/{id}", name="material_reservation_delete_back", methods={"DELETE"})
     */
    public function deleteReservation(Request $request, MaterialReservation $materialReservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materialReservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $ax = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->setAvailability(true);
            $bx = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->getNbrmatrres();
            $cx = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->setNbrmatrres($bx-1);
            $entityManager->persist($ax,$cx);
            $entityManager->remove($materialReservation);
            $entityManager->flush();

        }

        return $this->redirectToRoute('material_index');
    }


    
}

