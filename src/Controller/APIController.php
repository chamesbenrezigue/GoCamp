<?php

namespace App\Controller;

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
    public function new(Request $request,$id,NormalizerInterface $normalizer): Response
    {
        $materialReservation = new MaterialReservation();
        $entityManager = $this->getDoctrine()->getManager();
        $abn = $entityManager->getRepository(Material::class)->find($request->get('Material_id'));
        $acn = $entityManager->getRepository(User::class)->find($request->get('user_id'));
        $materialReservation->setUser($acn);
        $materialReservation->setMaterial($abn);
        $dt=new \DateTime($request->get('dateStart'));
        $materialReservation->setDateStart($dt);
        $materialReservation->setDateEnd(new \DateTime($request->get('dateEnd')));
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
        $jsonContent= $normalizer->normalize($materialReservation,'json',[
            'circular_reference_handler'=>function($object){
                return$object->getId();
            }
        ]);
        return new Response(json_encode($jsonContent));
    }
}
