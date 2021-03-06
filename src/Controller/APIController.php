<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Material;
use App\Entity\MaterialReservation;
use App\Entity\DateSearch;
use App\Entity\Reservation;

/**
 * @Route("/api", name="api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/login", name="apiLogin" ,methods={"GET","POST"})
     */
    public function verifierUser(Request $request,UserPasswordEncoderInterface  $encoder,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setEmail($request->get('email'));
        //$hash= $encoder->encodePassword($user,$request->get('password'));
        $user->setPassword($request->get('password'));
        $User = $em->getRepository(User::class)->findOneBy(
            ['email' => $user->getEmail()]);
        if ($User == null) {
            return new JsonResponse("email no correct", 400);
        }
        if (password_verify($user->getPassword(), $User->getPassword())) {
            $jsonContent = $normalizer->normalize($User, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }]);
            return new Response(json_encode($jsonContent));
        } else {
            return new JsonResponse("pass inco", 400);
        }
    }
    /**
    * @Route("/register", name="apiregister")
    */
    public function Registration(Request $request ,NormalizerInterface $normalizer ,EntityManagerInterface  $entityManager ,UserPasswordEncoderInterface  $encoder, \Swift_Mailer $mailer)
    {
        $user =new User();

        $user->setFirstName($request->get('firstName'));
        $user->setLastName($request->get('lastName'));
        $user->setEmail($request->get('email'));
        $hash= $encoder->encodePassword($user,$request->get('password'));
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

        $jsonContent= $normalizer->normalize($user,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }

        ]);

        return new Response(json_encode($jsonContent));


    }
    /**
     * @Route("/ListeMaterial", name="apiListeMaterial")
     */
    public function FrontRenting(NormalizerInterface $normalizer,Request $request): Response

    {
        $entityManager = $this->getDoctrine()->getManager();
        $ax = $entityManager->getRepository(MaterialReservation::class)->findAll();
        $l= count($ax)-1;
        $now = new \DateTime();
        for($i=0;$i<=$l;$i++) {
            if ($ax[$i]->getDateEnd() < $now){
                $vx = $entityManager->getRepository(Material::class)->find($ax[$i]->getMaterial())->setAvailability(true);
                $bx = $entityManager->getRepository(Material::class)->find($ax[$i]->getMaterial())->getNbrmatrres();
                $cx = $entityManager->getRepository(Material::class)->find($ax[$i]->getMaterial())->setNbrmatrres($bx-1);
                $entityManager->persist($vx,$cx);
                $entityManager->remove($ax[$i]);
                $entityManager->flush();
            }
        }
        $kx = $entityManager->getRepository(Material::class)->findAll();
        $jsonContent= $normalizer->normalize($kx,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/reservationMaterial/{id}", name="apiMaterialreservation", methods={"GET","POST"})
     */
    public function newMaterial(Request $request,$id,NormalizerInterface $normalizer,\Swift_Mailer $mailer): Response
    {
        $materialReservation = new MaterialReservation();
        $entityManager = $this->getDoctrine()->getManager();
        $abn = $entityManager->getRepository(Material::class)->find($request->get('Material_id'));
        $acn = $entityManager->getRepository(User::class)->find($request->get('user_id'));
        $materialReservation->setUser($acn);
        $materialReservation->setMaterial($abn);
        $dt=new \DateTime($request->get('dateStart'));
        $de=new \DateTime($request->get('dateEnd'));
        $materialReservation->setDateStart($dt);
        $materialReservation->setDateEnd($de);
        $bx = $entityManager->getRepository(Material::class)->find($id)->getnbrmatrres();
        $cx = $entityManager->getRepository(Material::class)->find($id)->getQuantity();
        if($cx>$bx){
            $ax = $entityManager->getRepository(Material::class)->find($id)->setnbrmatrres($bx+1);}
        $zx = $entityManager->getRepository(Material::class)->find($id)->getnbrmatrres();

        if($zx==$cx){
            $dx = $entityManager->getRepository(Material::class)->find($id)->setAvailability(false);
        }
        else{ $dx = $entityManager->getRepository(Material::class)->find($id)->setAvailability(true);
        }
        $entityManager->persist($materialReservation,$ax,$dx);
        $entityManager->flush();

        $message = (new \Swift_Message('R�servation R�ussie '))
            //
            ->setFrom('GoCamp315@gmail.com')
            //
            ->setTo($acn->getEmail())
            //
            ->setBody(
                $this->renderView('email/chaimaMailer.html.twig',[ 'firstName' => $acn->getFirstName()]),
                "text/html"
            );
        //on envoie l'email
        $mailer->send($message);
        $jsonContent= $normalizer->normalize($materialReservation,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/RentingMaterial/{id}", name="Api_material_show_front", methods={"GET"})
     */
    public function show(Material $material,$id,NormalizerInterface $normalizer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Data = $entityManager->getRepository(Material::class)->find($id);

        $jsonContent= $normalizer->normalize($Data,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/ListReservations/{id}", name="api_material_reservation_show_front", methods={"GET"})
     */
    public function ListReservation(NormalizerInterface $normalizer,$id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $Data = $entityManager->getRepository(MaterialReservation::class)->findBy(['user' => $user]);
        $data = array();
        $i = 0;
        foreach ($Data as $d) {
            $data[$i] = array(
                'id' => $d->getId(),
                'dateStart' => $d->getDateStart(),
                'dateEnd' => $d->getDateEnd(),
                'material_id' => $d->getMaterial()->getId(),
                'user_id' => $d->getUser()->getId(),
            );
            $i++;
        }

        $jsonContent = $normalizer->normalize($data, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            return new Response(json_encode($jsonContent));
        }

    /**
     * @Route("/deleteReservationMaterial/{id}", name="api_material_reservation_delete_front", methods={"GET"})
     */
    public function delete(Request $request,NormalizerInterface $normalizer,$id): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
        $materialReservation= $entityManager->getRepository(MaterialReservation::class)->find($id);
            $ax = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->setAvailability(true);
            $bx = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->getNbrmatrres();
            $cx = $entityManager->getRepository(Material::class)->find($materialReservation->getMaterial())->setNbrmatrres($bx-1);

            $entityManager->persist($ax,$cx);
            $entityManager->remove($materialReservation);
            $entityManager->flush();

        $jsonContent = $normalizer->normalize($materialReservation, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/profil_edit", name="api_edit_profil")
     */
    public function editPrfoil(Request $request,NormalizerInterface $normalizer): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($request->get('id'));
        $user->setEmail($request->get('email'));
        $user->setFirstName($request->get('firstName'));
        $user->setLastName($request->get('lastName'));
        $user->setAdress($request->get('adress'));
        $user->setSexe($request->get('sexe'));
        $user->setPhone($request->get('phone'));



        $entityManager->persist($user);
            $entityManager->flush();


        $jsonContent = $normalizer->normalize($user, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/reservation_edit", name="api_reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NormalizerInterface $normalizer): Response
    {             $entityManager = $this->getDoctrine()->getManager();
        $materialReservation = $entityManager->getRepository(MaterialReservation::class)->find($request->get('id'));

        $materialReservation->setdateStart(new \DateTime($request->get('dateStart')));
        $materialReservation->setdateEnd(new \DateTime($request->get('dateEnd')));

        $entityManager->persist($materialReservation);
        $entityManager->flush();

        $jsonContent = $normalizer->normalize($materialReservation, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/rechercheReservations", name="api_material_index", methods={"GET","POST"})
     */
    public function index(NormalizerInterface $normalizer,Request $request): Response

    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find($request->get('id_user'));
//recherche
            $minDate =new \DateTime($request->get('dateStart'));
            $maxDate =new \DateTime($request->get('dateEnd'));
            $reservation = $this->getDoctrine()->getRepository(MaterialReservation::class)->findByDateRange($minDate,$maxDate,$user);

        $data = array();
        $i = 0;
        foreach ($reservation as $d) {
            $data[$i] = array(
                'id' => $d->getId(),
                'dateStart' => $d->getDateStart(),
                'dateEnd' => $d->getDateEnd(),
                'material_id' => $d->getMaterial()->getId(),
                'user_id' => $d->getUser()->getId(),
            );
            $i++;
        }
        $jsonContent = $normalizer->normalize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));
    }


            /**
             * @Route("/listEvent", name="event_front")
             */
            public function ListEvent(NormalizerInterface $normalizer): Response{
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $entityManager->getRepository(Event::class)->findAll();

            $jsonContent= $normalizer->normalize($repository,'json',[
                'circular_reference_handler'=>function($object){
                    return$object->getId();
                }
            ]);
            return new Response(json_encode($jsonContent));


        }
    /**
     * @Route("front/{id}", name="event_show", methods={"GET"})
     */
    public function showR(NormalizerInterface $normalizer,$id): Response
    {

        $repository=$this->getDoctrine()->getRepository(Event::class)->find($id);

        $jsonContent= $normalizer->normalize($repository,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }

        ]);
        return new Response(json_encode($jsonContent));
        // return $this->render('event_front/show.html.twig', [
        //  'event' => $event,
        // ]);

    }
    public function CreaterReservationAction(Request $request, \Swift_Mailer $mailer)
    {

        $a= new Reservation();

        $repository=$this->getDoctrine()->getRepository(Event::class)->find($request->get('id'));
        $a->setNom($request->get('nom'));
        $a->setEvent($repository->getTitle());
        $a->setPrenom($request->get('prenom'));
        $a->setNbrplace($request->get('nbrplace'));
        //$message = (new \Swift_Message('votre reservation est fini'))
          //  ->setFrom('GoCamp315@gmail.com')

          //  ->setTo('br.chames97@gmail.com')
           // ->setBody(
           //     $this->renderView(
              //      'admin/event_management/confirmation_mail.html.twig'
              //  ),
               // 'text/html'
           // );
        //$mailer->send($message);
        $message = (new \Swift_Message('votre reservation est fini'))
            //
            ->setFrom('GoCamp315@gmail.com')
            //
            ->setTo('br.chames97@gmail.com')
            //
            ->setBody(
                $this->renderView('admin/event_management/confirmation_mail.html.twig'),
                "text/html"
            );
        //on envoie l'email
        $mailer->send($message);


        $this->getDoctrine()->getManager()->persist($a);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
    }

    public function AllReservationAction()
    {

        $ar = $this->getDoctrine()->getManager()
            ->getRepository('App:Reservation')
            ->findAll();
        $normalizer = new ObjectNormalizer ();

        $normalizer -> setCircularReferenceHandler ( function ( $ar ) {
            return $ar -> getId ();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($ar , null , [ ObjectNormalizer::ATTRIBUTES => ['id','nom','prenom','approuve','nbrplace','event'=>['id','nom','prenom','approuve','nbrplace','event']]]);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/res/front", name="res_front")
     * @param ReservationRepository $reservationRepository
     * @return Response
     */
    public function indexTT(NormalizerInterface $normalizer): Response{

        $entityManager = $this->getDoctrine()->getManager();

        $repository = $entityManager->getRepository('App:Reservation')->findAll();


        //Json
        $jsonContent= $normalizer->normalize($repository,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }

        ]);
        //return $this->render('comment/index.html.twig', [
        //'comments' => $jsonContent,
        //]);
        return new Response(json_encode($jsonContent));


        // return $this->render('event_front/index.html.twig', [
        // 'events' => $eventRepository->findAll(),]);
    }
    public function DeleteReservationAction(Request $request){

        $id = $request->get('id');
        $em=$this->getDoctrine()->getManager();
        $reservation=$em->getRepository('App:Reservation')->find($id);

        $this->getDoctrine()->getManager()->remove($reservation);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse();
    }

    /**
     * @Route("/sales/confirmer",name="confirmer", methods={"GET"})
     */
    public function confirmer(\Swift_Mailer $mailer,Request $request,NormalizerInterface $normalizer ): Response
    {
        $val =$this->getDoctrine()->getRepository(User::class)->find($request->get('id'));


        // Configure Dompdf according to your needs

        $message = (new \Swift_Message('confirmation achat '))
            //
            ->setFrom('GoCamp315@gmail.com')
            //
            ->setTo($val->getEmail())
            //
            ->setBody(
                $this->renderView('email/mailerDhia.html.twig',[ 'firstName' => $val->getFirstName()]),
                "text/html"
            );
        //on envoie l'email
        $mailer->send($message);

        $jsonContent= $normalizer->normalize($mailer,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }

        ]);
        //return $this->render('comment/index.html.twig', [
        //'comments' => $jsonContent,
        //]);
        return new Response(json_encode($jsonContent));
    }







}
