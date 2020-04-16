<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $email;

	/**
	 * @ORM\Column(type="json")
	 * @IsGranted("ROLE_ADMIN")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @param mixed
	 * @Assert\Length(min=6, minMessage="Le mot de passe doit faire au moins 6 caractères", max=30, maxMessage="Le mot de passe ne doit pas faire plus de 30 caractères")
	 *
	 */
	private $plainPassword;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $username;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Troupe", mappedBy="users")
	 */
	private $troupes;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LostPassword", mappedBy="user")
	 */
	private $lostPasswords;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Serveur", inversedBy="users")
	 * @ORM\JoinColumn(name="serveur_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	private $serveur;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $peuple;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Alliance", inversedBy="users")
     */
    private $alliance;

	public function __construct()
         	{
         		$this->troupes = new ArrayCollection();
         		$this->lostPasswords = new ArrayCollection();
         	}

	public function getId(): ?int
         	{
         		return $this->id;
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
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string
         	{
         		return (string)$this->username;
         	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
         	{
         		$roles = $this->roles;
         		// guarantee every user at least has ROLE_USER
         		$roles[] = 'ROLE_USER';
         
         		return array_unique($roles);
         	}

	public function setRoles(array $roles): self
         	{
         		$this->roles = $roles;
         
         		return $this;
         	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
         	{
         		return (string)$this->password;
         	}

	public function setPassword(string $password): self
         	{
         		$this->password = $password;
         
         		return $this;
         	}

	public function getPlainPassword(): ?string
         	{
         		return $this->plainPassword;
         	}

	public function setPlainPassword(string $plainPassword): self
         	{
         		$this->plainPassword = $plainPassword;
         		return $this;
         	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
         	{
         		// not needed when using the "bcrypt" algorithm in security.yaml
         	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
         	{
         		// If you store any temporary, sensitive data on the user, clear it here
         		$this->plainPassword = null;
         	}

	public function setUsername(?string $username): self
         	{
         		$this->username = $username;
         
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
         			$troupe->setUsers($this);
         		}
         
         		return $this;
         	}

	public function removeTroupe(Troupe $troupe): self
         	{
         		if ($this->troupes->contains($troupe)) {
         			$this->troupes->removeElement($troupe);
         			// set the owning side to null (unless already changed)
         			if ($troupe->getUsers() === $this) {
         				$troupe->setUsers(null);
         			}
         		}
         
         		return $this;
         	}

	/**
	 * @return Collection|LostPassword[]
	 */
	public function getLostPasswords(): Collection
         	{
         		return $this->lostPasswords;
         	}

	public function addLostPassword(LostPassword $lostPassword): self
         	{
         		if (!$this->lostPasswords->contains($lostPassword)) {
         			$this->lostPasswords[] = $lostPassword;
         			$lostPassword->setUser($this);
         		}
         
         		return $this;
         	}

	public function removeLostPassword(LostPassword $lostPassword): self
         	{
         		if ($this->lostPasswords->contains($lostPassword)) {
         			$this->lostPasswords->removeElement($lostPassword);
         			// set the owning side to null (unless already changed)
         			if ($lostPassword->getUser() === $this) {
         				$lostPassword->setUser(null);
         			}
         		}
         
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

	public function getPeuple(): ?string
         	{
         		return $this->peuple;
         	}

	public function setPeuple(?string $peuple): self
         	{
         		$this->peuple = $peuple;
         
         		return $this;
         	}

    public function getAlliance(): ?Alliance
    {
        return $this->alliance;
    }

    public function setAlliance(?Alliance $alliance): self
    {
        $this->alliance = $alliance;

        return $this;
    }
}
