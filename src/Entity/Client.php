<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity=Materiel::class, mappedBy="idclient")
     */
    private $idmateriels;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="idclient")
     */
    private $comments;

    public function __construct()
    {
        $this->idmateriels = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection|Materiel[]
     */
    public function getIdmateriels(): Collection
    {
        return $this->idmateriels;
    }

    public function addIdmateriel(Materiel $idmateriel): self
    {
        if (!$this->idmateriels->contains($idmateriel)) {
            $this->idmateriels[] = $idmateriel;
            $idmateriel->setIdclient($this);
        }

        return $this;
    }

    public function removeIdmateriel(Materiel $idmateriel): self
    {
        if ($this->idmateriels->removeElement($idmateriel)) {
            // set the owning side to null (unless already changed)
            if ($idmateriel->getIdclient() === $this) {
                $idmateriel->setIdclient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdclient($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIdclient() === $this) {
                $comment->setIdclient(null);
            }
        }

        return $this;
    }
}
