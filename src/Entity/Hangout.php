<?php

namespace App\Entity;

use App\Repository\HangoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HangoutRepository::class)]
class Hangout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTimestamp = null;

    #[ORM\Column]
    private ?\DateInterval $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $lastRegisterDate = null;

    #[ORM\Column]
    private ?int $maxSlots = null;

    #[ORM\Column(length: 3000)]
    private ?string $informations = null;

    #[ORM\ManyToOne(inversedBy: 'created')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\ManyToOne(inversedBy: 'hangout')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $schools = null;

    #[ORM\ManyToOne(inversedBy: 'hangouts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $places = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'user')]
    private Collection $user;




    public function __construct()
    {

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

    public function getStartTimestamp(): ?\DateTimeInterface
    {
        return $this->startTimestamp;
    }

    public function setStartTimestamp(\DateTimeInterface $startTimestamp): self
    {
        $this->startTimestamp = $startTimestamp;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLastRegisterDate(): ?\DateTimeInterface
    {
        return $this->lastRegisterDate;
    }

    public function setLastRegisterDate(\DateTimeInterface $lastRegisterDate): self
    {
        $this->lastRegisterDate = $lastRegisterDate;

        return $this;
    }

    public function getMaxSlots(): ?int
    {
        return $this->maxSlots;
    }

    public function setMaxSlots(int $maxSlots): self
    {
        $this->maxSlots = $maxSlots;

        return $this;
    }

    public function getInformations(): ?string
    {
        return $this->informations;
    }

    public function setInformations(string $informations): self
    {
        $this->informations = $informations;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getSchools(): ?School
    {
        return $this->schools;
    }

    public function setSchools(?School $schools): self
    {
        $this->schools = $schools;

        return $this;
    }

    public function getPlaces(): ?Place
    {
        return $this->places;
    }

    public function setPlaces(?Place $places): self
    {
        $this->places = $places;

        return $this;
    }

    /**
     * @return Collection<int, Hangout>
     */
    public function getUsers(): Collection
    {
        return $this->user;
    }

    public function addCreator(Hangout $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->sethangout($this);
        }

        return $this;
    }

    public function removeCreator(Hangout $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getHangout() === $this) {
                $user->setHangout(null);
            }
        }

        return $this;
    }

}
