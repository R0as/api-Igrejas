<?php

namespace App\Entity;

use App\Repository\ChurchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChurchRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Church
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 4)]
    private string $ownerDocumentType;

    #[ORM\Column(length: 14, unique: true)]
    private string $ownerDocumentNumber;

    #[ORM\Column(length: 255, unique: true)]
    private string $internalCode;

    #[ORM\Column(length: 20)]
    private string $phone;

    #[ORM\Column(length: 255)]
    private string $addressStreet;

    #[ORM\Column(length: 20)]
    private string $addressNumber;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressComplement = null;

    #[ORM\Column(length: 100)]
    private string $addressCity;

    #[ORM\Column(length: 2)]
    private string $addressState;

    #[ORM\Column(length: 9)]
    private string $addressZipCode;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column]
    private int $membersLimit;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'church', targetEntity: Member::class, orphanRemoval: true)]
    private Collection $members;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->members = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getOwnerDocumentType(): string
    {
        return $this->ownerDocumentType;
    }

    public function setOwnerDocumentType(string $ownerDocumentType): static
    {
        $this->ownerDocumentType = $ownerDocumentType;
        return $this;
    }

    public function getOwnerDocumentNumber(): string
    {
        return $this->ownerDocumentNumber;
    }

    public function setOwnerDocumentNumber(string $ownerDocumentNumber): static
    {
        $this->ownerDocumentNumber = $ownerDocumentNumber;
        return $this;
    }

    public function getInternalCode(): string
    {
        return $this->internalCode;
    }

    public function setInternalCode(string $internalCode): static
    {
        $this->internalCode = $internalCode;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAddressStreet(): string
    {
        return $this->addressStreet;
    }

    public function setAddressStreet(string $addressStreet): static
    {
        $this->addressStreet = $addressStreet;
        return $this;
    }

    public function getAddressNumber(): string
    {
        return $this->addressNumber;
    }

    public function setAddressNumber(string $addressNumber): static
    {
        $this->addressNumber = $addressNumber;
        return $this;
    }

    public function getAddressComplement(): ?string
    {
        return $this->addressComplement;
    }

    public function setAddressComplement(?string $addressComplement): static
    {
        $this->addressComplement = $addressComplement;
        return $this;
    }

    public function getAddressCity(): string
    {
        return $this->addressCity;
    }

    public function setAddressCity(string $addressCity): static
    {
        $this->addressCity = $addressCity;
        return $this;
    }

    public function getAddressState(): string
    {
        return $this->addressState;
    }

    public function setAddressState(string $addressState): static
    {
        $this->addressState = $addressState;
        return $this;
    }

    public function getAddressZipCode(): string
    {
        return $this->addressZipCode;
    }

    public function setAddressZipCode(string $addressZipCode): static
    {
        $this->addressZipCode = $addressZipCode;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;
        return $this;
    }

    public function getMembersLimit(): int
    {
        return $this->membersLimit;
    }

    public function setMembersLimit(int $membersLimit): static
    {
        $this->membersLimit = $membersLimit;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setChurch($this);
        }
        return $this;
    }

    public function removeMember(Member $member): static
    {
        if ($this->members->removeElement($member)) {
            if ($member->getChurch() === $this) {
                $member->setChurch(null);
            }
        }
        return $this;
    }
}