<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TransactionRepository;
use App\Constant\Group;
use App\Constant\Permission;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ApiResource]
#[GetCollection]
#[Get(security: Permission::OBJECT_OWNER)]
#[Post()]
#[Patch(security: Permission::PREVIOUS_OBJECT_OWNER)]
#[Put(securityPostDenormalize: Permission::FULL_OWNER)]
#[Delete(security: Permission::OBJECT_OWNER)]
class Transaction implements OwnedEntityInterface, TrackedEntityInterface
{
    #[Assert\Ulid]
    #[Groups(Group::TRANSACTION_READ)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Groups([Group::TRANSACTION_READ, Group::TRANSACTION_CREATE, Group::TRANSACTION_UPDATE])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $memo = null;

    #[Groups([Group::TRANSACTION_READ, Group::TRANSACTION_CREATE, Group::TRANSACTION_UPDATE])]
    #[ORM\Column]
    private ?bool $cleared = null;

    #[Groups([Group::TRANSACTION_READ, Group::TRANSACTION_CREATE, Group::TRANSACTION_UPDATE])]
    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $credit = null;

    #[Groups([Group::TRANSACTION_READ, Group::TRANSACTION_CREATE, Group::TRANSACTION_UPDATE])]
    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $debit = null;

    #[Groups(Group::TRANSACTION_READ)]
    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $runningBalance = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): static
    {
        $this->memo = $memo;

        return $this;
    }

    public function isCleared(): ?bool
    {
        return $this->cleared;
    }

    public function setCleared(bool $cleared): static
    {
        $this->cleared = $cleared;

        return $this;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(?string $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getDebit(): ?string
    {
        return $this->debit;
    }

    public function setDebit(?string $debit): static
    {
        $this->debit = $debit;

        return $this;
    }

    public function getRunningBalance(): ?string
    {
        return $this->runningBalance;
    }

    public function setRunningBalance(?string $runningBalance): static
    {
        $this->runningBalance = $runningBalance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
