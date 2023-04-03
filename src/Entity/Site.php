<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: User::class)]
    private Collection $users;
    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Hangout::class)]
    private Collection $hostedHangouts;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->hangout = new ArrayCollection();
        $this->hostedHangouts = new ArrayCollection();
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
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSite() === $this) {
                $user->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hangout>
     */
    public function getHangout(): Collection
    {
        return $this->hangout;
    }

    public function addHangout(Hangout $hangout): self
    {
        if (!$this->hangout->contains($hangout)) {
            $this->hangout->add($hangout);
            $hangout->setSchool($this);
        }

        return $this;
    }

    public function removeHangout(Hangout $hangout): self
    {
        if ($this->hangout->removeElement($hangout)) {
            // set the owning side to null (unless already changed)
            if ($hangout->getSchool() === $this) {
                $hangout->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hangout>
     */
    public function getHostedHangouts(): Collection
    {
        return $this->hostedHangouts;
    }

    public function addHostedHangout(Hangout $hostedHangout): self
    {
        if (!$this->hostedHangouts->contains($hostedHangout)) {
            $this->hostedHangouts->add($hostedHangout);
            $hostedHangout->setSite($this);
        }

        return $this;
    }

    public function removeHostedHangout(Hangout $hostedHangout): self
    {
        if ($this->hostedHangouts->removeElement($hostedHangout)) {
            // set the owning side to null (unless already changed)
            if ($hostedHangout->getSite() === $this) {
                $hostedHangout->setSite(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
}

}
