<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    use BaseEntity;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $assigned = '0';

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $activity = '0';

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryGroup $categoryGroup = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\OneToMany(mappedBy: 'autoCategory', targetEntity: Payee::class)]
    private Collection $payees;

    public function __construct()
    {
        $this->id = new Ulid();
        $this->transactions = new ArrayCollection();
        $this->payees = new ArrayCollection();
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

    public function getAssigned(): ?string
    {
        return $this->assigned;
    }

    public function setAssigned(string $assigned): static
    {
        $this->assigned = $assigned;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getCategoryGroup(): ?CategoryGroup
    {
        return $this->categoryGroup;
    }

    public function setCategoryGroup(?CategoryGroup $categoryGroup): static
    {
        $this->categoryGroup = $categoryGroup;

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
            $transaction->setCategory($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCategory() === $this) {
                $transaction->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payee>
     */
    public function getPayees(): Collection
    {
        return $this->payees;
    }

    public function addPayee(Payee $payee): static
    {
        if (!$this->payees->contains($payee)) {
            $this->payees->add($payee);
            $payee->setAutoCategory($this);
        }

        return $this;
    }

    public function removePayee(Payee $payee): static
    {
        if ($this->payees->removeElement($payee)) {
            // set the owning side to null (unless already changed)
            if ($payee->getAutoCategory() === $this) {
                $payee->setAutoCategory(null);
            }
        }

        return $this;
    }
}
