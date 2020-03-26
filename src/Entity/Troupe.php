<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TroupeRepository")
 */
class Troupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imperian;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $caesaris;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $belier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $catapulte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phalange;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $druide;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gourdin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $teuton;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="troupes", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Serveur", inversedBy="troupes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $serveur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImperian(): ?int
    {
        return $this->imperian;
    }

    public function setImperian(?int $imperian): self
    {
        $this->imperian = $imperian;

        return $this;
    }

    public function getCaesaris(): ?int
    {
        return $this->caesaris;
    }

    public function setCaesaris(?int $caesaris): self
    {
        $this->caesaris = $caesaris;

        return $this;
    }

    public function getBelier(): ?int
    {
        return $this->belier;
    }

    public function setBelier(?int $belier): self
    {
        $this->belier = $belier;

        return $this;
    }

    public function getCatapulte(): ?int
    {
        return $this->catapulte;
    }

    public function setCatapulte(?int $catapulte): self
    {
        $this->catapulte = $catapulte;

        return $this;
    }

    public function getPhalange(): ?int
    {
        return $this->phalange;
    }

    public function setPhalange(?int $phalange): self
    {
        $this->phalange = $phalange;

        return $this;
    }

    public function getDruide(): ?int
    {
        return $this->druide;
    }

    public function setDruide(?int $druide): self
    {
        $this->druide = $druide;

        return $this;
    }

    public function getGourdin(): ?int
    {
        return $this->gourdin;
    }

    public function setGourdin(?int $gourdin): self
    {
        $this->gourdin = $gourdin;

        return $this;
    }

    public function getTeuton(): ?int
    {
        return $this->teuton;
    }

    public function setTeuton(?int $teuton): self
    {
        $this->teuton = $teuton;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
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
}
