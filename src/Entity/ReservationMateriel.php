<?php

namespace App\Entity;

use App\Repository\ReservationMaterielRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationMaterielRepository::class)
 */
class ReservationMateriel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     */
    private $DateReservation;
    /**
     * @ORM\ManyToOne(targetEntity="Materiel")
     * @ORM\JoinColumn(name="materiel_id", referencedColumnName="id")
     */
    private $Materiel;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->DateReservation;
    }

    public function setDateReservation(\DateTimeInterface $DateReservation): self
    {
        $this->DateReservation = $DateReservation;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getMateriel()
    {
        return $this->Materiel;
    }

    /**
     * @param mixed $Materiel
     */
    public function setMateriel($Materiel)
    {
        $this->Materiel = $Materiel;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }

}
