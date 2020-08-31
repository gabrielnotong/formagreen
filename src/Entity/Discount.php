<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DiscountRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\NotBlank
     * @Assert\Type("float", message="must be a decimal")
     */
    private ?float $percentage = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min="3", max="255")
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity=Partner::class, inversedBy="discounts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="This value should not be blank. First of all, create a partner.")
     */
    private ?Partner $partner = null;

    /**
     * @ORM\OneToMany(targetEntity=Prestation::class, mappedBy="discount")
     */
    private Collection $prestations;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface", message="Incorrect date format: waiting for yyyy/mm/dd")
     */
    private ?DateTimeInterface $startsAt = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface", message="Incorrect date format: waiting for yyyy/mm/dd")
     * @Assert\GreaterThan(
     *     propertyPath="startsAt",
     *     message="The end date must come after the start date"
     * )
     */
    private ?DateTimeInterface $endsAt = null;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf("%f %s %s", $this->percentage, '%', $this->description);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(?Partner $partner): self
    {
        $this->partner = $partner;

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
            $prestation->setDiscount($this);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): self
    {
        if ($this->prestations->contains($prestation)) {
            $this->prestations->removeElement($prestation);
            // set the owning side to null (unless already changed)
            if ($prestation->getDiscount() === $this) {
                $prestation->setDiscount(null);
            }
        }

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

    public function getStartsAt(): ?DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }
}
