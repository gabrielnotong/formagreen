<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrestationRepository::class)
 */
class Prestation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $startsAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $endsAt = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $type = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="prestations")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $userMember = null;

    /**
     * @ORM\ManyToOne(targetEntity=Discount::class, inversedBy="prestations")
     */
    private ?Discount $discount = null;

    /**
     * @ORM\ManyToOne(targetEntity=GreenSpace::class, inversedBy="prestations")
     */
    private ?GreenSpace $greenSpace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUserMember(): ?User
    {
        return $this->userMember;
    }

    public function setUserMember(?User $userMember): self
    {
        $this->userMember = $userMember;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getGreenSpace(): ?GreenSpace
    {
        return $this->greenSpace;
    }

    public function setGreenSpace(?GreenSpace $greenSpace): self
    {
        $this->greenSpace = $greenSpace;

        return $this;
    }
}
