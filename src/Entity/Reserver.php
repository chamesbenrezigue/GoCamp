<?php

namespace App\Entity;

use App\Repository\ReserverRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReserverRepository::class)
 */
class Reserver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iduser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idevent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nbrplace;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?string
    {
        return $this->iduser;
    }

    public function setIduser(string $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdevent(): ?string
    {
        return $this->idevent;
    }

    public function setIdevent(string $idevent): self
    {
        $this->idevent = $idevent;

        return $this;
    }

    public function getNbrplace(): ?string
    {
        return $this->nbrplace;
    }

    public function setNbrplace(string $nbrplace): self
    {
        $this->nbrplace = $nbrplace;

        return $this;
    }
}
