<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $approuve;

    /**
     * @return mixed
     */
    public function getApprouve()
    {
        return $this->approuve;
    }

    /**
     * @param mixed $approuve
     */
    public function setApprouve($approuve): void
    {
        $this->approuve = $approuve;
    }
    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $prenom;

    /**

     /**
     * @ORM\Column(name="event", type="string", length=255)
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    public $nbrplace;



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event): void
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getNbrplace()
    {
        return $this->nbrplace;
    }

    /**
     * @param mixed $nbrplace
     */
    public function setNbrplace($nbrplace): void
    {
        $this->nbrplace = $nbrplace;
    }






}
