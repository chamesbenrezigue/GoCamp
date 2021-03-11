<?php

namespace App\Controller;


use App\Entity\Event;
use App\Entity\Reserver;
use App\Form\EventType;
use App\Form\ReserverType;
use App\Repository\ReserverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @Route("/reserver")
 */
class ReserverController extends AbstractController
{
    /**
     * @Route("/", name="reserver_index", methods={"GET"})
     */
    public function index(ReserverRepository $reserverRepository): Response
    {

        return $this->render('reserver/index.html.twig', [
            'reservers' => $reserverRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="reserver_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new (Request $request): Response
    {
        $reserver = new reserver();
        $form = $this->createForm(ReserverType::class, $reserver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reserver);
            $entityManager->flush();

            return $this->redirectToRoute('reserver');
        }

        return $this->render('reserver/new.html.twig', [
            'event' => $reserver,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="reserver_show", methods={"GET"})
     */
    public function show(Reserver $reserver): Response
    {
        return $this->render('reserver/show.html.twig', [
            'reserver' => $reserver,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reserver_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reserver $reserver): Response
    {
        $form = $this->createForm(ReserverType::class, $reserver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reserver_index');
        }

        return $this->render('reserver/edit.html.twig', [
            'reserver' => $reserver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reserver_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reserver $reserver): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserver->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reserver);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reserver_index');
    }

    /**
     * @Route("/listreserver/{id}", name="afficherReserver")
     */
    public function listReservationByEvent($id){
        $event=$this->getDoctrine()->getRepository(Event::class)->find($id);
        $listreserver=$this->getDoctrine()->getRepository(Reserver::class)->findBy(array('idevent'=>$event));
        return $this->render('event/listreserver.html.twig',array('reservations'=>$listreserver));

    }

}
