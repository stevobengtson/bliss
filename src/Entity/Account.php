<?php

namespace App\Entity;

use App\Enums\AccountType;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Account
{
    use BaseEntity;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $nickName = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $startingBalance = '0';

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $balance = '0';

    #[ORM\Column(type: Types::SMALLINT, nullable: false, enumType: AccountType::class)]
    private ?AccountType $type = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $clearedBalance = '0';

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $unclearedBalance = '0';

    /**
     * @var Collection<int, Transaction> $transactions
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Transaction::class, orphanRemoval: true)]
    private Collection $transactions;

    public function __construct()
    {
        $this->id = new Ulid();
        $this->transactions = new ArrayCollection();
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): static
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getStartingBalance(): string
    {
        return $this->startingBalance;
    }

    public function setStartingBalance(string $startingBalance): static
    {
        $this->startingBalance = $startingBalance;

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

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

    public function getClearedBalance(): string
    {
        return $this->clearedBalance;
    }

    public function setClearedBalance(string $clearedBalance): static
    {
        $this->clearedBalance = $clearedBalance;

        return $this;
    }

    public function getUnclearedBalance(): string
    {
        return $this->unclearedBalance;
    }

    public function setUnclearedBalance(string $unclearedBalance): static
    {
        $this->unclearedBalance = $unclearedBalance;

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
