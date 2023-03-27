<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StateRepository::class)]
class State
{
    private const wordingState = ['created', 'reg_open', 'reg_closed', 'in_progress', 'done', 'canceled'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Assert\Choice(choices: State::wordingState, message: 'select a valid state')]
    private ?string $wording = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'states')]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $hangouts = null;

    #[ORM\OneToMany(mappedBy: 'hangouts', targetEntity: self::class)]
    private Collection $states;

    public function __construct()
    {
        $this->states = new ArrayCollection();
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

    public function getHangouts(): ?self
    {
        return $this->hangouts;
    }

    public function setHangouts(?self $hangouts): self
    {
        $this->hangouts = $hangouts;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(self $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states->add($state);
            $state->setHangouts($this);
        }

        return $this;
    }

    public function removeState(self $state): self
    {
        if ($this->states->removeElement($state)) {
            // set the owning side to null (unless already changed)
            if ($state->getHangouts() === $this) {
                $state->setHangouts(null);
            }
        }

        return $this;
    }
}
