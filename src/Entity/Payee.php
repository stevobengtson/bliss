<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Constant\Group;
use App\Constant\Permission;
use App\Repository\PayeeRepository;
use App\State\UserPasswordHasher;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PayeeRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => [Group::PAYEE_READ]],
    denormalizationContext: ["groups" => [Group::PAYEE_CREATE, Group::PAYEE_UPDATE]]
)]
#[GetCollection]
#[Get(security: Permission::OBJECT_USER)]
#[Post]
#[Patch(security: Permission::OBJECT_USER, processor: UserPasswordHasher::class)]
#[Put(security: Permission::FULL_OWNER, processor: UserPasswordHasher::class)]
#[Delete(security: Permission::OBJECT_USER)]
class Payee implements OwnedEntityInterface
{
    #[Assert\Ulid]
    #[Groups(Group::PAYEE_READ)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Budget $budget = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Groups([Group::PAYEE_READ, Group::PAYEE_CREATE, Group::PAYEE_UPDATE])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups([Group::PAYEE_READ, Group::PAYEE_CREATE, Group::PAYEE_UPDATE])]
    #[ORM\ManyToOne]
    private ?Category $linkCategory = null;

    public function getId(): ?Ulid
    {
        return $this->id;
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

    public function getLinkCategory(): ?Category
    {
        return $this->linkCategory;
    }

    public function setLinkCategory(?Category $linkCategory): static
    {
        $this->linkCategory = $linkCategory;

        return $this;
    }
}
