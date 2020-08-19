<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GreenSpaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GreenSpaceRepository::class)
 */
class GreenSpace
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=6)
     */
    private ?string $latitude = null;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private ?string $longitude = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity=Prestation::class, mappedBy="greenSpace")
     */
    private Collection $prestations;

    /**
     * @ORM\ManyToOne(targetEntity=TrainingStructure::class, inversedBy="greenSpaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TrainingStructure $trainingStructure = null;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
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
     * @return Collection|Prestation[]
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): self
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations[] = $prestation;
            $prestation->setGreenSpace($this);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): self
    {
        if ($this->prestations->contains($prestation)) {
            $this->prestations->removeElement($prestation);
            // set the owning side to null (unless already changed)
            if ($prestation->getGreenSpace() === $this) {
                $prestation->setGreenSpace(null);
            }
        }

        return $this;
    }

    public function getTrainingStructure(): ?TrainingStructure
    {
        return $this->trainingStructure;
    }

    public function setTrainingStructure(?TrainingStructure $trainingStructure): self
    {
        $this->trainingStructure = $trainingStructure;

        return $this;
    }
}
