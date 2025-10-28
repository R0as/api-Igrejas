<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 4)]
    private string $documentType;

    #[ORM\Column(length: 30)]
    private string $documentNumber;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $birthDate;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 20)]
    private string $phone;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;
    
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $lastTransferDate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $deletedAt;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Church $church = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function setDocumentType(string $documentType): static
    {
        $this->documentType = $documentType;
        return $this;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(string $documentNumber): static
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    public function getBirthDate(): \DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

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

    public function getChurch(): ?Church
    {
        return $this->church;
    }

    public function setChurch(?Church $church): static
    {
        $this->church = $church;
        return $this;
    }

    public function getLastTransferDate(): ?\DateTimeImmutable
    {
        return $this->lastTransferDate;
    }

    public function setLastTransferDate(?\DateTimeImmutable $lastTransferDate): static
    {
        $this->lastTransferDate = $lastTransferDate;
        return $this;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}