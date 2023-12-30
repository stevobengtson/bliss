<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\HasLifecycleCallbacks]
trait BaseEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    private ?Ulid $id;

    #[Column(type: 'datetime', nullable: false)]
    private \DateTimeInterface $createdAt;

    #[Column(type: 'datetime', nullable: false)]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->id = new Ulid();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\PrePersist]
    public function initializeId(): self
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function initializeDates(): self
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function updateDates(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }
}
