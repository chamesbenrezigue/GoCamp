<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Sujet")
     * @ORM\JoinColumn(name="sujet_id", referencedColumnName="id")
     */
    private $Sujet;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 120,
     *      minMessage = "Your  comment must be at least {{ limit }} characters long",
     *      maxMessage = "Your  comment cannot be longer than {{ limit }} characters"
     * )
     */

    private $contenu;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
{
    return $this->id;
}

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return commentaire
     */
    public function setDate($date)
{
    $this->date = $date;

    return $this;
}

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
{
    return $this->date;
}

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return commentaire
     */
    public function setContenu($contenu)
{
    $this->contenu = $contenu;

    return $this;
}

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
{
    return $this->contenu;
}

    /**
     * @return mixed
     */
    public function getComments()
{
    return $this->comments;
}

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
{
    $this->comments = $comments;
}

    /**
     * @return mixed
     */
    public function getSujet()
{
    return $this->Sujet;
}

    /**
     * @param mixed $Sujet
     */
    public function setSujet($Sujet)
{
    $this->Sujet = $Sujet;
}

    /**
     * @return mixed
     */
    public function getUser()
{
    return $this->user;
}

    /**
     * @param mixed $user
     */
    public function setUser($user)
{
    $this->user = $user;
}



}