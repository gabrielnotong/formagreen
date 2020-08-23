<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CenterTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CenterTypeRepository::class)
 */
class CenterType
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
     * @ORM\OneToMany(targetEntity=TrainingCenter::class, mappedBy="centerType")
     */
    private Collection $trainingCenters;

    public function __construct()
    {
        $this->trainingCenters = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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
     * @return Collection|TrainingCenter[]
     */
    public function getTrainingCenters(): Collection
    {
        return $this->trainingCenters;
    }

    public function addTrainingCenter(TrainingCenter $trainingCenter): self
    {
        if (!$this->trainingCenters->contains($trainingCenter)) {
            $this->trainingCenters[] = $trainingCenter;
            $trainingCenter->setCenterType($this);
        }

        return $this;
    }

    public function removeTrainingCenter(TrainingCenter $trainingCenter): self
    {
        if ($this->trainingCenters->contains($trainingCenter)) {
            $this->trainingCenters->removeElement($trainingCenter);
            // set the owning side to null (unless already changed)
            if ($trainingCenter->getCenterType() === $this) {
                $trainingCenter->setCenterType(null);
            }
        }

        return $this;
    }
}
