<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait AddressTrait
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(groups={"training", "partner"})
     * @Assert\Range(min="1", groups={"training", "partner"})
     */
    private ?int $streetNumber = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training", "partner"})
     * @Assert\Length(min="3", max="255", groups={"training", "partner"})
     */
    private ?string $streetName = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training", "partner"})
     * @Assert\Length(min="3", max="255", groups={"training", "partner"})
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"training", "partner"})
     * @Assert\Length(min="3", max="255", groups={"training", "partner"})
     */
    private ?string $city = null;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\NotBlank(groups={"training", "partner"})
     * @Assert\Length(min="5", max="15", groups={"training", "partner"})
     */
    private ?string $zipCode = null;

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

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->streetNumber . ' ' . $this->streetName . ', ' . $this->zipCode . ' ' . $this->city . '(' . $this->country . ')';
    }
}
