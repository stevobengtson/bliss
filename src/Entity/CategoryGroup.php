<?php

namespace App\Entity;

use App\Repository\CategoryGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: CategoryGroupRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CategoryGroup
{
    use BaseEntity;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $assigned = '0';

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $activity = '0';
    #[ORM\OneToMany(mappedBy: 'categoryGroup', targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'categoryGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->id = new Ulid();
        $this->categories = new ArrayCollection();
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

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setCategoryGroup($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategoryGroup() === $this) {
                $category->setCategoryGroup(null);
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
