<?php

namespace App\Entity;

use App\Constant\Group;
use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget implements OwnedEntityInterface, TrackedEntityInterface
{
    #[Assert\Ulid]
    #[Groups(Group::BUDGET_READ)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Assert\NotBlank()]
    #[Groups([Group::BUDGET_READ, Group::BUDGET_CREATE, Group::BUDGET_UPDATE])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Account::class, orphanRemoval: true)]
    private Collection $accounts;

    #[Groups(Group::BUDGET_READ)]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(Group::BUDGET_READ)]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: CategoryGroup::class, orphanRemoval: true)]
    private Collection $gategoryGroups;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->gategoryGroups = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): static
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->setBudget($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): static
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getBudget() === $this) {
                $account->setBudget(null);
            }
        }

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
     * @return Collection<int, GategoryGroup>
     */
    public function getGategoryGroups(): Collection
    {
        return $this->gategoryGroups;
    }

    public function addGategoryGroup(CategoryGroup $gategoryGroup): static
    {
        if (!$this->gategoryGroups->contains($gategoryGroup)) {
            $this->gategoryGroups->add($gategoryGroup);
            $gategoryGroup->setBudget($this);
        }

        return $this;
    }

    public function removeGategoryGroup(CategoryGroup $gategoryGroup): static
    {
        if ($this->gategoryGroups->removeElement($gategoryGroup)) {
            // set the owning side to null (unless already changed)
            if ($gategoryGroup->getBudget() === $this) {
                $gategoryGroup->setBudget(null);
            }
        }

        return $this;
    }
}
