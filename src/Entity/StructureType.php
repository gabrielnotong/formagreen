<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StructureTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StructureTypeRepository::class)
 */
class StructureType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description = null;

    /**
     * @ORM\OneToMany(targetEntity=TrainingStructure::class, mappedBy="type")
     */
    private Collection $trainingStructures;

    public function __construct()
    {
        $this->trainingStructures = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|TrainingStructure[]
     */
    public function getTrainingStructures(): Collection
    {
        return $this->trainingStructures;
    }

    public function addTrainingStructure(TrainingStructure $trainingStructure): self
    {
        if (!$this->trainingStructures->contains($trainingStructure)) {
            $this->trainingStructures[] = $trainingStructure;
            $trainingStructure->setType($this);
        }

        return $this;
    }

    public function removeTrainingStructure(TrainingStructure $trainingStructure): self
    {
        if ($this->trainingStructures->contains($trainingStructure)) {
            $this->trainingStructures->removeElement($trainingStructure);
            // set the owning side to null (unless already changed)
            if ($trainingStructure->getType() === $this) {
                $trainingStructure->setType(null);
            }
        }

        return $this;
    }
}
