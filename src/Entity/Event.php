<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;
    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="event")
     *
     */
    private $comment;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InscriptionEvent", mappedBy="event")
     */
    private $inscriptionEvent;

    /**
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\Column(name="type",type="string", length=255)
     */
    private $type;
    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix): void
    {
        $this->prix = $prix;
    }


    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */


    private $image;

    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->Reservation;
    }

    /**
     * @param mixed $Reservation
     */
    public function setReservation($Reservation): void
    {
        $this->Reservation = $Reservation;
    }

    /**
     *
     * @ORM\Column(name="resevation", type="string", length=255,nullable=true)
     */
    private $Reservation;


    public function __construct()
    {
        $this->setStart(new \DateTime());
        $this->setEnd(new \DateTime());
        $this->comment = new ArrayCollection();
        $this->inscriptionEvent = new ArrayCollection();


    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

        return $this;
    }

    public function addInscriptionEvent(InscriptionEvent $inscriptionEvent): self
    {
        if (!$this->inscriptionEvent->contains($inscriptionEvent)) {
            $this->inscriptionEvent[] = $inscriptionEvent;
            $inscriptionEvent->setEvent($this);
        }

        return $this;
    }

    public function removeInscriptionEvent(InscriptionEvent $inscriptionEvent): self
    {
        if ($this->inscriptionEvent->removeElement($inscriptionEvent)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionEvent->getEvent() === $this) {
                $inscriptionEvent->setEvent(null);
            }
        }

        return $this;
    }



}
