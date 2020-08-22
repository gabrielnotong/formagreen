<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TrainingCenter extends User
{
    const QRCODE_CONTENT = "Name: %s\nEmail: %s\nMember: from %s to %s\nAddress: %s\nCountry: %s\nCity: %s\nPhone number: %s";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city = null;

    /**
     * @ORM\ManyToOne(targetEntity=CenterType::class, inversedBy="trainingStructures")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CenterType $type = null;

    /**
     * @ORM\OneToMany(targetEntity=GreenSpace::class, mappedBy="trainingStructure", orphanRemoval=true)
     */
    private Collection $greenSpaces;

    public function __construct()
    {
        parent::__construct();
        $this->greenSpaces = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getType(): ?CenterType
    {
        return $this->type;
    }

    public function setType(?CenterType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|GreenSpace[]
     */
    public function getGreenSpaces(): Collection
    {
        return $this->greenSpaces;
    }

    public function addGreenSpace(GreenSpace $greenSpace): self
    {
        if (!$this->greenSpaces->contains($greenSpace)) {
            $this->greenSpaces[] = $greenSpace;
            $greenSpace->setTrainingStructure($this);
        }

        return $this;
    }

    public function removeGreenSpace(GreenSpace $greenSpace): self
    {
        if ($this->greenSpaces->contains($greenSpace)) {
            $this->greenSpaces->removeElement($greenSpace);
            // set the owning side to null (unless already changed)
            if ($greenSpace->getTrainingStructure() === $this) {
                $greenSpace->setTrainingStructure(null);
            }
        }

        return $this;
    }
}
