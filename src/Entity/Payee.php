<?php

namespace App\Entity;

use App\Repository\PayeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: PayeeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Payee
{
    use BaseEntity;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'payees')]
    private ?Category $autoCategory = null;

    #[ORM\OneToOne(targetEntity: self::class)]
    private ?self $parentPayee = null;

    #[ORM\OneToMany(mappedBy: 'payee', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'payees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->id = new Ulid();
        $this->transactions = new ArrayCollection();
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

    public function getAutoCategory(): ?Category
    {
        return $this->autoCategory;
    }

    public function setAutoCategory(?Category $autoCategory): static
    {
        $this->autoCategory = $autoCategory;

        return $this;
    }

    public function getParentPayee(): ?self
    {
        return $this->parentPayee;
    }

    public function setParentPayee(?self $parentPayee): static
    {
        $this->parentPayee = $parentPayee;

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
            $transaction->setPayee($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getPayee() === $this) {
                $transaction->setPayee(null);
            }
        }

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
}
