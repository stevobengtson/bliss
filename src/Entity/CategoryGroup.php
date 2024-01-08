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
