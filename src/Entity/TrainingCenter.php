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

    use AddressTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training"})
     * @Assert\Length(min="3", max="255", groups={"training"})
     */
    private ?string $companyName = null;

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
