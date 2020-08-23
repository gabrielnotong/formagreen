<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PartnerRepository::class)
 */
class Partner
{
    use AddressTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"partner"})
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"partner"})
     * @Assert\Email(groups={"partner"})
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"partner"})
     */
    private ?string $phoneNumber = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $status = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleted = false;

    /**
     * @ORM\OneToMany(targetEntity=Discount::class, mappedBy="partner")
     */
    private Collection $discounts;

    public function __construct()
    {
        $this->discounts = new ArrayCollection();
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->getStatus();
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return Collection|Discount[]
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    public function addDiscount(Discount $discount): self
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts[] = $discount;
            $discount->setPartner($this);
        }

        return $this;
    }

    public function removeDiscount(Discount $discount): self
    {
        if ($this->discounts->contains($discount)) {
            $this->discounts->removeElement($discount);
            // set the owning side to null (unless already changed)
            if ($discount->getPartner() === $this) {
                $discount->setPartner(null);
            }
        }

        return $this;
    }
}
