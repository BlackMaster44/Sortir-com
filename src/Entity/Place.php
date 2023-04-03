<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]

#[ApiResource( operations: [
    new Get(),
],
    normalizationContext: ["groups"=>["place:read"],"enable_max_depth"=>"2"],
    denormalizationContext: ["groups"=>["place:write"]],

)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['place:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['place:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['place:read'])]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['place:read'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['place:read'])]
    private ?string $longitude = null;

    #[ORM\ManyToOne(inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['place:read'])]
    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'place', targetEntity: Hangout::class)]

    private Collection $hangouts;

    public function __construct()
    {
        $this->hangouts = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

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
            $hangout->setPlace($this);
        }

        return $this;
    }

    public function removeHangout(Hangout $hangout): self
    {
        if ($this->hangouts->removeElement($hangout)) {
            // set the owning side to null (unless already changed)
            if ($hangout->getPlace() === $this) {
                $hangout->setPlace(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
