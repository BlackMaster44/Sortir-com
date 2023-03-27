<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\ManyToMany(targetEntity: Hangout::class, inversedBy: 'users')]
    private Collection $goingTo;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Hangout::class)]
    private Collection $created;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $site = null;

    public function __construct()
    {

        $this->goingTo = new ArrayCollection();
        $this->created = new ArrayCollection();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }




    /**
     * @return Collection<int, Hangout>
     */
    public function getGoingTo(): Collection
    {
        return $this->goingTo;
    }

    public function addGoingTo(Hangout $goingTo): self
    {
        if (!$this->goingTo->contains($goingTo)) {
            $this->goingTo->add($goingTo);
        }

        return $this;
    }

    public function removeGoingTo(Hangout $goingTo): self
    {
        $this->goingTo->removeElement($goingTo);

        return $this;
    }

    /**
     * @return Collection<int, Hangout>
     */
    public function getCreated(): Collection
    {
        return $this->created;
    }

    public function addCreator(Hangout $created): self
    {
        if (!$this->created->contains($created)) {
            $this->created->add($created);
            $created->setCreator($this);
        }

        return $this;
    }

    public function removeCreator(Hangout $created): self
    {
        if ($this->created->removeElement($created)) {
            // set the owning side to null (unless already changed)
            if ($created->getCreator() === $this) {
                $created->setCreator(null);
            }
        }

        return $this;
    }

    public function getSite(): ?School
    {
        return $this->site;
    }

    public function setSite(?School $site): self
    {
        $this->site = $site;

        return $this;
    }
}
