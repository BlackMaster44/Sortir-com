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

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\ManyToMany(targetEntity: Hangout::class, inversedBy: 'participants')]
    private Collection $goingTo;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Hangout::class)]
    private Collection $createdHangouts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $administrator = null;


    public function __construct()
    {
        $this->administrator = false;
        $this->active = true;
        $this->goingTo = new ArrayCollection();
        $this->createdHangouts = new ArrayCollection();
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
        return $this->createdHangouts;
    }

    public function addCreated(Hangout $created): self
    {
        if (!$this->createdHangouts->contains($created)) {
            $this->createdHangouts->add($created);
            $created->setCreator($this);
        }

        return $this;
    }

    public function removeCreatec(Hangout $created): self
    {
        if ($this->createdHangouts->removeElement($created)) {
            // set the owning side to null (unless already changed)
            if ($created->getCreator() === $this) {
                $created->setCreator(null);
            }
        }

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, Hangout>
     */
    public function getCreatedHangouts(): Collection
    {
        return $this->createdHangouts;
    }

    public function addCreatedHangout(Hangout $createdHangout): self
    {
        if (!$this->createdHangouts->contains($createdHangout)) {
            $this->createdHangouts->add($createdHangout);
            $createdHangout->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedHangout(Hangout $createdHangout): self
    {
        if ($this->createdHangouts->removeElement($createdHangout)) {
            // set the owning side to null (unless already changed)
            if ($createdHangout->getCreator() === $this) {
                $createdHangout->setCreator(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
    }
}
