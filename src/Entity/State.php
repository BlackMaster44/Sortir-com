<?php

namespace App\Entity;

use App\Repository\StateRepository;
use App\TypeConstraints\StateConstraints;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StateRepository::class)]
class State
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Assert\Choice(choices: StateConstraints::wordingState, message: 'select a valid state')]
    private ?string $wording = null;

    #[ORM\OneToMany(mappedBy: 'state', targetEntity: Hangout::class)]
    private Collection $hangouts;

    public function __construct()
    {
        $this->hangouts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * @return Collection<int, Hangout>
     */
    public function getHangouts(): Collection
    {
        return $this->hangouts;
    }

    public function addHangout(Hangout $hangout): self
    {
        if (!$this->hangouts->contains($hangout)) {
            $this->hangouts->add($hangout);
            $hangout->setState($this);
        }

        return $this;
    }

    public function removeHangout(Hangout $hangout): self
    {
        if ($this->hangouts->removeElement($hangout)) {
            // set the owning side to null (unless already changed)
            if ($hangout->getState() === $this) {
                $hangout->setState(null);
            }
        }

        return $this;
    }
}
