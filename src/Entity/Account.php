<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Enum\AccountType;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Constant\Group;
use App\Constant\Permission;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ["groups" => [Group::ACCOUNT_READ]],
    denormalizationContext: ["groups" => [Group::ACCOUNT_CREATE, Group::ACCOUNT_UPDATE]],
    security: Permission::ROLE_USER
)]
#[GetCollection]
#[Get(security: Permission::OBJECT_OWNER)]
#[Post()]
#[Patch(security: Permission::PREVIOUS_OBJECT_OWNER)]
#[Put(securityPostDenormalize: Permission::FULL_OWNER)]
#[Delete(security: Permission::OBJECT_OWNER)]
#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements OwnedEntityInterface, TrackedEntityInterface
{
    #[Assert\Ulid]
    #[Groups(Group::ACCOUNT_READ)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[Groups([Group::ACCOUNT_READ, Group::ACCOUNT_CREATE, Group::ACCOUNT_UPDATE])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Budget $budget = null;

    #[Groups(Group::ACCOUNT_READ)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $balance = '0';

    #[Assert\Type(AccountType::class)]
    #[Groups([Group::ACCOUNT_READ, Group::ACCOUNT_CREATE, Group::ACCOUNT_UPDATE])]
    #[ORM\Column(type: Types::STRING, nullable: false, enumType: AccountType::class)]
    private ?AccountType $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Groups(Group::ACCOUNT_READ)]
    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(Group::ACCOUNT_READ)]
    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getType(): ?AccountType
    {
        return $this->type;
    }

    public function setType(AccountType $type): static
    {
        $this->type = $type;

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

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setAccount($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAccount() === $this) {
                $transaction->setAccount(null);
            }
        }

        return $this;
    }
}
