<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\OneToMany(targetEntity="InscriptionEvent", mappedBy="user")
     */
    private $inscriptionEvent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    public function __construct()
    {
        $this->inscriptionEvent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getInscriptionEvent()
    {
        return $this->inscriptionEvent;
    }

    /**
     * @param mixed $inscriptionEvent
     */
    public function setInscriptionEvent($inscriptionEvent)
    {
        $this->inscriptionEvent = $inscriptionEvent;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function addInscriptionEvent(InscriptionEvent $inscriptionEvent): self
    {
        if (!$this->inscriptionEvent->contains($inscriptionEvent)) {
            $this->inscriptionEvent[] = $inscriptionEvent;
            $inscriptionEvent->setUser($this);
        }

        return $this;
    }

    public function removeInscriptionEvent(InscriptionEvent $inscriptionEvent): self
    {
        if ($this->inscriptionEvent->removeElement($inscriptionEvent)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionEvent->getUser() === $this) {
                $inscriptionEvent->setUser(null);
            }
        }

        return $this;
    }
}
