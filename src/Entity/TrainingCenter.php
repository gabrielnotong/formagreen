<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class TrainingCenter extends User
{
    const QRCODE_CONTENT = "Name: %s\nEmail: %s\nMember: from %s to %s\nAddress: %s\nPhone number: %s";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Length(min="3", max="255", groups={"training"})
     */
    private ?string $companyName = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Range(min="1", groups={"training"})
     */
    private ?int $streetNumber = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Length(min="3", max="255", groups={"training"})
     */
    private ?string $streetName = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Length(min="3", max="255", groups={"training"})
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Length(min="3", max="255", groups={"training"})
     */
    private ?string $city = null;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Length(min="5", max="15", groups={"training"})
     */
    private ?string $zipCode = null;

    /**
     * @ORM\ManyToOne(targetEntity=CenterType::class, inversedBy="trainingCenters")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="This value should not be blank. You must create a Center Type first", groups={"training"})
     */
    private ?CenterType $centerType = null;

    /**
     * @ORM\OneToMany(targetEntity=GreenSpace::class, mappedBy="trainingCenter", orphanRemoval=true)
     */
    private Collection $greenSpaces;

    public function __construct()
    {
        parent::__construct();
        $this->greenSpaces = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getCompanyName();
    }

    public function getType(): string
    {
        return parent::TYPE_TRAINING_CENTER;
    }

    public function hasCompany(): ?bool
    {
        return $this->companyName != null;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(?string $streetName): self
    {
        $this->streetName = $streetName;

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

    public function getAddress(): string
    {
        return $this->streetNumber . ' ' . $this->streetName . ', ' . $this->zipCode . ' ' . $this->city . '(' . $this->country . ')';
    }

    public function getCenterType(): ?CenterType
    {
        return $this->centerType;
    }

    public function setCenterType(?CenterType $type): self
    {
        $this->centerType = $type;

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
            $greenSpace->setTrainingCenter($this);
        }

        return $this;
    }

    public function removeGreenSpace(GreenSpace $greenSpace): self
    {
        if ($this->greenSpaces->contains($greenSpace)) {
            $this->greenSpaces->removeElement($greenSpace);
            // set the owning side to null (unless already changed)
            if ($greenSpace->getTrainingcenter() === $this) {
                $greenSpace->setTrainingCenter(null);
            }
        }

        return $this;
    }
}
