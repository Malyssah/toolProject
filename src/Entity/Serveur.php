<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServeurRepository")
 */
class Serveur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ServeurUserPeuple", mappedBy="serveur", cascade={"remove"})
     */
    private $serveurUserPeuples;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Troupe", mappedBy="serveur")
     */
    private $troupes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Alliance", mappedBy="serveur")
     */
    private $alliances;

    public function __construct()
    {
        $this->serveurUserPeuples = new ArrayCollection();
        $this->troupes = new ArrayCollection();
        $this->alliances = new ArrayCollection();
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

    /**
     * @return Collection|ServeurUserPeuple[]
     */
    public function getServeurUserPeuples(): Collection
    {
        return $this->serveurUserPeuples;
    }

    public function addServeurUserPeuple(ServeurUserPeuple $serveurUserPeuple): self
    {
        if (!$this->serveurUserPeuples->contains($serveurUserPeuple)) {
            $this->serveurUserPeuples[] = $serveurUserPeuple;
            $serveurUserPeuple->setServeur($this);
        }

        return $this;
    }

    public function removeServeurUserPeuple(ServeurUserPeuple $serveurUserPeuple): self
    {
        if ($this->serveurUserPeuples->contains($serveurUserPeuple)) {
            $this->serveurUserPeuples->removeElement($serveurUserPeuple);
            // set the owning side to null (unless already changed)
            if ($serveurUserPeuple->getServeur() === $this) {
                $serveurUserPeuple->setServeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Troupe[]
     */
    public function getTroupes(): Collection
    {
        return $this->troupes;
    }

    public function addTroupe(Troupe $troupe): self
    {
        if (!$this->troupes->contains($troupe)) {
            $this->troupes[] = $troupe;
            $troupe->setServeur($this);
        }

        return $this;
    }

    public function removeTroupe(Troupe $troupe): self
    {
        if ($this->troupes->contains($troupe)) {
            $this->troupes->removeElement($troupe);
            // set the owning side to null (unless already changed)
            if ($troupe->getServeur() === $this) {
                $troupe->setServeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Alliance[]
     */
    public function getAlliances(): Collection
    {
        return $this->alliances;
    }

    public function addAlliance(Alliance $alliance): self
    {
        if (!$this->alliances->contains($alliance)) {
            $this->alliances[] = $alliance;
            $alliance->setServeur($this);
        }

        return $this;
    }

    public function removeAlliance(Alliance $alliance): self
    {
        if ($this->alliances->contains($alliance)) {
            $this->alliances->removeElement($alliance);
            // set the owning side to null (unless already changed)
            if ($alliance->getServeur() === $this) {
                $alliance->setServeur(null);
            }
        }

        return $this;
    }

	public function __toString() {
		return $this->name;
	}
}
