<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServeurUserPeupleRepository")
 */
class ServeurUserPeuple
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Serveur", inversedBy="serveurUserPeuples")
     * @ORM\JoinColumn(nullable=false)
     */
    private $serveur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="serveurUserPeuples")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $peuple;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServeur(): ?Serveur
    {
        return $this->serveur;
    }

    public function setServeur(?Serveur $serveur): self
    {
        $this->serveur = $serveur;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPeuple(): ?string
    {
        return $this->peuple;
    }

    public function setPeuple(string $peuple): self
    {
        $this->peuple = $peuple;

        return $this;
    }
}
