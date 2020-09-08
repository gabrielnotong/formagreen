<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user_member")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user_lambda" = "App\Entity\UserLambda", "training_center" = "App\Entity\TrainingCenter"})
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email is already in use by another user."
 * )
 */
abstract class User implements UserInterface
{
    const TYPE_USER_LAMBDA = 'user_lambda';
    const TYPE_TRAINING_CENTER = 'training_center';
    const ADD_MONTHS = "+%d months";
    const NUMBER_OF_MONTHS = [
       3 => 3,
       6 => 6,
       12 => 12
    ];
    const QRCODE_CONTENT = "Name: %s\nEmail: %s\nMember: from %s to %s\nAddress: %s\nPhone number: %s";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Email is mandatory", groups={"training", "userLambda"})
     * @Assert\Email(groups={"training", "userLambda"})
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $hash = null;

    /**
     * @var string|null
     * @Assert\EqualTo(
     *     propertyPath="hash",
     *     message="The two passwords are not the same !",
     *     groups={"training", "userLambda"}
     * )
     */
    public ?string $passwordConfirm = null;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     * @var Collection<Role>
     */
    private Collection $userRoles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $startsAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $endsAt = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    private ?string $qrCode = null;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min="10", max="14")
     */
    private ?string $phoneNumber = null;

    /**
     * @ORM\OneToMany(targetEntity=Prestation::class, mappedBy="userMember")
     */
    private Collection $prestations;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $status = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $deleted = false;

    private ?int $numberOfMonths = null;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->prestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    abstract public function getType(): string;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getRoles(): array
    {
        $roles = $this->userRoles->map(function (Role $role) {
            return $role->getName();
        })->toArray();

        return  ['ROLE_USER', ...$roles];
    }

    public function getPassword(): string
    {
        return $this->hash;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return  $this->userRoles;
    }

    public function addUserRole(Role $role): self
    {
        if (!$this->userRoles->contains($role)) {
            $this->userRoles[] = $role;
            $role->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $role): self
    {
        if ($this->userRoles->contains($role)) {
            $this->userRoles->removeElement($role);
            $role->removeUser($this);
        }

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
            $prestation->setUserMember($this);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): self
    {
        if ($this->prestations->contains($prestation)) {
            $this->prestations->removeElement($prestation);
            // set the owning side to null (unless already changed)
            if ($prestation->getUserMember() === $this) {
                $prestation->setUserMember(null);
            }
        }

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

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function isActive(): ?bool
    {
        return $this->getStatus();
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getNumberOfMonths(): ?int
    {
        return $this->numberOfMonths;
    }

    public function setNumberOfMonths(?int $numberOfMonths): self
    {
        $this->numberOfMonths = $numberOfMonths;

        return $this;
    }
}
