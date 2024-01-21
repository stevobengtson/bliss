<?php

namespace App\Entity;

interface OwnedEntityInterface
{
    public function setOwner(?User $owner): static;

    public function getOwner(): ?User;
}
