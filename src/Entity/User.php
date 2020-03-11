<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
	 * @ORM\ManyToMany(targetEntity="App\Entity\Alliance", mappedBy="user")
	 */
	private $alliance;

	public function __construct()
	{
		$this->alliance = new ArrayCollection();
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
	 * @return Collection|Alliance[]
	 */
	public function getAlliance(): Collection
	{
		return $this->alliance;
	}

	public function addAlliance(Alliance $alliance): self
	{
		if (!$this->alliance->contains($alliance)) {
			$this->alliance[] = $alliance;
			$alliance->addUser($this);
		}

		return $this;
	}

	public function removeAlliance(Alliance $alliance): self
	{
		if ($this->alliance->contains($alliance)) {
			$this->alliance->removeElement($alliance);
			$alliance->removeUser($this);
		}

		return $this;
	}
}
